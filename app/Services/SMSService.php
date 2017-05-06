<?php
namespace App\Services;

class SMSService
{
    public static function sendMessage($phone)
    {
        return rand(10000,999999);
        $ch = curl_init();

        //生成验证码内容
        $security_code = rand(10000,999999);
        $content = '【趣组队】您收到的验证码是：'.$security_code.'，请勿告诉他人，5分钟内有效';

        $url = 'http://apis.baidu.com/kingtto_media/106sms/106sms?mobile='.$phone.'&content='.$content.'&tag=2';


        // apikey从106短信购买
        $api_string = 'apikey:'.env('SMS_API_KEY');
        $header = array(
            $api_string,
        );

        // 执行HTTP请求
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch , CURLOPT_URL , $url);
        $result = curl_exec($ch);

        //处理结果
        $result = json_decode($result, true);
        if($result['returnstatus'] == 'Success') {
            return $security_code;
        }
        return false;
    }
}