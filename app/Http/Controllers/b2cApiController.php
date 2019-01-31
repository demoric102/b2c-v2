<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 300);

use App\Mail\LiveRequestReport;
use App\Mail\FicoReport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\B2c;
use App\Product;

class b2cApiController extends Controller
{
    public function testLiveRequest(Request $request){
        $user = \App\User::where('email', '=', $request->email)->first();
        $curl = curl_init();
        $mimie_username = '1A0L199999999903';
        $mimie_password = 'CRC2018a';
        $username_static = '39276684crcweb';
        $password_static = 'W3b5it3U53r2oi9';
        $username = $user->live_request_username;
        $password = $user->live_request_password;
        $report_type = $user->product_type;
        $response_type = $user->response_type;
        $names = $request->names;
        $gender = $request->gender;
        $dob = $request->dob;
        $email = $request->email;
        $destination_email = $request->destination_email;
        $bvn = $request->bvn;
        $post_fields = "strUserID=$username&strPassword=$password&strRequest=<REQUEST REQUEST_ID=\"1\"> <REQUEST_PARAMETERS> <REPORT_PARAMETERS REPORT_ID=\"$report_type\" SUBJECT_TYPE=\"1\" RESPONSE_TYPE=\"$response_type\" /> <INQUIRY_REASON CODE=\"1\"/> <APPLICATION PRODUCT=\"0\" NUMBER=\"0\" AMOUNT=\"0\" CURRENCY=\"NGN\" /> </REQUEST_PARAMETERS> <SEARCH_PARAMETERS SEARCH-TYPE=\"0\"> <NAME>$names</NAME> <SURROGATES> <GENDER VALUE=\"$gender\"/> <DOB VALUE=\"$dob\"/> </SURROGATES> </SEARCH_PARAMETERS> </REQUEST>";
        $post_fields_bvn = "strUserID=$username&strPassword=$password&strRequest=<REQUEST REQUEST_ID=\"1\"><REQUEST_PARAMETERS><REPORT_PARAMETERS RESPONSE_TYPE=\"$response_type\" SUBJECT_TYPE=\"1\" REPORT_ID=\"$report_type\"/><INQUIRY_REASON CODE=\"1\"/><APPLICATION CURRENCY=\"NGN\" AMOUNT=\"0\" NUMBER=\"232\" PRODUCT=\"017\"/></REQUEST_PARAMETERS><SEARCH_PARAMETERS SEARCH-TYPE=\"4\"><BVN_NO>$bvn</BVN_NO></SEARCH_PARAMETERS> </REQUEST>";
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://webserver.creditreferencenigeria.net/crcweb/liverequestinvoker.asmx/PostRequest",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "$post_fields_bvn",
            CURLOPT_HTTPHEADER => array(
                "Postman-Token: 43da05c1-4997-4972-8b71-345f1f82324a",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $xml2 = array();
        $reference_number = array();
        $bureau_id = array();
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $xml = simplexml_load_string($response);
            $xml1 = simplexml_load_string($xml);
            $error_msg = $xml1->BODY->{'ERROR-LIST'}->{'ERROR-CODE'};
            if (!empty($error_msg)){
                return $error_msg;
            }
        }
    }
    public function postLiveRequest(Request $request){

        if(strtolower(request()->server()['REQUEST_METHOD']) !== 'post'){
            return response()->json(['status' => 'error', 'message' => 'Invalid method. Please check that your method is post'], 403);
        }
        $payload = $request->all();
        $required_fields = ['email', 'acc_num','bvn','phone','product_id'];
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
            if ($user->live_request_username === "null"){
                return response()->json(['status' => 'errMsg', 'message' => 'User is not yet registered on Live Request App. Please contact the Administrator for registration'], 403);
            }
            
            $curl = curl_init();
            $mimie_username = '1A0L199999999903';
            $mimie_password = 'CRC2018a';
            $username_static = '39276684crcweb';
            $password_static = 'W3b5it3U53r2oi9';
            $username = $user->live_request_username;
            $password = $user->live_request_password;
            //$report_type = $user->product_type;
            $response_type = $user->response_type;
            $names = $request->names;
            $gender = $request->gender;
            $dob = $request->dob;
            $email = $request->email;
            $destination_email = $request->destination_email;
            $bvn = $request->bvn;
            if ($request->report_id == true){
                $report_id = $request->report_id;
            }
            else{
                $report_id = $user->product_type;
            }
            $post_fields = "strUserID=$username&strPassword=$password&strRequest=<REQUEST REQUEST_ID=\"1\"> <REQUEST_PARAMETERS> <REPORT_PARAMETERS REPORT_ID=\"$report_id\" SUBJECT_TYPE=\"1\" RESPONSE_TYPE=\"$response_type\" /> <INQUIRY_REASON CODE=\"1\"/> <APPLICATION PRODUCT=\"0\" NUMBER=\"0\" AMOUNT=\"0\" CURRENCY=\"NGN\" /> </REQUEST_PARAMETERS> <SEARCH_PARAMETERS SEARCH-TYPE=\"0\"> <NAME>$names</NAME> <SURROGATES> <GENDER VALUE=\"$gender\"/> <DOB VALUE=\"$dob\"/> </SURROGATES> </SEARCH_PARAMETERS> </REQUEST>";
            $post_fields_bvn = "strUserID=$username&strPassword=$password&strRequest=<REQUEST REQUEST_ID=\"1\"><REQUEST_PARAMETERS><REPORT_PARAMETERS RESPONSE_TYPE=\"$response_type\" SUBJECT_TYPE=\"1\" REPORT_ID=\"$report_id\"/><INQUIRY_REASON CODE=\"1\"/><APPLICATION CURRENCY=\"NGN\" AMOUNT=\"0\" NUMBER=\"232\" PRODUCT=\"017\"/></REQUEST_PARAMETERS><SEARCH_PARAMETERS SEARCH-TYPE=\"4\"><BVN_NO>$bvn</BVN_NO></SEARCH_PARAMETERS> </REQUEST>";
            if ($bvn != ""){
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://webserver.creditreferencenigeria.net/crcweb/liverequestinvoker.asmx/PostRequest",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 300,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "$post_fields_bvn",
                    CURLOPT_HTTPHEADER => array(
                        "Postman-Token: 43da05c1-4997-4972-8b71-345f1f82324a",
                        "cache-control: no-cache"
                    ),
                ));
            }
            elseif ($names != ""){
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://webserver.creditreferencenigeria.net/crcweb/liverequestinvoker.asmx/PostRequest",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 300,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "$post_fields",
                    CURLOPT_HTTPHEADER => array(
                        "Postman-Token: 43da05c1-4997-4972-8b71-345f1f82324a",
                        "cache-control: no-cache"
                    ),
                ));
            }

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            $xml2 = array();
            $reference_number = array();
            $bureau_id = array();
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $folderPath = "storage/live-request/";
                $xml = simplexml_load_string($response);
                $xml1 = simplexml_load_string($xml);
                $decoded = base64_decode($xml1->PDFResponse);
                if (!empty($xml1->BODY->{'SEARCH-RESULT-LIST'}->{'SEARCH-RESULT-ITEM'})){
                    $xml2[] = $xml1->BODY->{'SEARCH-RESULT-LIST'}->{'SEARCH-RESULT-ITEM'};
                    $reference_number = $xml1['REFERENCE-NO'];
                    $bureau_id = $xml1->BODY->{'SEARCH-RESULT-LIST'}->{'SEARCH-RESULT-ITEM'}['BUREAU-ID'][0];
                    //return $reference_number.' '.$bureau_id;
                }
                
                if (!empty($xml1->BODY->{'ERROR-LIST'}->{'ERROR-CODE'})){
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://webserver.creditreferencenigeria.net/crcweb/liverequestinvoker.asmx/PostRequest",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 300,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "$post_fields",
                        CURLOPT_HTTPHEADER => array(
                            "Postman-Token: 43da05c1-4997-4972-8b71-345f1f82324a",
                            "cache-control: no-cache"
                        ),
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);
                    $xml2 = array();
                    $reference_number = array();
                    $bureau_id = array();
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        $folderPath = "storage/live-request/";
                        $xml = simplexml_load_string($response);
                        $xml1 = simplexml_load_string($xml);
                        if (!empty($xml1->BODY->{'SEARCH-RESULT-LIST'}->{'SEARCH-RESULT-ITEM'})){
                            $xml2[] = $xml1->BODY->{'SEARCH-RESULT-LIST'}->{'SEARCH-RESULT-ITEM'};
                            $reference_number = $xml1['REFERENCE-NO'];
                            $bureau_id = $xml1->BODY->{'SEARCH-RESULT-LIST'}->{'SEARCH-RESULT-ITEM'}['BUREAU-ID'][0];
                            //return $reference_number.' '.$bureau_id;
                        }
                        if (!empty($xml2)){
                            $payload = 
                                "<REQUEST REQUEST_ID=\"1\">
                                    <REQUEST_PARAMETERS>
                                        <REPORT_PARAMETERS REPORT_ID=\"6110\" SUBJECT_TYPE=\"1\" RESPONSE_TYPE=\"3\"/>
                                        <INQUIRY_REASON CODE=\"1\" />
                                        <APPLICATION PRODUCT=\"017\" NUMBER=\"0\" AMOUNT=\"0\" CURRENCY=\"NGN\"/>
                                        <REQUEST_REFERENCE REFERENCE-NO=\"$reference_number\">
                                            <MERGE_REPORT PRIMARY-BUREAU-ID=\"$bureau_id\">
                                                <BUREAU_ID>$bureau_id</BUREAU_ID>    
                                            </MERGE_REPORT>  
                                        </REQUEST_REFERENCE>
                                    </REQUEST_PARAMETERS>
                                </REQUEST>";  
                            $post_fields = "strUserID=$username&strPassword=$password&strRequest=$payload";   
                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://webserver.creditreferencenigeria.net/crcweb/liverequestinvoker.asmx/PostRequest",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => "$post_fields",
                            CURLOPT_HTTPHEADER => array(),
                            ));

                            $response = curl_exec($curl);
                            $err = curl_error($curl);

                            curl_close($curl);

                            if ($err) {
                                echo "cURL Error #:" . $err;
                            } else {
                                $xml = simplexml_load_string($response);
                                $xml1 = simplexml_load_string($xml);
                                $decoded = base64_decode($xml1->PDFResponse);
                                echo $decoded;
                                $file = $folderPath . $bvn . '.pdf';
                                file_put_contents($file, $decoded);
                                Mail::to($destination_email)->queue(new LiveRequestReport($file));
                                if (file_exists($file)) {
                                    header('Content-Description: File Transfer');
                                    header('Content-Type: application/octet-stream');
                                    header('Content-Disposition: attachment; filename="'.basename($file).'"');
                                    header('Expires: 0');
                                    header('Cache-Control: must-revalidate');
                                    header('Pragma: public');
                                    header('Content-Length: ' . filesize($file));
                                    readfile($file);
                                    exit;
                                }
                            }
                        }
                        if($xml1->PDFResponse == '' && $xml2 == ''){
                            \DB::table('users')->where('email', $request->email)->increment('misses');
                            $b2c = new B2c;
                            $b2c->email = $request->email;
                            $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                            $b2c->status = 'miss';
                            $b2c->product_id = 'CRC001';
                            $b2c->save();
                            return $dataArray[] = array(
                                "status"=>"null",
                                "email"=> $request->email,
                                "response"=>"This returned a NO HIT"
                            );
                        }
                        else{
                            \DB::table('users')->where('email', $request->email)->increment('hits');
                            $b2c = new B2c;
                            $b2c->email = $request->email;
                            $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                            $b2c->status = 'hit';
                            $b2c->product_id = 'CRC001';
                            $b2c->save();
                        }
                        $file = $folderPath . $bvn . '.pdf';
                        file_put_contents($file, $decoded);
                        echo $xml1;
                        Mail::to($destination_email)->queue(new LiveRequestReport($file));
                        if (file_exists($file)) {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="'.basename($file).'"');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($file));
                            readfile($file);
                            exit;
                        }
                    }
                }

                if ($user->response_type === "3"){
                    $decoded = base64_decode($xml1->PDFResponse);
                    if($decoded == '' && $xml2 == ''){
                        \DB::table('users')->where('email', $request->email)->increment('misses');
                        $b2c = new B2c;
                        $b2c->email = $request->email;
                        $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                        $b2c->status = 'miss';
                        $b2c->product_id = 'CRC001';
                        $b2c->save();
                        return $dataArray[] = array(
                            "status"=>"null",
                            "email"=> $request->email,
                            "response"=>"This returned a NO HIT"
                        );
                    }else{
                        \DB::table('users')->where('email', $request->email)->increment('hits');
                        $b2c = new B2c;
                        $b2c->email = $request->email;
                        $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                        $b2c->status = 'hit';
                        $b2c->product_id = 'CRC001';
                        $b2c->save();
                        if (!empty($xml2)){
                            $payload = 
                                "<REQUEST REQUEST_ID=\"1\">
                                    <REQUEST_PARAMETERS>
                                        <REPORT_PARAMETERS REPORT_ID=\"6110\" SUBJECT_TYPE=\"1\" RESPONSE_TYPE=\"3\"/>
                                        <INQUIRY_REASON CODE=\"1\" />
                                        <APPLICATION PRODUCT=\"017\" NUMBER=\"0\" AMOUNT=\"0\" CURRENCY=\"NGN\"/>
                                        <REQUEST_REFERENCE REFERENCE-NO=\"$reference_number\">
                                            <MERGE_REPORT PRIMARY-BUREAU-ID=\"$bureau_id\">
                                                <BUREAU_ID>$bureau_id</BUREAU_ID>    
                                            </MERGE_REPORT>  
                                        </REQUEST_REFERENCE>
                                    </REQUEST_PARAMETERS>
                                </REQUEST>";  
                            $post_fields = "strUserID=$username&strPassword=$password&strRequest=$payload";   
                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://webserver.creditreferencenigeria.net/crcweb/liverequestinvoker.asmx/PostRequest",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => "$post_fields",
                            CURLOPT_HTTPHEADER => array(),
                            ));

                            $response = curl_exec($curl);
                            $err = curl_error($curl);

                            curl_close($curl);

                            if ($err) {
                            echo "cURL Error #:" . $err;
                            } else {
                                $xml = simplexml_load_string($response);
                                $xml1 = simplexml_load_string($xml);
                                $decoded = base64_decode($xml1->PDFResponse);
                                echo $decoded;
                                $file = $folderPath . $bvn . '.pdf';
                                file_put_contents($file, $decoded);
                                Mail::to($destination_email)->queue(new LiveRequestReport($file));
                                if (file_exists($file)) {
                                    header('Content-Description: File Transfer');
                                    header('Content-Type: application/octet-stream');
                                    header('Content-Disposition: attachment; filename="'.basename($file).'"');
                                    header('Expires: 0');
                                    header('Cache-Control: must-revalidate');
                                    header('Pragma: public');
                                    header('Content-Length: ' . filesize($file));
                                    readfile($file);
                                    exit;
                                }
                            }
                        }
                        $file = $folderPath . $bvn . '.pdf';
                        file_put_contents($file, $decoded);
                        echo $xml1;
                        Mail::to($destination_email)->queue(new LiveRequestReport($file));
                        if (file_exists($file)) {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="'.basename($file).'"');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($file));
                            readfile($file);
                            exit;
                        }
                    }                    
                }
                elseif ($user->response_type === "1"){
                    $decoded = $xml;
                    if($decoded == '' && $xml2 == ''){
                        \DB::table('users')->where('email', $request->email)->increment('misses');
                        $b2c = new B2c;
                        $b2c->email = $request->email;
                        $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                        $b2c->status = 'miss';
                        $b2c->product_id = 'CRC001';
                        $b2c->save();
                        return response()->json(['status' => 'errMsg', 'message' => 'The search returned NULL'], 403);
                    }else{
                        
                        \DB::table('users')->where('email', $request->email)->increment('hits');
                        $b2c = new B2c;
                        $b2c->email = $request->email;
                        $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                        $b2c->status = 'hit';
                        $b2c->product_id = 'CRC001';
                        $b2c->save();
                        $file = $folderPath . $bvn . '.pdf';
                        if (!empty($xml2)){
                            $payload = 
                                "<REQUEST REQUEST_ID=\"1\">
                                    <REQUEST_PARAMETERS>
                                        <REPORT_PARAMETERS REPORT_ID=\"6110\" SUBJECT_TYPE=\"1\" RESPONSE_TYPE=\"1\"/>
                                        <INQUIRY_REASON CODE=\"1\" />
                                        <APPLICATION PRODUCT=\"017\" NUMBER=\"0\" AMOUNT=\"0\" CURRENCY=\"NGN\"/>
                                        <REQUEST_REFERENCE REFERENCE-NO=\"$reference_number\">
                                            <MERGE_REPORT PRIMARY-BUREAU-ID=\"$bureau_id\">
                                                <BUREAU_ID>$bureau_id</BUREAU_ID>    
                                            </MERGE_REPORT>  
                                        </REQUEST_REFERENCE>
                                    </REQUEST_PARAMETERS>
                                </REQUEST>";  
                            $post_fields = "strUserID=$username&strPassword=$password&strRequest=$payload";   
                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://webserver.creditreferencenigeria.net/crcweb/liverequestinvoker.asmx/PostRequest",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => "$post_fields",
                            CURLOPT_HTTPHEADER => array(),
                            ));

                            $response = curl_exec($curl);
                            $err = curl_error($curl);

                            curl_close($curl);

                            if ($err) {
                            echo "cURL Error #:" . $err;
                            } else {
                            return $response;
                            }
                        }
                        return $xml;
                        if (file_exists($file)) {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="'.basename($file).'"');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($file));
                            readfile($file);
                            exit;
                        }
                    }
                }
                elseif ($user->response_type === "4"){
                    $decoded = $xml;
                    if($decoded == '' && $xml2 == ''){
                        \DB::table('users')->where('email', $request->email)->increment('misses');
                        $b2c = new B2c;
                        $b2c->email = $request->email;
                        $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                        $b2c->status = 'miss';
                        $b2c->product_id = 'CRC001';
                        $b2c->save();
                        return response()->json(['status' => 'errMsg', 'message' => 'The search returned NULL'], 403);
                    }else{
                        \DB::table('users')->where('email', $request->email)->increment('hits');
                        $b2c = new B2c;
                        $b2c->email = $request->email;
                        $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                        $b2c->status = 'hit';
                        $b2c->product_id = 'CRC001';
                        $b2c->save();
                        $file = $folderPath . $bvn . '.pdf';
                        if (!empty($xml2)){
                            return $xml2;
                        }
                        return $xml;
                        // file_put_contents($file, $decoded);
                        // Mail::to($email)->queue(new LiveRequestReport($file));
                        if (file_exists($file)) {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="'.basename($file).'"');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($file));
                            readfile($file);
                            exit;
                        }
                    }
                }
                
                
            }
        }
        else{
            return response()->json(['status' => 'errMsg', 'message' => 'Email not registered with CRC B2C API. Kindly contact customer support'], 403);
        }
    }


    public function postFicoRequest(Request $request)
    {
        if(strtolower(request()->server()['REQUEST_METHOD']) !== 'post'){
            return response()->json(['status' => 'error', 'message' => 'Invalid method. Please check that your method is post'], 403);
        }
        $payload = $request->all();
        $required_fields = ['email', 'acc_num','bvn','phone','product_id','destination_email'];
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
            $email = $request->email;
            $acc_num = $request->acc_num;
            $bvn = $request->bvn;
            $phone = $request->phone;
            $product_id = $request->product_id;
            $names = $request->names;
            $dob = $request->dob;
            $gender = $request->gender;
            $destination_email = $request->destination_email;
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_PORT => "8000",
                CURLOPT_URL => "http://172.16.5.12:8000/api/v1/post/single-batch/return-fico",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 600,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"email\"\r\n\r\n$email\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"json_array\"\r\n\r\n\n\n[\n\t{\n\t\t\"bvn\": \"$bvn\",\n\t\t\"phone\": \"$phone\",\n\t\t\"acc_num\": \"$acc_num\",\n\t\t\"dob\": \"$dob\",\n\t\t\"gender\": \"$gender\",\n\t\t\"names\": \"$names\"\n\t}\n]\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQ0ZGIxNDhhYmVmZjBmZjljMjJlM2M5YWEzYjIzZDJjMTMyYTNmOGI3MzkzYzAwOTNjNjQ0NzVjODUwZWMzY2Q1OGUyN2FjM2I2YzcyNmFiIn0.eyJhdWQiOiIxIiwianRpIjoiNDRkYjE0OGFiZWZmMGZmOWMyMmUzYzlhYTNiMjNkMmMxMzJhM2Y4YjczOTNjMDA5M2M2NDQ3NWM4NTBlYzNjZDU4ZTI3YWMzYjZjNzI2YWIiLCJpYXQiOjE1NDE2NjU4ODYsIm5iZiI6MTU0MTY2NTg4NiwiZXhwIjoxNTczMjAxODg2LCJzdWIiOiI0MCIsInNjb3BlcyI6W119.PixRybl9LWiYRzRUDhBZDHlzHAs4P3iOEIsqBXsz2i0afTlyi_5A9Chgz0gCDN-ODbU9m6nIzWBkgK2kuI7hL9lRmqruO8ax59P4n_R0PcOeMCJRArlwMGJfFpcInvx9HOcO5j5iLFsqGBFNExiQWBzWvp6mZDFdyZd9A4tAgdKiZCzlFLdQsoIWALkC-WQZT9gv12AaVy65TbOAASTRknJbnxnxQ49_4hOHrwUB4uyYdVlkuvTjWjzsHHNgIsSTTJ3576jMF-YG7pMg98cejoow0DB8gmPHf9d5EgYcEVJwF8IM_aMrjscZA4C1oTkE8RDOZhToay29MZL1USmuh8xou2fjQUkwUZjtSiF0F_jBtITRPcNSWogSLsL5GN2jDUEfGm1GcARMzj1qZRqvcWuDaHMAg9sxNDzfhyhzqGwNluPcECjVP0Qsp3iaJ7qBdoOu3KBWIQE--eIqmfjk2ACL8cxl-D9Gh0jj8go1vcdqMX-F6QHLLZPTgShyNndOzPDgzqPq9dgMNwW6fMuhvQfv5SnV_e9OWSYkrClQJTU1nO8yiWCCBbKEHJ4GUsueSsGfKoJi7emFVeoKd5m4vzc1cW6hgUc-TAT2G4BRgAPZjelIP2bKeFEYfT7UciAKWhhJPA19S4-RLV6JVwkQV1k444IrJtdZRhTto2RQhpg",
                    "Postman-Token: dc8d68d8-61a1-40b5-bc4c-f4a26723eaf7",
                    "cache-control: no-cache",
                    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $dataArray = array();
                $data = json_decode($response, true);
                $stripped_zero = $data[0];
                if ($stripped_zero['customer_name'] == 'null'){
                    \DB::table('users')->where('email', $request->email)->increment('misses');
                    $b2c = new B2c;
                    $b2c->email = $request->email;
                    $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                    $b2c->status = 'miss';
                    $b2c->product_id = 'CRC002';
                    $b2c->save();
                    $dataArray[] = array(
                        "customer_name"=>"null",
                        "status"=>"This result returned null",
                        "email"=> $request->email,
                        "response"=>"This BVN $bvn returned NO HIT"
                    );
                    return $dataArray;
                }
                elseif ($stripped_zero['customer_name'] != 'null'){
                    \DB::table('users')->where('email', $request->email)->increment('hits');
                    $b2c = new B2c;
                    $b2c->email = $request->email;
                    $b2c->b2c_id = 'Dob '.$dob.' Names '.$names.' Gender '.$gender;
                    $b2c->status = 'hit';
                    $b2c->product_id = 'CRC002';
                    $b2c->save();
                    $pdf = PDF::loadView('email.fico-report', compact('data'));
                    $pdf->save('storage/fico/'.$data[0]['bvn'].'.pdf');
                    Mail::to($destination_email)->queue(new FicoReport($data));
                    return $response;
                    die();
                }
            }
        }
        else{
            return response()->json(['status' => 'errMsg', 'message' => 'Email not registered with CRC B2C API. Kindly contact customer support'], 403);
        }
    }
}
