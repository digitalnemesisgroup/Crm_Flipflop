<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .header h1 { margin: 0; color: #4338ca; }
        .header-details { text-align: right; }
        .details-container { margin-bottom: 40px; width: 100%; }
        .details-container td { padding: 5px; vertical-align: top; }
        .bill-to { width: 50%; }
        .project-details { width: 50%; text-align: right; }
        table.items { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table.items th { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; padding: 10px; }
        table.items td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-row td { border-top: 2px solid #333; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; color: #777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table style="width: 100%; margin-bottom: 40px;">
            <tr>
                <td>
                    <h1 style="color: #4f46e5; margin: 0;">INVOICE</h1>
                    <p style="margin: 5px 0 0 0;">#{{ $invoice->invoice_number }}</p>
                </td>
                <td style="text-align: right;">
                    <strong>Company Name</strong><br>
                    123 Tech Street<br>
                    Digital City, DC 12345<br>
                    billing@company.com
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-bottom: 40px;" class="details-container">
            <tr>
                <td class="bill-to">
                    <strong style="font-size: 16px;">Bill To:</strong><br>
                    @if($invoice->project && $invoice->project->client)
                        <strong>{{ $invoice->project->client->name }}</strong><br>
                        {{ $invoice->project->client->company }}<br>
                        {{ $invoice->project->client->email }}<br>
                        {{ $invoice->project->client->phone ?? '' }}<br>
                        @if($invoice->project->client->gst_number)
                            GST: {{ $invoice->project->client->gst_number }}
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td class="project-details">
                    <strong>Project:</strong> {{ $invoice->project ? $invoice->project->name : 'N/A' }}<br>
                    <strong>Issue Date:</strong> {{ $invoice->issue_date->format('M d, Y') }}<br>
                    <strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}<br>
                    <strong>Status:</strong> <span style="text-transform: uppercase;">{{ $invoice->status }}</span>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->milestone_description ?? 'Project Services' }}</td>
                    <td style="text-align: right;">₹{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td style="text-align: right; padding-top: 20px;">Total</td>
                    <td style="text-align: right; padding-top: 20px;">₹{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right; color: #666;">Amount Paid</td>
                    <td style="text-align: right; color: #666;">₹{{ number_format($invoice->amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right; color: #d97706; font-weight: bold;">Balance Due</td>
                    <td style="text-align: right; color: #d97706; font-weight: bold;">₹{{ number_format($invoice->amount - $invoice->amount_paid, 2) }}</td>
                </tr>
            </tbody>
        </table>

        @if($invoice->payments->count() > 0)
        <div style="margin-top: 40px;">
            <h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">Payment History</h4>
            <table style="width: 100%; font-size: 12px; text-align: left; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb;">
                        <th style="padding: 5px;">Date</th>
                        <th style="padding: 5px;">Method</th>
                        <th style="padding: 5px;">Ref / Notes</th>
                        <th style="padding: 5px; text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr>
                        <td style="padding: 5px; border-bottom: 1px solid #eee;">{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td style="padding: 5px; border-bottom: 1px solid #eee;">{{ $payment->payment_method }}</td>
                        <td style="padding: 5px; border-bottom: 1px solid #eee;">{{ $payment->notes }}</td>
                        <td style="padding: 5px; border-bottom: 1px solid #eee; text-align: right;">₹{{ number_format($payment->amount_paid, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="footer">
            Thank you for your business!
        </div>
    </div>
</body>
</html>