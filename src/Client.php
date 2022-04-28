<?php
/**
 * 网络抓取从这里开始
 * @author  zqu zqu1016@qq.com
 */
namespace iboxs\http;

use iboxs\http\lib\Base;

class Client extends Base
{
    /**
     * 设置请求头
     * @param string|array $header 请求头
     * @return $this
     */
    public function header($header): Client{
        if(is_array($header)){
            $this->header=array_merge($this->header,$header);
        } else {
            $this->header[]=$header;
        }
        return $this;
    }

    /**
     * @param string $type 请求数据类型
     * @return $this
     */
    public function type(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 发起Post请求
     * @param array $data 请求数据
     * @param bool $is_format 是否格式化（会自动将返回数据格式化为数组或直接返回请求数据）
     * @return array|string
     */
    public function post(array $data,bool $is_format=true,&$statusCode=200,&$header=null){
        list($result,$statusCode,$header)=$this->runPost($this,$data);
        if($result==false) {
            return false;
        }
        if($is_format){
            $result=$this->formatData($result,$header);
        }
        return $result;
    }

    /**
     * 发起Get请求
     * @param array $data 请求数据
     * @param bool $is_format 是否格式化（会自动将返回数据格式化为数组或直接返回请求数据）
     * @return array|string
     */
    public function get(array $data,bool $is_format=true,&$statusCode=200,&$header=null){
        list($result,$statusCode,$header)=$this->runGet($this,$data);
        if($result==false) {
            return false;
        }
        if($is_format){
            $result=$this->formatData($result,$header);
        }
        return $result;
    }

    /**
     * Post上传文件
     */
    public function postFile(array $data,$file,$file_key='file',bool $is_format=true,&$statusCode=200,&$header=null){
        $result=$this->runPostFile($this,$file,$data,$file_key);
        return $result;
    }
}