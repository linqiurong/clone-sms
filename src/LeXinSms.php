<?php
namespace clonelin\sms;

use clonelin\sms\library\SmsInterface;

class LeXinSms implements SmsInterface{


    protected static $target = 'http://cf.51welink.com/submitdata/Service.asmx/g_Submit';

    // 乐信短信
    public static function send($params = []){

        if(empty($params)){
            throw new \Exception("SMS Params Must");
        }
        $name = $params['auth_account'];
        $password = $params['auth_password'];
        $mobile= $params['send_account'];
        $template = $params['content'];
        $sign = $params['sign'];

        //替换成自己的测试账号,参数顺序和wenservice对应
        $post_data = "sname={$name}&spwd={$password}&scorpid=&sprdid=1012818&sdst={$mobile}&smsg=".rawurlencode("{$template}【{$sign}】");
        $gets = self::smsPost($post_data, self::$target);
        return $gets;
    }

    // 短信POST
    private static function smsPost($data,$target){

        $url_info = parse_url($target);
        $httpHeader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpHeader .= "Host:" . $url_info['host'] . "\r\n";
        $httpHeader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpHeader .= "Content-Length:" . strlen($data) . "\r\n";
        $httpHeader .= "Connection:close\r\n\r\n";
        $httpHeader .= $data;

        $fd = fsockopen($url_info['host'], 80);
        fwrite($fd, $httpHeader);
        $gets = "";
        while(!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);
        if($gets != ''){
            $start = strpos($gets, '<?xml');
            if($start > 0) {
                $gets = substr($gets, $start);
            }
        }
        return $gets;
    }

}
 
