<?php

namespace iboxs\http\lib;

class Base
{
    use Post,Get;
    /**
     * @var string 请求地址
     */
    protected $url='';

    /**
     * @var string 请求数据类型
     */
    protected $type='params';

    /**
     * @var array 请求头
     */
    protected $header=[];

    public function __construct($url)
    {
        $this->url=$url;
    }

    public function formatData($result,$header){
        $type=trim(explode('/',explode(';',$header['Content-Type'])[0])[1]??'json');
        if($type=='json'){
            return json_decode($result,true);
        } else if($type=='xml'){
            $xml =simplexml_load_string($result); //xml转object
            $xml= json_encode($xml);  //objecct转json
            return json_decode($xml,true); //json转array
        }
        return $result;
    }

    public function formatHeader($header){
        $result=[];
        $arr=explode(PHP_EOL,$header);
        foreach ($arr as $item) {
            if($item==''){
                continue;
            }
            $zu=explode(':',$item);
            $key=$zu[0];
            $val=$zu[1]??'';
            $result[trim($key)]=trim($val);
        }
        return $result;
    }
}