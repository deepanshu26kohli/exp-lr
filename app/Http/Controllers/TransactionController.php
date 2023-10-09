<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionsRequest;
use App\Models\Bank;
use App\Models\Transaction;
use App\Models\TypeOfTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class TransactionController extends Controller
{
    public function getTransaction()
    {
        $data =  Transaction::with(["head:id,name,color,typeoftransaction_id","bank:id,bank_name,account_number","typeoftransaction:id,name"])->get()->toArray();
        if($data){
            return json_encode($data);
        }
        else{
            return json_encode(["message"=>"Unable to fetch transactions"]);
        }
    }
    public function store(TransactionsRequest $request)
    {
        $data = [];
        DB::beginTransaction();
        try {
            $transaction = new Transaction();
            $transaction->amount = $request->amount;
            $transaction->head_id = $request->head_id;
            $transaction->bank_id = $request->bank_id;
            $transaction->typeoftransaction_id = $request->typeoftransaction_id;
            $transaction->date = $request->date;
            $transaction->notes = $request->notes;
            $transaction->save();
            $bankbalance = Bank::where('id',$transaction->bank_id)->get('bank_balance')->toArray();
            $x = TypeOfTransaction::where('id', $transaction->typeoftransaction_id )->get('action')->toArray();
            if($x[0]['action'] == 1 ){
                $bankbalance = $bankbalance[0]["bank_balance"] +  $request->amount;
                Bank::where('id',$transaction->bank_id)->update([
                    "bank_balance" =>  $bankbalance,
                ]);
            }else{
                $bankbalance = $bankbalance[0]['bank_balance'] -  $request->amount;
                if($bankbalance > 0){
                    Bank::where('id',$transaction->bank_id)->update([
                        "bank_balance" =>  $bankbalance,
                    ]);
                }
                else{
                    return ["message"=>"Your balance in this bank is not sufficient to make this transaction"];
                }
            }
            DB::commit();
            $data["message"] = "Transaction added Successfully";
            $data["status"] = 200;
            return json_encode($data);
        } catch (\Exception $e) {

            DB::rollBack();
            $data["message"] = "Transaction Not added ! ";
            $data["status"] = 422;
            Log::info($e);
            return json_encode($data);
        }
    }
    public function edit($id)
    {
        if ($id < 0 || !is_numeric($id)) {
            return ([
                'status' => 200,
                'message' => "Enter valid id",
            ]);
        }
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return json_encode(["message" => "Bank is not available"]);
        }
        return ([
            'status' => 200,
            'transaction' =>   $transaction,
        ]);
    }
    public function update(TransactionsRequest $request, $id)
    {
        if ($id < 0 || !is_numeric($id)) {
            return ([
                'status' => 200,
                'message' => "Enter valid id",
            ]);
        }
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return json_encode(["message" => "Transaction is not available"]);
        }
        $data = [];
        DB::beginTransaction();
        try {
            $transaction->amount = $request->amount;
            $transaction->head_id = $request->head_id;
            $transaction->bank_id = $request->bank_id;
            $transaction->typeoftransaction_id = $request->typeoftransaction_id;
            $transaction->date = $request->date;
            $transaction->notes = $request->notes;
            $transaction->update();
            $bankbalance = Bank::where('id',$transaction->bank_id)->get('bank_balance')->toArray();
            $x = TypeOfTransaction::where('id', $transaction->typeoftransaction_id )->get('action')->toArray();
            if($x[0]['action'] == 1 ){
                $bankbalance = $bankbalance[0]["bank_balance"] +  $request->amount;
                Bank::where('id',$transaction->bank_id)->update([
                    "bank_balance" =>  $bankbalance,
                ]);
            }else{
                $bankbalance = $bankbalance[0]['bank_balance'] -  $request->amount;
                if($bankbalance > 0){
                    Bank::where('id',$transaction->bank_id)->update([
                        "bank_balance" =>  $bankbalance,
                    ]);
                }
                else{
                    return json_encode(["message"=>"Your balance in this bank is not sufficient to make this transaction"]);
                }
            }
            DB::commit();
            $data["message"] = "Transaction updated Successfully";
            $data["status"] = 200;
            return json_encode($data);
        } catch (\Exception $e) {

            DB::rollBack();
            $data["message"] = "Transaction Not Updated ! ";
            $data["status"] = 422;
            Log::info($e);
            return json_encode($data);
        }        
    }
    public function destroy($id){
        if ($id < 0 || !is_numeric($id)) {
            return ([
                'status' => 200,
                'message' => "Enter valid id",
            ]);
        }
        $transaction = Transaction::find($id);
        $z = Transaction::find($id);
        if(!$transaction){
            return json_encode(["message"=>"Transaction is not available"]);
        }
        $check = $transaction->delete();

        if($check){
            $bankbalance = Bank::where('id',$z->bank_id)->get('bank_balance')->toArray();
            $x = $z->typeoftransaction_id;
            if($x == 1 || $x == 5 || $x == 4 ||$x == 7 ){
                $bankbalance = $bankbalance[0]["bank_balance"] -  $z->amount;
                Bank::where('id',$z->bank_id)->update([
                    "bank_balance" =>  $bankbalance,
                ]);
            }else{
                $bankbalance = $bankbalance[0]['bank_balance'] +  $z->amount;
                Bank::where('id',$z->bank_id)->update([
                    "bank_balance" =>  $bankbalance,
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => "Transaction deleted successfully",
         ]);
        }
        else{
            return response()->json([
                'status' => 422,
                'message' => "Could not delete Transaction",
         ]);
        }
    }
    public function income(){
        $income = Transaction::whereHas('TypeOfTransaction', function ($query) {
            $query->where('action', 1);
        })->sum('amount');
        if($income){
            return json_encode(["income"=>$income]);
        }
        return json_encode(["message"=>"Error Could not calculate expense"]);
    }
    public function expense(){
        $expense = Transaction::whereHas('TypeOfTransaction', function ($query) {
            $query->where('action', 0);
        })->sum('amount');
        if($expense){
            return json_encode(["expense"=>$expense]);
        }
        return json_encode(["message"=>"Error Could not calculate expense"]);
    }
    public function search($search){
        $res =Transaction::with(["head:id,name,color,typeoftransaction_id","bank:id,bank_name,account_number","typeoftransaction:id,name"])->where('head_id', 'LIKE', $search)->get();
        if($res){
            return json_encode($res);
        }
        else{
            return json_encode(["message"=>"Could not Search fo this Header"]);
        }
    }
}
