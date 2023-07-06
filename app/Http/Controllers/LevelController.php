<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\LevelOption;
use Validator;

class LevelController extends Controller
{
    public function __construct() {

        $this->middleware('auth');
    }



    public function index(){

        $activity = Level::with('options')->get()->toJson();
        return response($activity, 200);

    }




    public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'activity_id' => 'required|integer',
            // 'media' => 'mimes:jpeg,png,jpg,mp4,mov,ogg,flv,mov,avi|max:15360',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = new Level;
        $data->name = $request->name;
        $data->activity_id = $request->activity_id;
        $data->description = $request->description;
        $data->status = $request->status;
        $data->s_no = $request->s_no;
         $data->media = $request->media;

       
        // if($request->hasfile('media'))
        // {
        //     $file = $request->file('media');
        //     $media = $file->getClientOriginalName();
        //     $path = 'storage/level/';
        //     $upload = $file->move($path, $media); 
        //     $data->media = $media;
        //  }


        $data->save();


         if(count($request->option_text) > 0 ){

            foreach($request->option_text as $key => $option_text){
                $level_option = new LevelOption;
                $level_option->level_id = $data->id;
                $level_option->option_text = $option_text;
                $level_option->points = $request->points[$key];
                $level_option->save();
            }

        }


         return response()->json([
            'message' => 'Level Added Successfully',
            'status' => 200
        ], 200);
 

    }



    public function update(Request $request,$id){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'activity_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = Level::find($id);
        $data->name = $request->name;
        $data->activity_id = $request->activity_id;
        $data->description = $request->description;
        $data->status = $request->status;
        $data->s_no = $request->s_no;
         $data->media = $request->media;
      

        // if($request->hasfile('media'))
        // {
        //     $file = $request->file('media');
        //     $media = $file->getClientOriginalName();
        //     $path = 'storage/level/';
        //     $upload = $file->move($path, $media); 
        //  }

        $data->update();

        if($request->option_id){


        if(count($request->option_id) > 0 ){

            foreach($request->option_id as $key => $value){

                if(!empty($request->option_id[$key])){
                     $level_option = LevelOption::find($value);
                      $level_option->option_text = $request->option_text[$key];
                      $level_option->points = $request->points[$key];
                      $level_option->update();
                }else{
                    $level_option = new LevelOption;
                    $level_option->level_id = $data->id;
                    $level_option->option_text = $request->option_text[$key];
                    $level_option->points = $request->points[$key];
                    $level_option->save();
                }
            }
        }

        }

         return response()->json([
            'message' => 'Level Updated Successfully',
            'status' => 200
        ], 200);
 

    }







    public function getLevel($id){
        $data = Level::with('options')->where('id',$id)->first();

        if($data){

            return response()->json([
                'message' => 'View Level',
                'data' => $data,
                'status' => 200
            ], 200);
        }else{
            return response()->json([
                'message' => 'Wrong Level id',
                'status' => 201
            ], 201);
        }

    }




    public function deleteLevel($id){

        if(Level::where('id', $id)->exists()) {
            $data = Level::find($id);
    	    $activity_id = $data->activity_id;
            $data->delete();
            $milstones  = Level::where('activity_id',$activity_id)->get();
      	  $i= 1;
  
        foreach($milstones as $milstone){
        
        	$milstone->s_no = 'L'.$i;
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


    public function deleteLevelOption($id){

        if(LevelOption::where('id', $id)->exists()) {
            $data = LevelOption::find($id);
            $data->delete();

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
