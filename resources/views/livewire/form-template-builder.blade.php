<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Form Templates</h2>
        <button wire:click="create" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
            Create Template
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <h3 class="text-lg font-medium text-gray-900 truncate">{{ $template->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1 truncate">{{ $template->description ?: 'No description' }}</p>
                    <div class="mt-4 flex justify-between items-center text-sm text-gray-500">
                        <span>{{ count($template->schema ?? []) }} Fields</span>
                        <div class="space-x-3">
                            <button wire:click="edit({{ $template->id }})" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="Edit"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                            <button wire:click="delete({{ $template->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" title="Delete"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-10 text-center text-gray-500 bg-white shadow-sm rounded-lg border border-gray-200">
                No templates created yet. Click "Create Template" to build one like Google Forms!
            </div>
        @endforelse
    </div>

    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('isModalOpen', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">{{ $template_id ? 'Edit Template' : 'Create Template' }}</h3>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="mb-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Template Name *</label>
                            <input type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                            <textarea wire:model="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                    
                    <hr class="my-6 border-gray-200">
                    <h4 class="text-md font-bold text-gray-800 mb-4">Form Fields</h4>
                    
                    <div class="space-y-4">
                        @foreach($schema as $index => $field)
                            <div class="border border-gray-200 rounded-md p-4 bg-gray-50 relative">
                                <button type="button" wire:click="removeField({{ $index }})" class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-medium text-sm">Remove</button>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Field Label *</label>
                                        <input type="text" wire:model="schema.{{ $index }}.label" placeholder="e.g. Client Name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('schema.'.$index.'.label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Field Type</label>
                                        <select wire:model.live="schema.{{ $index }}.type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="text">Text Input (Short)</option>
                                            <option value="textarea">Long Text (Paragraph)</option>
                                            <option value="number">Number</option>
                                            <option value="date">Date Picker</option>
                                            <option value="time">Time Picker</option>
                                            <option value="radio">Radio Buttons</option>
                                            <option value="dropdown">Dropdown Select</option>
                                            <option value="checkbox">Checkboxes (Multiple)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                @if(in_array($schema[$index]['type'], ['radio', 'checkbox', 'dropdown']))
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Options (Comma separated)</label>
                                        <input type="text" wire:model="schema.{{ $index }}.options" placeholder="e.g. Male, Female, Other" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                @endif
                                
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="schema.{{ $index }}.required" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm text-gray-900">Required Field</label>
                                </div>
                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                                    <h5 class="col-span-full text-xs font-bold text-gray-500 uppercase tracking-wider">Validations</h5>
                                    @if(in_array($schema[$index]['type'], ['text', 'textarea']))
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Min Length</label>
                                            <input type="number" wire:model="schema.{{ $index }}.min_length" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Max Length</label>
                                            <input type="number" wire:model="schema.{{ $index }}.max_length" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        <div class="flex items-center space-x-4 col-span-full mt-2">
                                            <label class="flex items-center text-sm text-gray-700">
                                                <input type="checkbox" wire:model="schema.{{ $index }}.only_characters" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                Only Characters (A-Z)
                                            </label>
                                            <label class="flex items-center text-sm text-gray-700">
                                                <input type="checkbox" wire:model="schema.{{ $index }}.only_numbers" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                Only Numbers
                                            </label>
                                        </div>
                                    @endif
                                    @if($schema[$index]['type'] === 'number')
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Min Value</label>
                                            <input type="number" wire:model="schema.{{ $index }}.min_value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Max Value</label>
                                            <input type="number" wire:model="schema.{{ $index }}.max_value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" wire:click="addField" class="mt-4 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        + Add Another Field
                    </button>
                    @error('schema') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button type="button" wire:click="store" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Template
                    </button>
                    <button type="button" wire:click="$set('isModalOpen', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
