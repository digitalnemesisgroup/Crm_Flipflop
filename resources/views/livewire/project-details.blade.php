<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ $project->name }}</h2>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $project->status === 'active' ? 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20' : 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' }} capitalize">
            {{ $project->status }}
        </span>
    </div>

    @if($isAdmin)
        <!-- ADMIN VIEW -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Project Information -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Project Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Value</p>
                            <p class="text-xl font-bold text-gray-900">₹{{ number_format($project->total_budget, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Employee Payout %</p>
                            <p class="text-xl font-bold text-indigo-600">{{ $project->employee_payout_percentage }}%</p>
                        </div>
                        <div class="col-span-2 mt-2">
                            <p class="text-sm text-gray-500">Description</p>
                            <p class="text-sm text-gray-800 mt-1">{{ $project->description ?: 'No description provided.' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Financials -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Financial Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                            <p class="text-sm font-medium text-green-800">Total Client Payment Received</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">₹{{ number_format($totalClientPayment, 2) }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <p class="text-sm font-medium text-blue-800">Total Employee Payout (Calculated)</p>
                            <p class="text-2xl font-bold text-blue-600 mt-1">₹{{ number_format($totalEmployeePayout, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Invoices -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Invoices & Billing</h3>
                    <x-table>
                            <x-table.thead>
                                <x-table.tr>
                                    <x-table.th>Invoice #</x-table.th>
                                    <x-table.th>Amount</x-table.th>
                                    <x-table.th>Status</x-table.th>
                                    <x-table.th class="text-right">Action</x-table.th>
                                </x-table.tr>
                            </x-table.thead>
                            <x-table.tbody>
                                @forelse($invoices as $inv)
                                    <x-table.tr>
                                        <x-table.td>{{ $inv->invoice_number }}</x-table.td>
                                        <x-table.td>₹{{ number_format($inv->amount, 2) }}</x-table.td>
                                        <x-table.td>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $inv->status === 'paid' ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' : 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20' }}">
                                                {{ $inv->status }}
                                            </span>
                                        </x-table.td>
                                        <x-table.td>
                                            <a href="{{ route('invoices.download', $inv->id) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center justify-end">
                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                PDF
                                            </a>
                                        </x-table.td>
                                    </x-table.tr>
                                @empty
                                    <x-table.tr>
                                        <x-table.td colspan="4">No invoices found for this project.</x-table.td>
                                    </x-table.tr>
                                @endforelse
                            </x-table.tbody>
                        </x-table>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Client Information -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Client Information</h3>
                    @if($project->client)
                        <div class="flex items-center mb-3">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold">
                                {{ substr($project->client->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $project->client->name }}</p>
                                <p class="text-xs text-gray-500">{{ $project->client->company ?: 'No Company' }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 mt-4 text-sm text-gray-600">
                            <p><strong>Email:</strong> {{ $project->client->email }}</p>
                            <p><strong>Phone:</strong> {{ $project->client->phone ?: 'N/A' }}</p>
                            <p><strong>GST:</strong> {{ $project->client->gst_number ?: 'N/A' }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No client assigned.</p>
                    @endif
                </div>

                <!-- Assigned Team -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Assigned Team</h3>
                    <ul class="space-y-3">
                        @forelse($project->users as $member)
                            <li class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">{{ $member->name }}</span>
                                </div>
                            </li>
                        @empty
                            <p class="text-sm text-gray-500">No team members assigned.</p>
                        @endforelse
                    </ul>
                </div>
                
                <!-- Documents -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Documents</h3>
                    
                    <!-- Upload Form -->
                    <form wire:submit.prevent="uploadDocument" class="mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Document Name</label>
                                <input type="text" wire:model="document_name" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('document_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <select wire:model="document_category" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Select Category</option>
                                    <option value="Agreement">Agreement</option>
                                    <option value="Invoice">Invoice</option>
                                    <option value="Payment Receipt">Payment Receipt</option>
                                    <option value="Requirement">Requirement</option>
                                    <option value="Project Files">Project Files</option>
                                    <option value="Delivery Files">Delivery Files</option>
                                </select>
                                @error('document_category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">File Upload</label>
                                <input type="file" wire:model="document_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                @error('document_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2 flex items-center">
                                <input id="is_visible" type="checkbox" wire:model="is_visible_to_employee" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_visible" class="ml-2 block text-sm text-gray-900">
                                    Visible to Employee / Freelancer
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                            Upload Document
                        </button>
                    </form>

                    <!-- Document List -->
                    <ul class="divide-y divide-gray-200">
                        @forelse($project->documents as $doc)
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $doc->name }} <span class="text-xs text-gray-500">({{ $doc->category }})</span></p>
                                        <p class="text-xs text-gray-500">{{ $doc->is_visible_to_employee ? 'Visible to Team' : 'Internal Only' }} - Uploaded on {{ $doc->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="View"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></a>
                                    <button wire:click="deleteDocument({{ $doc->id }})" wire:confirm="Delete this document?" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </div>
                            </li>
                        @empty
                            <p class="text-sm text-gray-500 italic">No documents uploaded yet.</p>
                        @endforelse
                    </ul>
                </div>

                <!-- Activity Log -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Recent Activity</h3>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @forelse($activityLogs as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">{{ $log->action }} <span class="font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</span></p>
                                                    @if($log->new_value)
                                                        <p class="text-xs text-gray-500 mt-1">{{ $log->new_value }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <p class="text-sm text-gray-500 italic">No recent activity.</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- EMPLOYEE VIEW -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Project Information -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Project Information</h3>
                    <p class="text-sm text-gray-800">{{ $project->description ?: 'No description provided.' }}</p>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-sm font-bold text-gray-900 capitalize">{{ $project->status }}</p>
                        </div>
                    </div>
                </div>

                <!-- My Payout -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">My Payout Summary (This Project)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                            <p class="text-xs font-medium text-indigo-800">Total Earning</p>
                            <p class="text-xl font-bold text-indigo-600 mt-1">₹{{ number_format($myEarning, 2) }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                            <p class="text-xs font-medium text-green-800">Paid</p>
                            <p class="text-xl font-bold text-green-600 mt-1">₹{{ number_format($myPaid, 2) }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                            <p class="text-xs font-medium text-yellow-800">Due</p>
                            <p class="text-xl font-bold text-yellow-600 mt-1">₹{{ number_format($myDue, 2) }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">* Check 'My Earnings' tab for exact global available balance.</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- My Bills / Invoices -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Bills / Invoices</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse($invoices as $inv)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $inv->invoice_number }}</p>
                                    <p class="text-xs text-gray-500">₹{{ number_format($inv->amount, 2) }} - <span class="capitalize {{ $inv->status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ $inv->status }}</span></p>
                                </div>
                                <a href="{{ route('invoices.download', $inv->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Download PDF</a>
                            </li>
                        @empty
                            <p class="text-sm text-gray-500 italic">No bills available.</p>
                        @endforelse
                    </ul>
                </div>

                <!-- Documents -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Shared Documents</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse($project->documents->where('is_visible_to_employee', true) as $doc)
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $doc->name }} <span class="text-xs text-gray-500">({{ $doc->category }})</span></p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="View"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></a>
                            </li>
                        @empty
                            <p class="text-sm text-gray-500 italic">No documents shared yet.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
