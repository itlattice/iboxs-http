<?php

namespace iboxs\http\lib;

trait Get
{
    public function runGet($client,$data){
        $curl = curl_init();
        //设置提交的url
        $getFields = http_build_query($data);
        curl_setopt($curl, CURLOPT_URL, $client->url."?".$getFields);
        // 这里是头部信息
        $header=$this->getHeader($client);
//        var_dump($header);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
        //设置获取的信息以文件流的形式返回，而不是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //忽略证书（关闭https验证）
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //在尝试连接时等待的秒数
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        //设置超时时间，最大执行时间超时时间（单位:s）
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        //是否返回请求头信息（http协议头）
        //追踪句柄的请求字符串（允许查看请求header）
        curl_setopt($curl, CURLINFO_HEADER_OUT, 0);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        //尝试连接
        if (curl_exec($curl) === false) {
            return array(-1,curl_error($curl),false);
        }
        //执行命令
        $result = curl_exec($curl);
        //去空格
        $result = trim($result);
        //转换字符编码
        $result = mb_convert_encoding($result, 'utf-8', 'UTF-8,GBK,GB2312,BIG5');
        //解决返回的json字符串中返回了BOM头的不可见字符（某些编辑器默认会加上BOM头）
        $result = trim($result,chr(239).chr(187).chr(191));
        //获取状态码
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $returnHeader='';
        if ($httpCode == '200') {
            $returnHeaderSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $returnHeader = substr($result, 0, $returnHeaderSize);
        }
        $result=str_replace($returnHeader,'',$result);
        $result=trim($result);
        $returnHeader = $this->formatHeader($returnHeader);
        //关闭URL请求
        curl_close($curl);
        //是否返回状态码
        return array($result,$httpCode,$returnHeader);
    }
}