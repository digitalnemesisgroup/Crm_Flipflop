import re

with open('app/Livewire/MySpecialTasks.php', 'r') as f:
    content = f.read()

target = """        // Build validation rules dynamically
        $rules = [];
        foreach ($this->activeTask->formTemplate->schema as $field) {
            if ($field['required']) {
                $rules['formData.'.$field['id']] = 'required';
            }
        }"""

replacement = """        // Build validation rules dynamically
        $rules = [];
        foreach ($this->activeTask->formTemplate->schema as $field) {
            $fieldRules = [];
            if (!empty($field['required'])) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }
            
            if (isset($field['type']) && $field['type'] == 'number') {
                $fieldRules[] = 'numeric';
                if (!empty($field['min_value'])) $fieldRules[] = 'min:' . $field['min_value'];
                if (!empty($field['max_value'])) $fieldRules[] = 'max:' . $field['max_value'];
            }
            
            if (isset($field['type']) && in_array($field['type'], ['text', 'textarea'])) {
                $fieldRules[] = 'string';
                if (!empty($field['min_length'])) $fieldRules[] = 'min:' . $field['min_length'];
                if (!empty($field['max_length'])) $fieldRules[] = 'max:' . $field['max_length'];
                if (!empty($field['only_characters'])) $fieldRules[] = 'regex:/^[a-zA-Z\s]+$/';
                if (!empty($field['only_numbers'])) $fieldRules[] = 'regex:/^[0-9]+$/';
            }
            
            if (!empty($fieldRules)) {
                $rules['formData.'.$field['id']] = implode('|', $fieldRules);
            }
        }"""

if target in content:
    content = content.replace(target, replacement)
    with open('app/Livewire/MySpecialTasks.php', 'w') as f:
        f.write(content)
    print("Patched MySpecialTasks.php successfully.")
else:
    print("Could not find target block in MySpecialTasks.php.")
