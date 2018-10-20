<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Libraries\Common;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function getGraphData(){
        try{
            $resArr['status'] = 0;
            /** get count of enquiries */
            $daysOfMonth = Common::getDaysOfMonth();
            // $retArr['data']['daysOfMonth'] = $daysOfMonth['days'];
            $dayListing = $daysOfMonth['days'];
            /** get count of subscribers */
            $subs = \App\Models\Subscriber::get()->count();
            $retArr['data']['totalSubs'] = $subs;

            /** get count of visitors */
            $visits = \App\Models\Visitor::get()->count();
            $retArr['data']['totalVisitors'] = $visits;

            /** all enquiries */
            $retArr['data']['allEnquiries'] = \App\Models\Enquiry::get()->count();

            /** all replies */
            $retArr['data']['allReplies'] = \App\Models\Enquiry::where('reply_date', '!=', 'NULL')->get()->count();

            /** get count of enquiries */
            $totalEnquiries = "SELECT DATE_FORMAT(enquiry_date,'%d %b') AS day,count(*) AS Total FROM enquiries WHERE DATE_FORMAT(enquiry_date,'%Y-%m-%d') <= DATE_FORMAT('". $daysOfMonth['start'] ."','%Y-%m-%d') AND DATE_FORMAT(enquiry_date,'%Y-%m-%d') >= DATE_FORMAT('". $daysOfMonth['end'] ."','%Y-%m-%d') GROUP BY DATE_FORMAT(enquiry_date,'%d %b')";
            
            $totalEnq = DB::select($totalEnquiries);
            
            $enq = Common::getFormattedObj($totalEnq);
            $retArr['data']['totalEnquiries'] = array_merge($dayListing,$enq);

            /** get count of replies */
            $totalUnreplied = "SELECT DATE_FORMAT(enquiry_date,'%d %b') AS day,count(*) AS Total FROM enquiries WHERE reply_date IS NULL AND DATE_FORMAT(enquiry_date,'%Y-%m-%d') <= DATE_FORMAT('". $daysOfMonth['start'] ."','%Y-%m-%d') AND DATE_FORMAT(enquiry_date,'%Y-%m-%d') >= DATE_FORMAT('". $daysOfMonth['end'] ."','%Y-%m-%d') GROUP BY DATE_FORMAT(enquiry_date,'%d %b')";
            \Log::info($totalUnreplied);

            $unReplied = DB::select($totalUnreplied);
            $unReplied = Common::getFormattedObj($unReplied);
            $retArr['data']['totalReplies'] = array_merge($dayListing,$unReplied);            

        }catch(\Exception $e){
            $retArr = ['status' => 1,$e->getMessage()];
        }
        return json_encode($retArr);
    }
}
