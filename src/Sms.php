<?php
namespace clonelin\sms;
use clonelin\sms\library\SendTypeEnum;


class Sms {

    // 发送短信
    public static function send($sendType,$params = []){
        if(!in_array($sendType,[SendTypeEnum::ALI_SMS,SendTypeEnum::LE_SMS])){
            throw new \Exception("Send Type Invalidate");
            return false;
        }
        // 校验参数
        $tmpAuthAccount = isset($params['auth_account']) ? $params['auth_account'] : '';
        $tmpAuthPassword = isset($params['auth_password']) ? $params['auth_password'] : '';
        $tmpSign = isset($params['sign']) ? $params['sign'] : '';
        $tmpSendAccount = isset($params['send_account']) ? $params['send_account'] : '';
        $tmpContent = isset($params['content']) ? $params['content'] : '';

        if(empty($tmpAuthAccount) || empty($tmpAuthPassword) || empty($tmpContent) || empty($tmpSign) || empty($tmpSendAccount)){
            throw new \Exception("Params Invalidate");
            return false;
        }

        if($sendType == SendTypeEnum::ALI_SMS) {
            $tmpTemplateID = isset($params['template_id']) ? $params['template_id'] : '';
            if(empty($tmpTemplateID)){
                throw new \Exception("Params Invalidate");
                return false;
            }
            // 阿里云发送短信
            AliSms::send($params);
        }else{
            LeXinSms::send($params);
        }



        return true;
    }
}
