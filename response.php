<?php 
class Response{ 
    /*
    * 封通信接口数据
    * @param integer $code 状态码
    * @param string $message 状态信息
    * @param array $data 数据
    * return string 
    */
    public static function api_response($code, $message='',
            $data=array()){
        $type = isset($_REQUEST['format'])?$_REQUEST['format']:'';
        switch ($type) {
            case 'json':
                self::json_response($code, $message, $data);
                break;
            case 'xml':
                self::xml_response($code, $message, $data);
                break;
            case 'array':
                echo var_dump(self::grant_array($code, $message, $data));
                break;
            default:
                self::json_response($code, $message, $data);
                break;
        }
    }
    /*
    * Get ticket status
    * @param integer $code 状态码
    * @param string $message 状态信息
    * @param array $data 数据
    * return string 
    */
    public static function response_ticket_status($code, $ticketid=''){
        $STAGE_DFP_SERVICE_URL = "https://stage-dfp-service-internal.shdrapps.disney.com/shdr-parkentry-service/tickets/".$ticketid;
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_URL, $STAGE_DFP_SERVICE_URL);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        curl_setopt($ci, CURLOPT_NOBODY, FALSE); 
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ci, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ci, CURLOPT_TIMEOUT, 20);
    
        $content=curl_exec($ci);
        $ticketinfo=curl_getinfo($ci,CURLINFO_HTTP_CODE);
        if (strpos($content,'NOTFOUND'))
            return "false";
        else
            return "true";
    }
    public static function response_entry_ticket($code, $ticketid=''){
        $STAGE_DFP_SERVICE_URL = "https://stage-dfp-service-internal.shdrapps.disney.com/shdr-parkentry-service/tickets/add";
        $header_array = array("Content-Type: application/json");
        $pool = '[{"oneSourceParkId":"shdr","ticketCode":10004,"ticketId":"'.$ticketid.'"}]';
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_URL, $STAGE_DFP_SERVICE_URL);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $header_array);
        curl_setopt($ci, CURLOPT_POSTFIELDS, $pool);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        curl_setopt($ci, CURLOPT_NOBODY, FALSE); 
        curl_setopt($ci, CURLOPT_POST, TRUE);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ci, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ci, CURLOPT_TIMEOUT, 20);
    
        $content=curl_exec($ci);
        $ticketinfo=curl_getinfo($ci,CURLINFO_HTTP_CODE);
        if ((strpos($content,'"status":"SUCCESS"')) and $ticketinfo === 200)
            return TRUE;
        else
            return FALSE;
    }
    /*
    * 封装数为为json数据类型
    * @param integer $code 状态码
    * @param string $message 状态信息
    * @param array $data 数据
    * return string 
    */
    public static function json_response($code, 
        $message='', $data=array()){
        $result = (new Response)->grant_array($code, $message, $data);
        echo json_encode($result);
        exit;
    }

    /*
    * 封装数为为xml数据类型
    * @param integer $code 状态码
    * @param string $message 状态信息
    * @param array $data 数据
    * return string 
    */
    public static function xml_response($code, 
        $message='', $data=array()){
        
        $result = (new Response)->grant_array($code, $message, $data);
        
        header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<Document>";
        $xml .= (new Response)->xml_encode($result);
        $xml .= "</Document>";
        echo $xml;
        exit();
    }

    /*
    * 将数组转换为XML格式
    * @param array $array 数组
    * return string 
    */
    private function xml_encode($array=array()){
        $xml = $attr = "";

        if(!empty($array)){
            foreach ($array as $key => $value) {
                if(is_numeric($key)){
                    $attr = " id='{$key}'";
                    $key = "item";
                }
                $xml .= "<{$key}{$attr}>" ;
                $xml .= is_array($value) ? self::xml_encode($value) : $value;
                $xml .="</{$key}>\n";
            }
        }
        return $xml;
    }

    /*
    * 按照接口格式生成原数据数组
    * @param integer $code 状态码
    * @param string $message 状态信息
    * @param array $data 数据
    * return array 
    */
    private function grant_array($code, $message='', $data=array()){
        if(!is_numeric($code)){
            return '';
        }

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );

        return $result;
    }
}