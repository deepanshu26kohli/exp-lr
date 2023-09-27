<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function getTransaction()
    {
        $data =  Transaction::with(["head:id,name,color,typeoftransaction_id","bank:id,bank_name,account_number"])->get()->toArray();
        return json_encode($data);
    }
}
