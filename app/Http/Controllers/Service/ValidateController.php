<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Tool\Validate\ValidateCode;
use App\Tool\SMS\SendTemplateSMS;
use App\Entity\TempPhone;
use App\Entity\TempEmail;
use App\Entity\Member;
use Illuminate\Http\Request;
use App\Models\M3Result;

class ValidateController extends Controller
{
    public function create(Request $request)
    {
        $validateCode = new ValidateCode;
        $request->session()->put('validate_code',$validateCode->getCode());
        return $validateCode->doimg();
    }

    public function sendSMS(Request $request)
    {

        $m3_result = new M3Result;

        $phone = $request->input('phone','');
        if ($phone=='') {
            $m3_result->status = 1;
            $m3_result->message = "手机号不能为空";
            return $m3_result->toJson();
        }

        $sendTemplateSMS = new SendTemplateSMS;
        $code="";
        $charset="1234567890";
        $_len = strlen($charset) - 1;
        for ($i = 0;$i < 6;++$i) {
            $code .= $charset[mt_rand(0, $_len)];
        }

        $m3_result = $sendTemplateSMS->sendTemplateSMS($phone, array($code, 60), 1);

        if($m3_result->status == 0){
            $tempPhone = TempPhone::where('phone', $phone)->first();
            if($tempPhone == null) {
            $tempPhone = new TempPhone;
        }
        $tempPhone->phone = $phone;
        $tempPhone->code = $code;
        $tempPhone->deadline = date('Y-m-d H-i-s', time() + 60*60);
        $tempPhone->save();
    }
        
        return $m3_result->toJson();
    }

    public function validateEmail(Request $request)
    {
        $member_id = $request->input('member_id','');
        $code = $request->input('code','');
         if ($member_id == '' || $code == '') {
            return "验证异常";
        }

        $tempEmail = tempEmail::where('member_id',$member_id)->first();
        if ($tempEmail == null) {
            return "验证异常";
        }
        if ($tempEmail->code = $code) {
            if(time()>strtotime($tempEmail->deadline)){
                return "链接失效1";
            }

            $member = Member::find($member_id);
            $member->active = 1;
            $member->save();

            return redirect('/login');
        }else{
            return "链接失效2";
        }
    }

}
