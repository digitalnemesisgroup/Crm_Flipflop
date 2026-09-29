<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\FormTemplate;
class FormTemplateBuilder extends Component {
    public $templates = [];
    public $isModalOpen = false;
    
    public $template_id = null;
    public $name = '';
    public $description = '';
    public $schema = [];
    
    public function render() {
        $this->templates = FormTemplate::orderBy('created_at', 'desc')->get();
        return view('livewire.form-template-builder')->layout('layouts.app', ['header' => 'Form Templates']);
    }

    public function create() {
        $this->resetFields();
        $this->addField(); // start with one field
        $this->isModalOpen = true;
    }

    public function edit($id) {
        $template = FormTemplate::findOrFail($id);
        $this->template_id = $id;
        $this->name = $template->name;
        $this->description = $template->description;
        $this->schema = $template->schema ?? [];
        $this->isModalOpen = true;
    }

    public function addField() {
                $this->schema[] = [
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
        ];
    }

    public function removeField($index) {
        unset($this->schema[$index]);
        $this->schema = array_values($this->schema);
    }

    public function store() {
        $this->validate([
            'name' => 'required|string|max:255',
            'schema' => 'required|array|min:1',
            'schema.*.label' => 'required|string',
            'schema.*.type' => 'required|in:text,textarea,number,date,time,radio,checkbox,dropdown',
        ], [
            'schema.*.label.required' => 'All fields must have a label.'
        ]);

        // Clean options for types that don't need them
        foreach ($this->schema as &$field) {
            if (!in_array($field['type'], ['radio', 'checkbox', 'dropdown'])) {
                $field['options'] = '';
            }
            $field['required'] = (bool)$field['required'];
        }

        FormTemplate::updateOrCreate(['id' => $this->template_id], [
            'name' => $this->name,
            'description' => $this->description,
            'schema' => $this->schema,
        ]);

        session()->flash('message', 'Form Template saved successfully.');
        $this->isModalOpen = false;
        $this->resetFields();
    }

    public function delete($id) {
        FormTemplate::find($id)->delete();
        session()->flash('message', 'Form Template deleted.');
    }

    public function resetFields() {
        $this->template_id = null;
        $this->name = '';
        $this->description = '';
        $this->schema = [];
    }
}
