<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    
    // Form fields
    public $user_id;
    public $name;
    public $email;
    public $mobile;
    public $pin;
    public $role = '';
    
    public $skills;
    public $joining_date;
    public $employment_type = '';
    public $status = 'Active';
    
    // Bank fields mapped to JSON
    public $bank_name = '';
    public $account_name = '';
    public $account_number = '';
    public $ifsc_code = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::with('roles')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('mobile', 'like', '%' . $this->search . '%')
            ->paginate(10);
            
        $roles = Role::all();

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.app', ['header' => 'Employee & Team Management']);
    }

    public function openCreateModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->mobile = $user->mobile;
        $this->role = $user->roles->first()?->name ?? '';
        
        $this->skills = is_array($user->skills) ? implode(', ', $user->skills) : $user->skills;
        $this->joining_date = $user->joining_date;
        $this->employment_type = $user->employment_type ?? '';
        $this->status = $user->status ?? 'Active';
        
        if ($user->bank_details && is_array($user->bank_details)) {
            $this->bank_name = $user->bank_details['bank_name'] ?? '';
            $this->account_name = $user->bank_details['account_name'] ?? '';
            $this->account_number = $user->bank_details['account_number'] ?? '';
            $this->ifsc_code = $user->bank_details['ifsc_code'] ?? '';
        } else {
            $this->bank_name = '';
            $this->account_name = '';
            $this->account_number = '';
            $this->ifsc_code = '';
        }
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $rules = [
            'name'              => 'required|string|min:2|max:255|regex:/^[A-Za-z\s\.\-\']+$/',
            'email'             => ['required', 'email:rfc', 'max:255', Rule::unique('users')->ignore($this->user_id)],
            'mobile'            => ['required', 'string', 'regex:/^[6-9]\d{9}$/', Rule::unique('users', 'mobile')->ignore($this->user_id)],
            'role'              => 'required|exists:roles,name',
            'skills'            => 'nullable|string|max:500',
            'joining_date'      => 'nullable|date|before_or_equal:today',
            'employment_type'   => 'required|in:Full-time,Part-time,Contract,Intern,Freelancer',
            'status'            => 'required|in:Active,Inactive,Suspended',
            'bank_name'         => 'nullable|string|max:255',
            'account_name'      => 'nullable|string|max:255|regex:/^[A-Za-z\s]+$/',
            'account_number'    => ['nullable', 'string', 'regex:/^\d{9,18}$/'],
            'ifsc_code'         => ['nullable', 'string', 'regex:/^[A-Za-z]{4}0[A-Za-z0-9]{6}$/'],
        ];

        if (!$this->user_id) {
            $rules['pin'] = ['required', 'string', 'digits_between:4,8'];
        } else {
            $rules['pin'] = ['nullable', 'string', 'digits_between:4,8'];
        }

        $messages = [
            'name.regex'                  => 'Name may only contain letters, spaces, dots, hyphens and apostrophes.',
            'name.min'                    => 'Name must be at least 2 characters.',
            'mobile.regex'                => 'Mobile must be a valid 10-digit Indian number starting with 6, 7, 8 or 9.',
            'mobile.required'             => 'Mobile number is required.',
            'mobile.unique'               => 'This mobile number is already registered.',
            'email.unique'                => 'This email is already registered.',
            'employment_type.required'    => 'Employment type is required.',
            'joining_date.before_or_equal'=> 'Joining date cannot be in the future.',
            'pin.digits_between'          => 'PIN must be between 4 and 8 digits.',
            'account_number.regex'        => 'Account number must be 9 to 18 digits.',
            'ifsc_code.regex'             => 'IFSC must be 4 letters, 0, then 6 alphanumeric characters (e.g. HDFC0001234).',
        ];

        $this->validate($rules, $messages);

        $bank_details = array_filter([
            'bank_name'      => $this->bank_name,
            'account_name'   => $this->account_name,
            'account_number' => $this->account_number,
            'ifsc_code'      => strtoupper((string) $this->ifsc_code),
        ]);

        $skillsArray = $this->skills ? array_map('trim', explode(',', $this->skills)) : null;

        $data = [
            'name'            => $this->name,
            'email'           => $this->email,
            'mobile'          => preg_replace('/\D+/', '', (string) $this->mobile),
            'skills'          => $skillsArray,
            'joining_date'    => $this->joining_date,
            'employment_type' => $this->employment_type,
            'status'          => $this->status,
            'bank_details'    => empty($bank_details) ? null : $bank_details,
        ];

        if ($this->pin) {
            $data['password'] = Hash::make($this->pin);
            $data['pin'] = $this->pin;
        }

        $user = User::updateOrCreate(['id' => $this->user_id], $data);

        // Sync Roles
        $user->syncRoles([$this->role]);

        session()->flash('message', $this->user_id ? 'Employee Profile Updated Successfully.' : 'Employee Created Successfully.');

        $this->closeModal();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'User Deleted Successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->mobile = '';
        $this->pin = '';
        $this->role = '';
        $this->skills = '';
        $this->joining_date = '';
        $this->employment_type = '';
        $this->status = 'Active';
        $this->bank_name = '';
        $this->account_name = '';
        $this->account_number = '';
        $this->ifsc_code = '';
    }
}
