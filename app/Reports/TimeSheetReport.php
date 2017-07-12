<?php 

namespace App\Reports;

use App\User;
use App\Clock;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeSheetReport
{
    protected $request;
    protected $user;
    
    /**
     * Create a new TimeSheetReport instance.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     */
    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Return the requested data as JSON.
     * 
     * @return Illuminate\Database\Query\Builder
     */
    public function get()
    {
        $query = ($this->request->week) 
            ? Clock::getWeek($this->request->week) 
            : Clock::currentWeek();

        $data = $query
            ->whereUserId($this->user->id)
            ->get();

        return $this->transform($data);
    }

    /**
     * Transform the data into the desired formation.
     * 
     * @param  App\Clock $data
     * @return array
     */
    private function transform($data)
    {        
        $array = [
            'timesheets' => [],
            'total' => 0
        ];

        $data->each(function ($record) use (&$array) {
            $key = $record->in_at->format('Y-m-d');
            $day_of_week = Carbon::parse($key)->format('l');

            if (! isset($array['timesheets'][$key])) {
                $array['timesheets'][$key] = [
                    'day_of_week' => $day_of_week,
                    'punches' => [],
                    'total' => 0,
                    'button' => (date('Y-m-d') == $record->in_at->format('Y-m-d')) ? 'hide' : 'show'
                ];
            }

            $array['total'] += $record->total;
            $array['timesheets'][$key]['total'] += $record->total;
            $array['timesheets'][$key]['punches'][] = [
                'day_of_week' => $day_of_week,
                'in_time' => $record->in_time,
                'out_time' => $record->out_time,
                'total' => $record->total,
                'class' => (date('Y-m-d') == $record->in_at->format('Y-m-d')) ? '' : 'hide'
            ];
        });

        return $array;
    }
}