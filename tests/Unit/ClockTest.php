<?php

namespace Tests\Unit;

use App\User;
use App\Clock;
use Carbon\Carbon;
use Tests\TestCase;

class ClockTest extends TestCase
{
    /** @test */
    public function status_returns_false_if_the_user_isnt_clocked_in()
    {
        $user = create(User::class);
        $this->signIn($user);

        $clock = create(Clock::class, [
            'user_id' => $user->id,
            'in_at' => Carbon::now(),
            'out_at' => Carbon::now()
        ]);

        $this->assertFalse(Clock::status());
    }

    /** @test */
    public function status_returns_true_if_the_user_is_clocked_in()
    {
        $user = create(User::class);
        $this->signIn($user);

        $clock = create(Clock::class, [
            'user_id' => $user->id,
            'in_at' => Carbon::now(),
            'out_at' => null
        ]);

        $this->assertTrue(Clock::status());
    }

    /** @test */
    public function toggle_clocks_you_out_if_your_clocked_in()
    {
        $user = create(User::class);
        $this->signIn($user);
        create(Clock::class, [
            'user_id' => $user->id,
            'in_at' => Carbon::now(),
            'out_at' => null
        ]);

        $this->assertTrue(Clock::status());

        Clock::toggle();

        $this->assertFalse(Clock::status());
    }
}
