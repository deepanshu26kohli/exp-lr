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
        return true;
    }
    public function update($id)
    {
        $head = Head::find($id);
        if (!$head) {
            return false;
        }
        $head->name = request('name');
        $head->color = request('color');
        $head->typeoftransaction_id = request('typeoftransaction_id');
        $head->save();
        return true;
    }
}
