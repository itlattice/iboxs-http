<?php
namespace iboxs\http\lib;

class CurlUploadFile
{   
    protected static $url;
    protected static $delimiter;
    protected static $instance;

    public function __construct($url) {
        //上传地址
        static::$url = $url;
        //分割符
        static::$delimiter = uniqid();
    }

    public function putFile($param) {
        $post_data = static::buildData($param);
        $curl = curl_init(static::$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: multipart/form-data; boundary=" . static::$delimiter,
            "Content-Length: " . strlen($post_data)
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $info = json_decode($response, true);
        return $info;
    }

    private static function buildData($param){
        $data = '';
        $eol = "\r\n";
        $upload = $param['file'];
        unset($param['file']);

        foreach ($param as $name => $content) {
            $data .= "--" . static::$delimiter . "\r\n"
                . 'Content-Disposition: form-data; name="' . $name . "\"\r\n\r\n"
                . $content . "\r\n";
        }
        // 拼接文件流
        $data .= "--" . static::$delimiter . $eol
            . 'Content-Disposition: form-data; name="file"; filename="' . $param['name'] . '"' . "\r\n"
            . 'Content-Type:application/octet-stream'."\r\n\r\n";

        $data .= $upload . "\r\n";
        $data .= "--" . static::$delimiter . "--\r\n";
        return $data;
    }

    public static function getInstance($url) {
        if(!static::$instance){
            static::$instance = new static($url);
        }
       return static::$instance;
    }

}