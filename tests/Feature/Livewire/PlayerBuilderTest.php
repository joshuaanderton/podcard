<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\PlayerBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PlayerBuilderTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(PlayerBuilder::class);

        $component->assertStatus(200);
    }
}
