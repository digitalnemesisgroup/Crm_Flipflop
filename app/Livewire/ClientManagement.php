<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
use Illuminate\Validation\Rule;

class ClientManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    
    // Form fields
    public $client_id;
    public $name;
    public $email;
    public $phone;
    public $company;
    public $address;
    public $gst_number;
    public $status = 'active';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clients = Client::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('company', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.client-management', [
            'clients' => $clients,
        ])->layout('layouts.app', ['header' => 'Client Management']);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $this->client_id = $id;
        $this->name = $client->name;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->company = $client->company;
        $this->address = $client->address;
        $this->gst_number = $client->gst_number;
        $this->status = $client->status;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clients')->ignore($this->client_id)],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Client::updateOrCreate(['id' => $this->client_id], [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'address' => $this->address,
            'gst_number' => $this->gst_number,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->client_id ? 'Client Updated Successfully.' : 'Client Created Successfully.');

        $this->closeModal();
    }

    public function delete($id)
    {
        Client::find($id)->delete();
        session()->flash('message', 'Client Deleted Successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->client_id = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->company = '';
        $this->address = '';
        $this->gst_number = '';
        $this->status = 'active';
    }
}
