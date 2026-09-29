import re

with open('app/Livewire/MySpecialTasks.php', 'r') as f:
    content = f.read()

target = """        if (!empty($rules)) {
            $this->validate($rules, [
                'required' => 'This field is required.'
            ]);
        }"""

replacement = """        if (!empty($rules)) {
            $messages = [
                'required' => 'This field is required.',
                'min' => 'Value is too small/short.',
                'max' => 'Value is too large/long.',
                'regex' => 'Invalid format. Please check constraints (e.g. only characters or only numbers).',
                'numeric' => 'Must be a valid number.'
            ];
            
            // Custom messages for specific regex rules can be tricky generically, so we use a general regex message
            
            $this->validate($rules, $messages);
        }"""

if target in content:
    content = content.replace(target, replacement)
    with open('app/Livewire/MySpecialTasks.php', 'w') as f:
        f.write(content)
    print("Patched messages successfully.")
else:
    print("Could not find messages block.")
