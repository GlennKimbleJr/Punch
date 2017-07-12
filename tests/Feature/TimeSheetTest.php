<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;

class TimeSheetTest extends TestCase
{
    /** @test */
    public function unauthenticated_users_cannot_view_the_reports()
    {
        $this->withExceptionHandling();

        $request = $this->post('/clock/report');

        $request->assertStatus(302);
    }

    /** @test */
    public function authenticated_users_can_view_the_reports()
    {
        $this->signIn();

        $request = $this->post('/clock/report');

        $request->assertStatus(200);
    }

    /** @test */
    public function authenticated_users_can_only_view_a_report_of_their_own_punches()
    {
        $user = create('App\User');
        $otherUser = create('App\User');
        create('App\Clock', ['user_id' => $user->id]);
        create('App\Clock', ['user_id' => $otherUser->id]);
        
        $this->signIn($user);
        $request = $this->post('/clock/report');

        $request->assertSeeText('"user_id":"' . $user->id . '"');
        $request->assertDontSee('"user_id":"' . $otherUser->id . '"');
    }

    /** @test */
    public function the_current_weeks_punches_are_returned_by_default()
    {
        $user = create('App\User');

        create('App\Clock', [
            'user_id' => $user->id,
            'in_at' => $twoWeeksAgo = Carbon::parse('2 weeks ago'),
            'out_at' => Carbon::parse('2 weeks ago')->addHours(8)
        ]);

        create('App\Clock', [
            'user_id' => $user->id,
            'in_at' => $currentTime = Carbon::now(),
            'out_at' => Carbon::now(),
        ]);
        
        $this->signIn($user);
        $request = $this->post('/clock/report');

        $request->assertSeeText('"in_at":"' . $currentTime . '"');
        $request->assertDontSee('"in_at":"' . $twoWeeksAgo . '"');
    }

    /** @test */
    public function you_can_view_any_single_weeks_punches_by_submitting_any_given_day_of_that_week()
    {
        $this->signIn($user = create('App\User'));
        
        create('App\Clock', [
            'user_id' => $user->id,
            'in_at' => $twoWeeksAgo = Carbon::parse('2 weeks ago'),
            'out_at' => Carbon::parse('2 weeks ago')->addHours(8)
        ]);
        
        $request = $this->post('/clock/report', [
            'week' => $twoWeeksAgo
        ]);

        $request->assertSeeText('"in_at":"' . $twoWeeksAgo . '"');
    }

    /** @test */
    public function multiple_punches_from_a_given_day_are_totaled_up()
    {
        $this->signIn($user = create('App\User'));
        create('App\Clock', [
            'user_id' => $user->id,
            'in_at' => $twoWeeksAgo = Carbon::parse('8 hours ago'),
            'out_at' => Carbon::now()
        ], 2);
        
        $request = $this->post('/clock/report');

        $json = json_decode(json_encode($request->json()));
        $this->assertEquals(16, $json->timesheets->{$twoWeeksAgo->format('Y-m-d')}->total);
    }

    /** @test */
    public function totals_of_multiple_days_in_a_given_week_are_totaled_up()
    {
        $this->signIn($user = create('App\User'));
        create('App\Clock', [
            'user_id' => $user->id,
            'in_at' => $twoWeeksAgo = Carbon::parse('8 hours ago'),
            'out_at' => Carbon::now()
        ], 2);

        create('App\Clock', [
            'user_id' => $user->id,
            'in_at' => $twoWeeksAgo = Carbon::parse('1 day ago'),
            'out_at' => Carbon::parse('1 day ago')->addHours(8)
        ], 2);
        
        $request = $this->post('/clock/report');

        $json = json_decode(json_encode($request->json()));
        $this->assertEquals(32, $json->total);
    }
}
