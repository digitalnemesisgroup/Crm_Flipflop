<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;

class BankAccountManagement extends Component
{
    public bool $isModalOpen = false;
    public ?int $account_id = null;

    // Form fields
    public string $account_holder_name = '';
    public string $bank_name = '';
    public string $account_number = '';
    public string $ifsc_code = '';
    public string $branch = '';
    public bool $is_primary = false;

    public function render()
    {
        $accounts = BankAccount::where('user_id', Auth::id())
            ->orderByDesc('is_primary')
            ->orderBy('created_at')
            ->get();

        return view('livewire.bank-account-management', [
            'accounts' => $accounts,
        ])->layout('layouts.app', ['header' => 'My Bank Accounts']);
    }

    public function create(): void
    {
        $this->reset(['account_id', 'account_holder_name', 'bank_name',
                      'account_number', 'ifsc_code', 'branch', 'is_primary']);
        $this->isModalOpen = true;
    }

    public function edit(int $id): void
    {
        $acc = BankAccount::where('user_id', Auth::id())->findOrFail($id);
        $this->account_id            = $acc->id;
        $this->account_holder_name   = $acc->account_holder_name;
        $this->bank_name             = $acc->bank_name;
        $this->account_number        = $acc->account_number;
        $this->ifsc_code             = $acc->ifsc_code;
        $this->branch                = $acc->branch ?? '';
        $this->is_primary            = $acc->is_primary;
        $this->isModalOpen           = true;
    }

    public function save(): void
    {
        $this->validate([
            'account_holder_name' => 'required|string|max:255',
            'bank_name'           => 'required|string|max:255',
            'account_number'      => 'required|string|max:30',
            'ifsc_code'           => 'required|string|max:20',
            'branch'              => 'nullable|string|max:255',
            'is_primary'          => 'boolean',
        ]);

        // If marking as primary, unset others
        if ($this->is_primary) {
            BankAccount::where('user_id', Auth::id())
                ->where('id', '!=', $this->account_id ?? 0)
                ->update(['is_primary' => false]);
        }

        BankAccount::updateOrCreate(
            ['id' => $this->account_id],
            [
                'user_id'              => Auth::id(),
                'account_holder_name'  => $this->account_holder_name,
                'bank_name'            => $this->bank_name,
                'account_number'       => $this->account_number,
                'ifsc_code'            => $this->ifsc_code,
                'branch'               => $this->branch ?: null,
                'is_primary'           => $this->is_primary,
            ]
        );

        session()->flash('message', $this->account_id
            ? 'Bank Account Updated Successfully.'
            : 'Bank Account Added Successfully.');

        $this->isModalOpen = false;
    }

    public function setPrimary(int $id): void
    {
        BankAccount::where('user_id', Auth::id())->update(['is_primary' => false]);
        BankAccount::where('user_id', Auth::id())->where('id', $id)->update(['is_primary' => true]);
        session()->flash('message', 'Primary account updated.');
    }

    public function delete(int $id): void
    {
        BankAccount::where('user_id', Auth::id())->where('id', $id)->delete();
        session()->flash('message', 'Bank Account Removed.');
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }
}
