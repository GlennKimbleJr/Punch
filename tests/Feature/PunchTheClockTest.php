<?php

namespace Tests\Feature;

use App\User;
use App\Punch;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PunchTheClockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_punch_in()
    {
        $response = $this->postJson(route('punch-in'));

        $response->assertStatus(401);
    }

    /** @test */
    public function a_guest_cannot_punch_out()
    {
        $response = $this->postJson(route('punch-out'));

        $response->assertStatus(401);
    }

    /** @test */
    public function an_employee_can_punch_in()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->postJson(route('punch-in'));

        $response->assertStatus(200);
        $this->assertCount(1, Punch::all());
    }

    /** @test */
    public function an_employee_can_punch_out()
    {
        $user = factory(User::class)->create();
        $user->punch();

        $response = $this->actingAs($user->fresh(), 'api')->post(route('punch-out'));

        $response->assertStatus(200);
        $this->assertFalse($user->fresh()->isPunchedIn());
    }

    /** @test */
    public function an_employee_cannot_punch_out_if_they_are_not_punched_in()
    {
        $user = factory(User::class)->create();

        $this->assertFalse($user->isPunchedIn());

        $response = $this->actingAs($user, 'api')->post(route('punch-out'));

        $response->assertSessionHasErrors('invalid-punch');
    }

    /** @test */
    public function an_employee_cannot_punch_in_if_they_are_already_punched_in()
    {
        $user = factory(User::class)->create();
        $user->punch();
        $user = $user->fresh();

        $this->assertTrue($user->isPunchedIn());

        $response = $this->actingAs($user, 'api')->post(route('punch-in'));

        $response->assertSessionHasErrors('invalid-punch');
    }
}
