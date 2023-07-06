<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use Validator;

class SkillController extends Controller
{
    
    public function __construct() {

        $this->middleware('auth');
    }



    public function index(){

        $skill = Skill::get()->toJson(JSON_PRETTY_PRINT);
        return response($skill, 200);

    }




    public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'color' => 'required',
            'border_color' => 'required',
            'order' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = new Skill;
        $data->name = $request->name;
        $data->color = $request->color;
        $data->border_color = $request->border_color;
        $data->s_no = $request->s_no;
        $data->status = $request->status;
        $data->skill_order = $request->order;
        $data->save();



         return response()->json([
            'message' => 'Skill Added Successfully',
            'status' => 200
        ], 200);
 

    }



    public function update(Request $request,$id){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'color' => 'required',
            'border_color' => 'required',
            'order' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = Skill::find($id);
        $data->name = $request->name;
        $data->color = $request->color;
        $data->border_color = $request->border_color;
        $data->s_no = $request->s_no;
        $data->status = $request->status;
		 $data->skill_order = $request->order;
        $data->update();

         return response()->json([
            'message' => 'Skill Updated Successfully',
            'status' => 200
        ], 200);
 

    }







    public function getSkill($id){
        $data = Skill::with('milestones')->where('id', $id)->get();


        if($data){

            return response()->json([
                'message' => 'View Skill',
                'data' => $data,
                'status' => 200
            ], 200);
        }else{
            return response()->json([
                'message' => 'Wrong Skill id',
                'status' => 201
            ], 201);
        }

    }




    public function deleteSkill($id){

        if(Skill::where('id', $id)->exists()) {
            $data = Skill::find($id);
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
