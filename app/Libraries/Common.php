<?php
namespace App\Libraries;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Common
{
    /**
     * get days of previous 30 days fron today
     * @return array date in 'Mon Date'
     */
    public static function getDaysOfMonth()
    {
        $start = Carbon::now();
        $end = Carbon::now()->subDays(30);

        $response['start'] = $start->format('Y-m-d');
        $response['end'] = $end->format('Y-m-d');
        
        $dates = [];
        // do date equal to today
        for($date = $end; $date->lte($start); $date->addDay()) {
            $dates[$date->format('d M')] = 0;
        }
        $response['days'] = $dates;
        return $response;
    }

    public static function getFormattedObj($dataObj){
        $tmpArr = [];
        $members = collect();
        foreach($dataObj as $res){
            $members->push($res);
        }
        $res = json_decode($members,true);
        foreach($res as $val){
            $tmpArr[$val['day']] = $val['Total'];
        }
        return $tmpArr;
    }
}