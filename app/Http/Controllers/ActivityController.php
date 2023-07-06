<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Validator;

class ActivityController extends Controller
{
    public function __construct() {

        $this->middleware('auth');
    }



    public function index(){

        $activity = Activity::get()->toJson();
        return response($activity, 200);

    }




    public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'day' => 'required',
            'milestone_id' => 'required|integer',
            // 'media' => 'mimes:jpeg,png,jpg,mp4,mov,ogg,flv,avi|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = new Activity;
        $data->name = $request->name;
        $data->milestone_id = $request->milestone_id;
        $data->day = $request->day;
        $data->description = $request->description;
        $data->extra = $request->extra;
        $data->status = $request->status;
        $data->s_no = $request->s_no;
         $data->media = $request->media;
        

        // if($request->hasfile('media'))
        // {
        //     $file = $request->file('media');
        //     $media = $file->getClientOriginalName();
        //     $path = 'storage/activity/';
        //     $upload = $file->move($path, $media); 
        //     $data->media = $media;
        //  }


        $data->save();

         return response()->json([
            'message' => 'Activity Added Successfully',
            'status' => 200
        ], 200);
 

    }



    public function update(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'day' => 'required',
            'milestone_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = Activity::find($id);
        $data->name = $request->name;
        $data->day = $request->day;
        $data->milestone_id = $request->milestone_id;
        $data->description = $request->description;
        $data->extra = $request->extra;
        $data->status = $request->status;
        $data->s_no = $request->s_no;
         $data->media = $request->media;


        // if($request->hasfile('media'))
        // {
        //     $file = $request->file('media');
        //     $media = $file->getClientOriginalName();
        //     $path = 'storage/activity/';
        //     $upload = $file->move($path, $media); 
        //     $data->media = $media;
        //  }

        $data->update();

         return response()->json([
            'message' => 'Activity Updated Successfully',
            'status' => 200
        ], 200);
 

    }







    public function getActivity($id){
        $data = Activity::where('id',$id)->with('levels')->first();

        if($data){

            return response()->json([
                'message' => 'View Activity',
                'data' => $data,
                'status' => 200
            ], 200);
        }else{
            return response()->json([
                'message' => 'Wrong Activity id',
                'status' => 201
            ], 201);
        }

    }




    public function deleteActivity($id){

        if(Activity::where('id', $id)->exists()) {
            $data = Activity::find($id);
        $miliestone_id = $data->milestone_id;
            $data->delete();
            $milstones  = Activity::where('milestone_id',$miliestone_id)->get();
        $i= 1;
  
        foreach($milstones as $milstone){
        
        	$milstone->s_no = 'A'.$i;
        	$milstone->update();
      		 $i++;
        }


            return response()->json([
              "message" => "records deleted"
            ], 202);
          } else {
            return response()->json([
              "message" => "record not found"
            ], 404);
          }

    }

}
