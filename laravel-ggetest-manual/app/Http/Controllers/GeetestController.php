<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GeetestLib;
use Input;

class GeetestController extends Controller
{
    public function getVerify(){
        //实例化并传入极验id与key值
        $GtSdk = new GeetestLib(config('sys.GEE_ID'), config('sys.GEE_KEY'));
        $user_id = "web";
        // $user_id = $geetest['user_id'];
        $status = $GtSdk->pre_process($user_id);
        $data = array(
            'gtserver'=>$status,
            'user_id'=>$user_id
        );
        session(['geetest'=>$data]);
        echo $GtSdk->get_response_str();
    }

    public function login()
    {
        $geetest_challenge = Input::get('geetest_challenge');
        $geetest_validate = Input::get('geetest_validate');
        $geetest_seccode = Input::get('geetest_seccode');
        $GtSdk = new GeetestLib(config('sys.GEE_ID'), config('sys.GEE_KEY'));
        $geetest = session("geetest");
        $user_id = $geetest['user_id'];
        // $user_id = ["web"];
        if ($geetest['gtserver'] == 1) {
            $result = $GtSdk->success_validate($geetest_challenge, $geetest_validate, $geetest_seccode, $user_id);
            if ($result) {
                echo 'Yes!';
            } else{
                echo 'No';
            }
        }else{
           if ($GtSdk->fail_validate($geetest_challenge, $geetest_validate, $geetest_seccode)) {
                echo "yes";
            }else{
                echo "no";
            }
        }
    }
}