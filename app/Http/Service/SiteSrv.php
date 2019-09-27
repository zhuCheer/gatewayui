<?php

namespace App\Http\Service;

use App\Exceptions\ApiException;
use DB;

class SiteSrv
{
    public function __construct()
    {
    }


    /**
     * 查询站点逻辑
     */
    public function findSite($id){
        $info = DB::table('qi_sites')->where('id', $id)->first();

        if(empty($info)){
            throw new ApiException('数据不存在，刷新后重试！');
        }
        return $info;
    }

    /**
     * 查询节点数据
     */
    public function getAllNodesBySiteId($id){
        $datas = DB::table('qi_nodes')->where('site_id', $id)->get();

        return $datas;
    }

    /**
     * 删除站点逻辑
     */
    public function removeSite($id){
        $info = DB::table('qi_sites')->where('id', $id)->first();

        if(empty($info)){
            throw new ApiException('数据不存在，刷新后重试！');
        }
        DB::beginTransaction(); //开启事务
        try{
            DB::table('qi_nodes')->where('site_id', $id)->delete();
            $ret = DB::table('qi_sites')->where('id', $id)->delete();

            $proxySrv = new ProxySrv();
            $proxySrv->flushSite($info->domain);
            DB::commit();
            return $ret;
        }catch (\Exception $e){
            DB::rollback();
        }


        return false;
    }

    /**
     * 节点删除
     */
    public function removeNode($id){
        $info = DB::table('qi_nodes')->where('id', $id)->first();
        if(empty($info)){
            throw new ApiException('数据不存在，刷新后重试！');
        }
        $siteInfo = $this->findSite($info->site_id);
        if(empty($info)){
            throw new ApiException('站点数据不存在，刷新后重试！');
        }

        DB::beginTransaction(); //开启事务
        $ret = DB::table('qi_nodes')->where('id', $id)->delete();
        $proxySrv = new ProxySrv();

        try{
            $proxySrv->removeAddr($siteInfo->domain, $info->addr);
        }catch (ApiException $e){
            DB::rollback();
            throw new ApiException($e->getMessage());
        }
        DB::commit();
        return $ret;
    }

    /**
     * 存储站点逻辑
     */
    public function saveSite($params){
        $proxySrv = new ProxySrv();

        DB::beginTransaction(); //开启事务
        if(empty($params['id'])){
            $exists = DB::table('qi_sites')->where('domain', $params['domain'])->count();
            if($exists){
                throw new ApiException('站点域名已存在！');
            }

            $domain = $params['domain'];
            $saveData = [
                'name' => $params['name'],
                'domain' => $params['domain'],
                'scheme' => $params['scheme'],
                'balance' => $params['balance'],
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // insert
            $ret = DB::table('qi_sites')->insert($saveData);

            if($ret){
                try{
                    $proxySrv->registerSite($domain, $params['balance'], $params['scheme']);
                }catch (ApiException $e){
                    DB::rollback();
                    throw new ApiException($e->getMessage());
                }
                DB::commit();

            }else{
                DB::rollback();
            }

        }else{

            if(!empty($params['domain'])){
                $exists = DB::table('qi_sites')->where('id','<>', $params['id'])->where('domain', $params['domain'])->count();
                if($exists){
                    throw new ApiException('站点域名已存在！');
                }
            }

            $info = $this->findSite($params['id']);
            if(empty($info)){
                throw new ApiException('数据不存在！');
            }
            $domain = $info->domain;
            $saveData = [];
            isset($params['name']) && $saveData['name'] = $params['name'];
            isset($params['domain']) && $saveData['domain'] = $params['domain'];
            isset($params['scheme']) && $saveData['scheme'] = $params['scheme'];
            isset($params['balance']) && $saveData['balance'] = $params['balance'];
            $saveData['updated_at'] = date('Y-m-d H:i:s');

            // update
            $ret = DB::table('qi_sites')->where('id', $params['id'])->update($saveData);
            DB::commit();
            if($ret){
                // 必须先让db存储好再调刷新接口，否则刷新不生效
                try{
                    $proxySrv->refreshSite($domain);
                }catch (ApiException $e){
                    throw new ApiException($e->getMessage());
                }
            }
        }

        return $ret;
    }

    /**
     * 节点存储
     */
    public function saveNode($params){
        $saveData = [
            'site_id' => $params['site_id'],
            'addr' => $params['addr'],
            'weight' => $params['weight'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $siteInfo = $this->findSite($params['site_id']);
        if(empty($siteInfo)){
            throw new ApiException('站点不存在！');
        }

        $exists = DB::table('qi_nodes')->where('site_id', $params['site_id'])->where('addr', $params['addr'])->count();
        if($exists){
            throw new ApiException('节点已存在！');
        }

        DB::beginTransaction(); //开启事务
        if(empty($params['id'])){
            // insert
            $saveData['created_at'] = date('Y-m-d H:i:s');
            $ret = DB::table('qi_nodes')->insert($saveData);

        }else{

            // update
            $ret = DB::table('qi_nodes')->where('id', $params['id'])->update($saveData);
        }
        $proxySrv = new ProxySrv();

        try{
            $proxySrv->addAddr($siteInfo->domain, $params['addr'], $params['weight']);
        }catch (ApiException $e){
            DB::rollback();
            throw new ApiException($e->getMessage());
        }

        DB::commit();

        return $ret;
    }

}