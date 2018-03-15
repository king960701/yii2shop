<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/11
 * Time: 12:36
 */

namespace frontend\aliyun;


use yii\base\Component;

class SmsHandler extends Component
{
    public $ak;
    public $sk;
    public $sign;
    public $template;
    public $_tel;
    public $_params;
    /**
     * 设置手机号
     */
    public function setTel($tel){
        $this->_tel=$tel;
        return $this;
    }
    /**
     * 设置变量值
     */
    public function setParams($params){
        $this->_params=$params;
        return $this;
    }
    /**
     * 发送短信
     */
    public function send(){
        $params = [
            'PhoneNumbers'=>$this->_tel,
            'SignName'=>$this->sign,
            'TemplateCode'=>$this->template,
            'TemplateParam'=>$this->_params
        ];
        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();
        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $this->ak,
            $this->sk,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );

        return ($content->Message == 'OK' && $content->Code == 'OK');
    }
}