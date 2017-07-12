<?php 

namespace App\Reports;

use App\User;
use App\Clock;
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

            if (! isset($array['timesheets'][$key])) {
                $array['timesheets'][$key] = [
                    'punches' => [],
                    'total' => 0
                ];
            }

            $array['timesheets'][$key]['punches'][] = $record;
            $array['timesheets'][$key]['total'] += $record->total;
            $array['total'] += $record->total;
        });

        return $array;
    }
}