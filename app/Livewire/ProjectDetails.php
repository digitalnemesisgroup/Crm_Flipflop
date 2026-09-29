<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Earning;
use App\Models\PayoutRequest;
use App\Models\ActivityLog;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProjectDetails extends Component
{
    use WithFileUploads;

    public $project_id;
    public $project;
    
    // Document Upload Fields
    public $document_file;
    public $document_name;
    public $document_category = '';
    public $is_visible_to_employee = true;

    public function mount($id)
    {
        $this->project_id = $id;
        $this->project = Project::with(['client', 'users', 'invoices.payments', 'documents'])->findOrFail($id);
        
        $user = Auth::user();
        if (!$user->hasRole(['SUPER ADMIN', 'ADMIN'])) {
            if (!$this->project->users->contains($user->id)) {
                abort(403, 'Unauthorized access to this project.');
            }
        }
    }

    public function render()
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['SUPER ADMIN', 'ADMIN']);

        $invoices = $this->project->invoices;
        
        $totalClientPayment = 0;
        foreach ($invoices as $inv) {
            $totalClientPayment += $inv->amount_paid;
        }

        $employeeEarnings = Earning::where('project_id', $this->project->id)->get();
        $totalEmployeePayout = $employeeEarnings->sum('amount');
        
        // For Employee View
        $myEarning = 0;
        $myPaid = 0;
        $myDue = 0;
        
        if (!$isAdmin) {
            $myEarning = $employeeEarnings->where('user_id', $user->id)->sum('amount');
            // Simplified approximation for project-specific 'paid' since payout requests are not necessarily linked to a specific project.
            // But we can check Earning status
            $myPaid = $employeeEarnings->where('user_id', $user->id)->where('status', 'paid')->sum('amount');
            $myDue = $myEarning - $myPaid;
        }

        $activityLogs = [];
        if ($isAdmin) {
            // Search for project string in new_value or related actions
            $activityLogs = ActivityLog::where('new_value', 'like', '%Project: ' . $this->project->name . '%')
                                ->orWhere('action', 'like', '%project%')
                                ->orderBy('created_at', 'desc')
                                ->take(10)
                                ->get();
        }

        return view('livewire.project-details', [
            'isAdmin' => $isAdmin,
            'invoices' => $invoices,
            'totalClientPayment' => $totalClientPayment,
            'totalEmployeePayout' => $totalEmployeePayout,
            'myEarning' => $myEarning,
            'myPaid' => $myPaid,
            'myDue' => $myDue,
            'activityLogs' => $activityLogs,
        ])->layout('layouts.app', ['header' => 'Project Details - ' . $this->project->name]);
    }

    public function uploadDocument()
    {
        $this->validate([
            'document_file' => 'required|file|max:10240', // 10MB Max
            'document_name' => 'required|string|max:255',
            'document_category' => 'required|string|in:Agreement,Invoice,Payment Receipt,Requirement,Project Files,Delivery Files',
            'is_visible_to_employee' => 'boolean',
        ]);

        $path = $this->document_file->store('project_documents', 'public');

        Document::create([
            'project_id' => $this->project->id,
            'name' => $this->document_name,
            'path' => $path,
            'category' => $this->document_category,
            'is_visible_to_employee' => $this->is_visible_to_employee,
            'uploaded_by' => Auth::id(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Document uploaded',
            'new_value' => 'Uploaded ' . $this->document_name . ' (' . $this->document_category . ') for Project: ' . $this->project->name,
            'ip_address' => request()->ip(),
            'device' => request()->header('User-Agent'),
        ]);

        session()->flash('message', 'Document Uploaded Successfully.');

        $this->reset(['document_file', 'document_name', 'document_category', 'is_visible_to_employee']);
        
        // Refresh project data to load new documents
        $this->project->load('documents');
    }

    public function deleteDocument($id)
    {
        $document = Document::findOrFail($id);
        if (Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        }
        $document->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Document deleted',
            'new_value' => 'Deleted document ' . $document->name . ' from Project: ' . $this->project->name,
            'ip_address' => request()->ip(),
            'device' => request()->header('User-Agent'),
        ]);

        session()->flash('message', 'Document Deleted Successfully.');
        $this->project->load('documents');
    }
}
