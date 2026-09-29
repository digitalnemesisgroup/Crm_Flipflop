<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoicePdfController extends Controller
{
    public function download($id)
    {
        $invoice = Invoice::with(['project.client', 'payments'])->findOrFail($id);
        
        $user = Auth::user();
        if (!$user->hasRole(['SUPER ADMIN', 'ADMIN'])) {
            // Check if employee is assigned to the project
            if (!$invoice->project || !$invoice->project->users->contains($user->id)) {
                abort(403, 'Unauthorized access to this invoice.');
            }
        }

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        
        return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
    }
}
