<?php

namespace Tests\Unit;

use App\User;
use App\Punch;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PunchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_punched_in_returns_true_if_there_is_a_in_at_time_but_not_a_out_at_time()
    {
        $punch = factory(Punch::class)->create([
            'in_at' => now(),
            'out_at' => null,
        ]);

        $this->assertTrue($punch->isPunchedIn());
    }

    /** @test */
    public function is_punched_in_returns_false_if_there_is_a_in_at_time_and_out_at_time()
    {
        $punch = factory(Punch::class)->create([
            'in_at' => now(),
            'out_at' => now(),
        ]);

        $this->assertFalse($punch->isPunchedIn());
    }
}
