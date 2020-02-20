<?php

class c_lang
{
    function createNonceStr($length = 8){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return "z".$str;
    }

    function arithmetic($timeStamp,$randomStr){
        $arr['timeStamp'] = $timeStamp;
        $arr['randomStr'] = $randomStr;
        $arr['token'] = 'SDETTICKETTOKEN';
        //按照首字母大小写顺序排序
        sort($arr,SORT_STRING);
        //拼接成字符串
        $str = implode($arr);
        //进行加密
        #$signature = sha1($str);
        global $signature;
        $signature = md5($signature);
        //转换成大写
        $signature = strtoupper($signature);
        return $signature;
    }

    public function lang($key){
        global $randomStr;
        $randomstr = $this->createNonceStr();
        $timeStamp = time();
        $lang = array (
            "randomStr" => $randomstr,
            "timeStamp" => $timeStamp,
            "signature" => $this->arithmetic($timeStamp, $randomStr)
        );
        return $lang[$key];
    }

}
?>