<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\TaskManagement;
use Spatie\Permission\Models\Role;
use Livewire\Mechanisms\HandleComponents\HandleComponents;

class LivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_modal()
    {
        Role::create(['name' => 'EMPLOYEE']);
        Role::create(['name' => 'FREELANCER']);
        
        $component = Livewire::test(TaskManagement::class);
        $html = $component->html();
        file_put_contents('/tmp/out.html', $html);
    }
}
