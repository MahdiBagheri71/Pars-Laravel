<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SearchUsers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SearchUsersTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(SearchUsers::class);

        $component->assertStatus(200);
    }
}
