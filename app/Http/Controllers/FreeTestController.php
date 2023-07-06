<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreeTest;
use Validator;

class FreeTestController extends Controller
{
    


    public function __construct() {

        $this->middleware('auth');
    }



    public function index(){

        $free_test = FreeTest::get()->toJson(JSON_PRETTY_PRINT);
        return response($free_test, 200);

    }




    public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'age_group_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = new FreeTest;
        $data->question = $request->question;
        $data->age_group_id = $request->age_group_id;
        $data->status = $request->status;
        $data->save();

         return response()->json([
            'message' => 'Question Added Successfully',
            'status' => 200
        ], 200);
 

    }



    public function update(Request $request,$id){


        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'age_group_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $data = FreeTest::find($id);
        $data->question = $request->question;
        $data->age_group_id = $request->age_group_id;
        $data->status = $request->status;
        $data->update();

         return response()->json([
            'message' => 'Question Updated Successfully',
            'status' => 200
        ], 200);
 

    }







    public function getTest($id){
        $data = FreeTest::find($id);

        if($data){

            return response()->json([
                'message' => 'View Question',
                'data' => $data,
                'status' => 200
            ], 200);
        }else{
            return response()->json([
                'message' => 'Wrong Question id',
                'status' => 201
            ], 201);
        }

    }




    public function deleteTestQue($id){

        if(FreeTest::where('id', $id)->exists()) {
            $data = FreeTest::find($id);
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
