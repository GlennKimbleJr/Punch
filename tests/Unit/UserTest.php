<?php

namespace Tests\Unit;

use App\User;
use App\Punch;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function most_recent_punch_returns_the_single_record_with_the_most_recent_updated_at_timestamp()
    {
        $user = factory(User::class)->create();

        $olderPunch = $user->punches()->create([
            'updated_at' => Carbon::parse('Last Tuesday'),
        ]);

        $newerPunch = $user->punches()->create([
            'updated_at' => now(),
        ]);

        $this->assertEquals($newerPunch->id, $user->mostRecentPunch->id);
        $this->assertNotEquals($olderPunch->id, $user->mostRecentPunch->id);
    }

    /** @test */
    public function is_punched_in_returns_false_if_the_user_has_no_punches()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(0, $user->punches->count());
        $this->assertFalse($user->isPunchedIn());
    }

    /** @test */
    public function is_punched_in_returns_true_if_the_user_is_punched_in()
    {
        $user = factory(User::class)->create();

        $user->punches()->create([
            'in_at' => now(),
        ]);

        $this->assertTrue($user->isPunchedIn());
    }

    /** @test */
    public function is_punched_in_returns_false_if_the_user_is_punched_out()
    {
        $user = factory(User::class)->create();

        $user->punches()->create([
            'in_at' => now(),
            'out_at' => now(),
        ]);

        $this->assertFalse($user->isPunchedIn());
    }
}
