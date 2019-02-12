<?php

namespace App\Http\Controllers;

use App\Mail\IconReport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\B2c;
use App\Product;

class IconController extends Controller
{
    public function __construct()
    {
        $this->connection = \DB::connection('oracle');
   
    }
    function test()
    {
        $email = 'ademola.adewumi@crccreditbureau.com';
        $bvn = '22145793506';
        $firstname = 'Popoola';
        $test = $this->connection->select("select * from aa_individual_sub_details where email = '$email'");
        return $test;
        //$this->connection->table('AA_INDIVIDUAL_SUB_DETAILS704')->insert(['email' => $email]);
    }
    function icon(Request $request)
    {
        if(strtolower(request()->server()['REQUEST_METHOD']) !== 'post'){
            return response()->json(['status' => 'error', 'message' => 'Invalid method. Please check that your method is post'], 403);
        }
        $payload = $request->all();
        $required_fields = ['email', 'acc_num','bvn','phone','product_id','gender','dob','customer_email','firstname','lastname'];
        foreach($required_fields as $required){
            if(!array_key_exists($required, $payload)){
                return response()->json(['status' => 'errMsg', 'message' => 'Required parameter "'.$required.'" not found'], 403);
            }
        }
        if (\App\User::where('email', $request->email)->exists()){
            $user = \App\User::where('email', '=', $request->email)->first();
            if ($user->activate === "inactive"){
                return response()->json(['status' => 'errMsg', 'message' => 'User is Inactive. Please contact the Administrator for activation'], 403);
            }

            $gender = $request->gender;
            $dob = $request->dob;
            $email = $request->customer_email;
            $bvn = $request->bvn;
            $acc_num = $request->acc_num;
            $phone = $request->phone;
            $firstname = $request->firstname;
            if(isset($request->middlename)){
                $middlename = $request->middlename;
            }
            $lastname = $request->lastname;

            //CON Plus
            if ($request->product_id === "CRC004"){
                $this->connection->table('aa_individual_sub_details')->insert(['email' => $email, 'dateofbirth' => $dob, 'bvn' => $bvn, 'gender' => $gender, 'name' => $names, 'contactno1' => $phone, 'type' => 'COMM', 'firstname' => $firstname, 'middlename'=> $middlename, 'lastname' => $lastname]);
                \DB::table('users')->where('email', $request->email)->increment('hits');
                $b2c = new B2c;
                $b2c->email = $request->email;
                $b2c->b2c_id = 'Email '.$email.' Date Of Birth '.$dob.' BVN '.$bvn;
                $b2c->status = 'hit';
                $b2c->product_id = 'CRC004';
                $b2c->save();
                Mail::to($email)->queue(new LiveRequestReport($file));
                return response()->json(['status' => 'success', 'message' => 'Succesfully made Con Plus request. Expect a response soon.'], 403);
            }
            //iCON Plus
            elseif($request->product_id === "CRC003"){
                if ($request->middlename === ""){
                    $this->connection->table('aa_individual_sub_details')->insert(['email' => $email, 'dateofbirth' => $dob, 'bvn' => $bvn, 'gender' => $gender, 'contactno1' => $phone, 'type' => 'CONS', 'firstname' => $firstname, 'lastname' => $lastname]);
                }
                elseif ($request->middlename != ""){
                    $this->connection->table('aa_individual_sub_details')->insert(['email' => $email, 'dateofbirth' => $dob, 'bvn' => $bvn, 'gender' => $gender, 'contactno1' => $phone, 'type' => 'CONS', 'firstname' => $firstname, 'middlename'=> $middlename, 'lastname' => $lastname]);
                }
                \DB::table('users')->where('email', $request->email)->increment('hits');
                $b2c = new B2c;
                $b2c->email = $request->email;
                $b2c->b2c_id = 'Email '.$email.' Date Of Birth '.$dob.' BVN '.$bvn;
                $b2c->status = 'hit';
                $b2c->product_id = 'CRC003';
                $b2c->save();
                Mail::to($email)->queue(new IconReport());
                return response()->json(['status' => 'success', 'message' => 'Succesfully made Icon Plus request. Expect a response soon.'], 403);
            }
        }
        else{
            return response()->json(['status' => 'errMsg', 'message' => 'Email is not registered with CRC Credit Bureau. Please contact the Administrator for activation'], 403);
        }
    }
}
