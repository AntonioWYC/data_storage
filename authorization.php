<?php 
    //响应前台的请求
    $timeStamp = $_GET['t'];
    $randomStr = $_GET['r'];
    $signature = $_GET['s'];
    $str = arithmetics($timeStamp,$randomStr);
    
    if($str != $signature){
        echo '-1';
        exit;
    }else{
        echo '1';
    }
    /**
     * @param $timeStamp 时间戳
     * @param $randomStr 随机字符串
     * @return string 返回签名
     */
    function arithmetics($timeStamp,$randomStr){
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

?>