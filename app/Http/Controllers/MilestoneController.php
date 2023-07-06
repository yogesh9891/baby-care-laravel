<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Milestone;
use Validator;

class MilestoneController extends Controller
{
    
    public function __construct() {

        $this->middleware('auth');
    }



    public function index(){

        $milestone = Milestone::with('activities','skill')->get()->toJson();
        return response($milestone, 200);

    }




    public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'skill_id' => 'required|integer',
            'age_group_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = new Milestone;
        $data->name = $request->name;
        $data->skill_id = $request->skill_id;
        $data->age_group_id = $request->age_group_id;
        $data->description = $request->description;
        $data->description2 = $request->description2;
        $data->other = $request->other;
        $data->status = $request->status;
        $data->s_no = $request->s_no;
        $data->save();

         return response()->json([
            'message' => 'Milestone Added Successfully',
            'status' => 200
        ], 200);
 

    }



    public function update(Request $request,$id){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'skill_id' => 'required|integer',
            'age_group_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = Milestone::find($id);
        $data->name = $request->name;
        $data->skill_id = $request->skill_id;
        $data->age_group_id = $request->age_group_id;
        $data->description = $request->description;
        $data->description2 = $request->description2;
        $data->other = $request->other;
        $data->s_no = $request->s_no;
        $data->status = $request->status;
        $data->update();

         return response()->json([
            'message' => 'Milestone Updated Successfully',
            'status' => 200
        ], 200);
 

    }







    public function getMilestone($id){
        $data = Milestone::with('activities')->where('id',$id)->first();

        if($data){

            return response()->json([
                'message' => 'View Milestone',
                'data' => $data,
                'status' => 200
            ], 200);
        }else{
            return response()->json([
                'message' => 'Wrong Milestone id',
                'status' => 201
            ], 201);
        }

    }




    public function deleteMilestone($id){

        if(Milestone::where('id', $id)->exists()) {
            $data = Milestone::find($id);
        $skill_id = $data->skill_id ;
            $data->delete();
        $milstones  = Milestone::where('skill_id',$skill_id)->get();
        $i= 1;
  
        foreach($milstones as $milstone){
        
        	$milstone->s_no = 'M'.$i;
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
