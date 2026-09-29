import re

# Update PHP component
with open('app/Livewire/FormTemplateBuilder.php', 'r') as f:
    php_content = f.read()

php_replacement = """        $this->schema[] = [
            'id' => 'field_' . uniqid(),
            'label' => '',
            'type' => 'text',
            'options' => '',
            'required' => true,
            'min_length' => '',
            'max_length' => '',
            'min_value' => '',
            'max_value' => '',
            'only_characters' => false,
            'only_numbers' => false,
        ];"""

php_content = re.sub(
    r"\$this->schema\[\] = \[\s*'id' => 'field_' \. uniqid\(\),\s*'label' => '',\s*'type' => 'text',\s*'options' => '',\s*'required' => true,\s*\];",
    php_replacement,
    php_content
)

with open('app/Livewire/FormTemplateBuilder.php', 'w') as f:
    f.write(php_content)


# Update Blade template
with open('resources/views/livewire/form-template-builder.blade.php', 'r') as f:
    blade_content = f.read()

blade_insert = """
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
"""

# Find where to insert it - right after the required checkbox div
target = """                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="schema.{{ $index }}.required" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label class="ml-2 block text-sm text-gray-900">Required Field</label>
                                </div>"""

blade_content = blade_content.replace(target, target + blade_insert)

with open('resources/views/livewire/form-template-builder.blade.php', 'w') as f:
    f.write(blade_content)

print("Patched Builder and Blade successfully.")
