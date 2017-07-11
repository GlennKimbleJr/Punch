<?php

namespace Tests\Feature;

use App\Clock;
use Tests\TestCase;

class TimeClockTest extends TestCase
{
    /** @test */
    public function unauthenticated_users_cannot_punch_the_clock()
    {
        $this->withExceptionHandling();

        $response = $this->post('/clock/punch');

        $response->assertStatus(302);
    }

    /** @test */
    public function authenticated_users_can_punch_the_clock()
    {
        $this->signIn();

        $response = $this->post('/clock/punch');

        $response->assertStatus(200);
    }

    /** @test */
    public function users_are_clocked_in_when_they_punch_the_clock()
    {
        $this->signIn();

        $response = $this->post('/clock/punch');

        $this->assertTrue(Clock::status());
    }

    /** @test */
    public function clocked_in_users_are_clocked_out_when_they_punch_the_clock()
    {
        $this->signIn();
        $this->post('/clock/punch');
        $this->assertTrue(Clock::status());

        $this->post('/clock/punch');

        $this->assertFalse(Clock::status());
    }
}
