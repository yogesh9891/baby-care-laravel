<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use Mail,PDF,DB;
=======
// use Mail,PDF,DB;
use Carbon\Carbon;
>>>>>>> new_deployment
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $new = new StdClass;
$new->percent= 60;
$new->da_of_child = 7.2;
$new->value =-2.8;
$new->result='Ahead of the Curve by 1 Month';
$s['Gross Motor'] = $new;
$s['Fine Motor'] = $new;
$s['Cognitive'] = $new;

   $data["age"] = 34;
   $data["result"] = 2.5;
   $data["gender1"] = 'he';
   $data["gender2"] = 'his';
   $data["gender3"] = 'him';
        $data["name"] =  'Yogesh';

        $data["id"] = 154;
        $data["skills"] = $s; 
<<<<<<< HEAD
    // return view('emails.report3',$data);

              $pdf = PDF::loadView('emails.report3', $data);
        return $pdf->download('pdfview.pdf');

$data['subject']= 'CompassTot - Self-Assessment Test Report - '.date('Ymd').'-'.$data["id"].'-'.strtoupper($data["name"]);

        Mail::send('emails.welcome_report', $data, function($message)use($data, $pdf) {
            $message->to('yogesh@ebslon.com', $data["name"])
                    ->subject($data['subject'])
                    ->attachData($pdf->output(), "Reports.pdf");
                    $message->from('no-reply@compasstot.com','Welcome in Compasstot Family');
});
});
=======
    // return view('emails.report',$data);

>>>>>>> new_deployment

//               $pdf = PDF::loadView('emails.report', $data);
//         return $pdf->download('pdfview.pdf');

// $data['subject']= 'CompassTot - Self-Assessment Test Report - '.date('Ymd').'-'.$data["id"].'-'.strtoupper($data["name"]);

//         Mail::send('emails.welcome_report', $data, function($message)use($data, $pdf) {
//             $message->to('yogesh@ebslon.com', $data["name"])
//                     ->subject($data['subject'])
//                     ->attachData($pdf->output(), "Reports.pdf");
//                     $message->from('no-reply@compasstot.com','Welcome in Compasstot Family');
// });
});

