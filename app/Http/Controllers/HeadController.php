<?php

namespace App\Http\Controllers;

use App\Http\Requests\HeadsRequest;
use App\Models\Head;
use Illuminate\Http\Request;

class HeadController extends Controller
{
    public function gethead()
    {
        $data =  Head::with(["type_of_transaction:id,name"])->get()->toArray();
        return json_encode($data);
    }
    public function store(HeadsRequest $request)
    {   
        $head = new Head();
        $head->name = $request->name;
        $head->color = $request->color;
        $head->typeoftransaction_id = $request->typeoftransaction_id;
        $head->save();
        return json_encode(["message" => "Head added Successfully", "status" => 200]);
    }
    public function edit($id)
    {
        if ($id < 0 || !is_numeric($id)) {
            return ([
                'status' => 200,
                'message' => "Enter valid id",
            ]);
        }
        $head = Head::with(["type_of_transaction:id,name"])->find($id);
        if(!$head){
            return json_encode(["message"=>"Head is not available"]);
        }
        return ([
            'status' => 200,
            'head' => $head,
        ]);
    }
    public function update(HeadsRequest $request, $id)
    {
        if ($id < 0 || !is_numeric($id)) {
            return ([
                'status' => 200,
                'message' => "Enter valid id",
            ]);
        }
        $head = Head::with(["type_of_transaction:id,name"])->find($id);
        if(!$head){
            return json_encode(["message"=>"Head is not available"]);
        }
        $head->name = $request->name;
        $head->color = $request->color;
        $head->typeoftransaction_id = $request->typeoftransaction_id;
        $check = $head->update();
        if($check){
            return ([
                'status' => 200,
                'message' => "Head Updated Successfully",
            ]);
        }
        else{
            return ([
                'status' => 200,
                'message' => "Head Not Updated ",
            ]);
        }
       
    }
    public function destroy($id){
        if ($id < 0 || !is_numeric($id)) {
            return ([
                'status' => 200,
                'message' => "Enter valid id",
            ]);
        }
        $head = Head::with(["type_of_transaction:id,name"])->find($id);
        if(!$head){
            return json_encode(["message"=>"Head is not available"]);
        }
        
        $check = $head->delete();
        if($check){
            return response()->json([
                'status' => 200,
                'message' => "Head deleted successfully",
         ]);
        }
        else{
            return response()->json([
                'status' => 200,
                'message' => "Could not delete head",
         ]);
        }
        
    }
}
