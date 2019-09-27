<?php

namespace App\Http\Service;

use App\Exceptions\ApiException;

class ProxySrv
{
    private $urlPre;
    private $apis = [];
    public function __construct()
    {
        $this->urlPre = config('app.proxyServer.urlPre');
        $this->apis = config('app.proxyServer.apis');
    }

    /**
     * 获取接口url
     */
    private function getApiUrl($key){
        if(!empty($this->apis[$key])){
            return $this->urlPre.$this->apis[$key];
        }
        return false;
    }


    /**
     * 注册站点
     */
    public function registerSite($domain, $balance, $scheme = 'http'){
        $url = $this->getApiUrl('regist');
        $params = [
            'domain'=>$domain,
            'balance'=>$balance,
            'scheme'=>$scheme
        ];
        $ret = $this->curlFun($url, $params);
        $ret = json_decode($ret, true);
        if(isset($ret['error']) && $ret['error'] != 0){
            throw new ApiException('代理服务器报错，'. $ret['info']);
        }

        return true;
    }

    /**
     * 新增节点
     */
    public function addAddr($domain, $addr, $weight = 0){
        $url = $this->getApiUrl('insertone');
        $params = [
            'domain'=>$domain,
            'addr'=>$addr,
            'weight'=>$weight
        ];
        $ret = $this->curlFun($url, $params);
        $ret = json_decode($ret, true);
        if(isset($ret['error']) && $ret['error'] != 0){
            throw new ApiException('代理服务器报错，'. $ret['info']);
        }

        return true;
    }

    /**
     * 删除节点
     */
    public function removeAddr($domain, $addr){
        $url = $this->getApiUrl('removenoe');
        $params = [
            'domain'=>$domain,
            'addr'=>$addr,
        ];
        $ret = $this->curlFun($url, $params);
        $ret = json_decode($ret, true);
        if(isset($ret['error']) && $ret['error'] != 0){
            throw new ApiException('代理服务器报错，'. $ret['info']);
        }

        return true;
    }

    /**
     * 刷新站点
     */
    public function refreshSite($domain){
        $url = $this->getApiUrl('reloadone');
        $params = [
            'domain'=>$domain,
        ];
        $ret = $this->curlFun($url, $params);
        $ret = json_decode($ret, true);
        if(isset($ret['error']) && $ret['error'] != 0){
            throw new ApiException('代理服务器报错，'. $ret['info']);
        }

        return true;
    }

    /**
     * 清空站点
     */
    public function flushSite($domain){
        $url = $this->getApiUrl('flushone');
        $params = [
            'domain'=>$domain,
        ];
        $ret = $this->curlFun($url, $params);
        $ret = json_decode($ret, true);
        if(isset($ret['error']) && $ret['error'] != 0){
            throw new ApiException('代理服务器报错，'. $ret['info']);
        }

        return true;
    }


    /**
     * curl请求
     */
    private function curlFun($url, $postData=[], $timeout = 10){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);//需要抓取的页面路径
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
        ]);
        if(!empty($postData)){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));//post值
        }
        $fileContents = curl_exec($ch);//抓取的内容放在变量中
        curl_close($ch);

        return $fileContents;
    }




}