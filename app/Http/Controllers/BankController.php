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
        $data =  Bank::get()->toArray();
        return json_encode($data);
    }
    public function store(BanksRequest $request)
    {
        $data = [];
        DB::beginTransaction();
        try {
            $bank = new Bank();
            $bank->bank_name = $request->bank_name;
            $bank->holder_name = $request->holder_name;
            $bank->account_number = $request->account_number;
            $bank->ifsc_code = $request->ifsc_code;
            $bank->save();
            DB::commit();
            $data["message"] = "Bank added Successfully";
            $data["status"] = 200;
            return json_encode($data);
        } catch (\Exception $e) {

            DB::rollBack();
            $data["message"] = "Bank Not added ! ";
            $data["status"] = 422;
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
        $bank->bank_name = $request->bank_name;
        $bank->holder_name = $request->holder_name;
        $bank->account_number = $request->account_number;
        $bank->ifsc_code = $request->ifsc_code;
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
}
