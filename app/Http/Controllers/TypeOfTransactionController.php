<?php

namespace App\Http\Controllers;

use App\Models\TypeOfTransaction;
use Illuminate\Http\Request;

class TypeOfTransactionController extends Controller
{
    public function get_type_of_transaction(){
        $data =  TypeOfTransaction::all()->toArray();
        return json_encode($data);
    }
}
