<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


   public function authorise(){
    
     return response()->json([
                        'message' => 'Error',
                        'data' => 'Not Authorised!',
                        'status' => false,
                    ], 200);
    
    }
}
