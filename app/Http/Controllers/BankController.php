<?php

namespace App\Http\Controllers;

use App\Http\Requests\BanksRequest;
use App\Models\Bank;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankController extends Controller
{
    public function getbank()
    {
        $data =  Bank::where('ifbank',1)->get()->toArray();
        return json_encode($data);
    }
    public function store(BanksRequest $request)
    {
        $data = [];
     
        DB::beginTransaction();
        try {
            $bank = new Bank();
            if($request->bank_name == "Cash"){
                Log::info($request->bank_name);
                $bank->bank_name = $request->bank_name;
                $bank->ifbank = 0;
                $bank->holder_name = "0";
                $bank->account_number = "0";
                $bank->ifsc_code = 0;
                $bank->bank_balance = $request->bank_balance;
            }
            else{
                $bank->bank_name = $request->bank_name;
                $bank->holder_name = $request->holder_name;
                $bank->account_number = $request->account_number;
                $bank->ifsc_code = $request->ifsc_code;
                $bank->ifbank = 1;
                $bank->bank_balance = $request->bank_balance;
            }
          
            
            
            $bank->save();
            DB::commit();
            $data["message"] = "Bank added Successfully";
            $data["status"] = 200;
            return json_encode($data);
        } catch (\Exception $e) {

            DB::rollBack();
            $data["message"] = "Bank Not added ! ";
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
        $bank = Bank::find($id);
        if (!$bank) {
            return json_encode(["message" => "Bank is not available"]);
        }
        return ([
            'status' => 200,
            'bank' => $bank,
        ]);
    }
    public function update(BanksRequest $request, $id)
    {
        if ($id < 0 || !is_numeric($id)) {
            return ([
                'status' => 200,
                'message' => "Enter valid id",
            ]);
        }
        $bank = Bank::find($id);
        if (!$bank) {
            return json_encode(["message" => "Bank is not available"]);
        }
        if($request->bank_name == "Cash"){
            Log::info($request->bank_name);
            $bank->bank_name = "Cash";
            $bank->ifbank = 0;
            $bank->holder_name = "0";
            $bank->account_number = "0";
            $bank->ifsc_code = 0;
            $bank->bank_balance = $request->bank_balance;
        }
        else{
            $bank->bank_name = $request->bank_name;
            $bank->holder_name = $request->holder_name;
            $bank->account_number = $request->account_number;
            $bank->ifsc_code = $request->ifsc_code;
            $bank->ifbank = 1;
            $bank->bank_balance = $request->bank_balance;
        }
        $check = $bank->update();
        if($check){
            return ([
                'status' => 200,
                'message' => "Bank Updated Successfully",
            ]);
        }
        else{
            return ([
                'status' => 422,
                'message' => "Bank Not Updated",
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
        $bank = Bank::find($id);
        if(!$bank){
            return json_encode(["message"=>"Bank is not available"]);
        }
        $check = $bank->delete();
        if($check){
            return response()->json([
                'status' => 200,
                'message' => "Bank deleted successfully",
         ]);
        }
        else{
            return response()->json([
                'status' => 422,
                'message' => "Could not delete bank ",
         ]);
        }
    }
    public function getTotalAmount(){
        $totalAmount = Bank::sum('bank_balance');
        if($totalAmount){
            return json_encode(["totalAmount"=>$totalAmount,  'status' => 200,]);
        }
        else{
            return json_encode(["message"=>"Could not fetch the total balance",'status' => 200]);
        }
    }
    public function getBankAmount(){
        $totalAmount = Bank::where('ifbank',1)->sum('bank_balance');
        if($totalAmount){
            return json_encode(["totalAmount"=>$totalAmount,  'status' => 200,]);
        }
        else{
            return json_encode(["message"=>"Could not fetch the total bank balance",'status' => 422]);
        }
    }
    public function getCashAmount(){
        $totalAmount = Bank::where('ifbank',0)->sum('bank_balance');
        if($totalAmount){
            return json_encode(["totalAmount"=>$totalAmount,  'status' => 200,]);
        }
        else{
            return json_encode(["message"=>"Could not fetch the total cash balance",'status' => 422]);
        }
    }
}
