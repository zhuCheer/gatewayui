<?php

namespace App\Http\Controllers;
use App\Exceptions\ApiException;
use App\Http\Service\ProxySrv;
use App\Http\Service\SiteSrv;
use Illuminate\Http\Request;
use DB;

class Admin extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 站点管理
     */
    public function siteList()
    {

        $list = DB::table('qi_sites')->paginate();
        foreach ($list as $key=>$item){
            $nodeCount = DB::table('qi_nodes')->where('site_id', $item->id)->count();
            $list[$key]->nodeCount = $nodeCount;
        }

        $viewData = [
            'list'=>$list
        ];

        return view('admin.siteList', $viewData);

    }

    /**
     * 站点查询
     */
    public function siteInfo(Request $request){
        $params = $request->toArray();
        if(empty($params['id'])){
            return $this->ajaxReturn(1, '参数不完整', '');
        }
        $siteSrv = new SiteSrv();
        $ret = $siteSrv->findSite($params['id']);

        if($ret){
            return $this->ajaxReturn(0, '成功', $ret);
        }
        return $this->ajaxReturn(1, '失败', '');
    }


    /**
     * 存储站点
     */
    public function siteSave(Request $request){
        $params = $request->toArray();


        if(empty($params['id']) && (empty($params['domain']) || empty($params['scheme'])) ){
            return $this->ajaxReturn(1, '参数不完整', '');
        }
        $siteSrv = new SiteSrv();

        $ret = $siteSrv->saveSite($params);
        if($ret){
            return $this->ajaxReturn(0, '成功', '');
        }
        return $this->ajaxReturn(1, '失败', '');
    }


    /**
     * 站点删除
     */
    public function siteRemove(Request $request){
        $params = $request->toArray();
        if(empty($params['id'])){
            return $this->ajaxReturn(1, '参数不完整', '');
        }
        $siteSrv = new SiteSrv();
        $ret = $siteSrv->removeSite($params['id']);

        if($ret){
            return $this->ajaxReturn(0, '成功', '');
        }
        return $this->ajaxReturn(1, '失败', '');

    }


    /**
     * 节点管理
     */
    public function setNodes(Request $request)
    {
        $params = $request->toArray();
        if(empty($params['id'])){
            throw new ApiException("id参数缺失");
        }
        $siteSrv = new SiteSrv();
        $info = $siteSrv->findSite($params['id']);

        $nodes = $siteSrv->getAllNodesBySiteId($params['id']);

        $viewData = [
            'info'=>$info,
            'nodes'=>$nodes,
        ];
        return view('admin.setNodes', $viewData);
    }

    /**
     * 节点存储
     */
    public function saveNode(Request $request){
        $params = $request->toArray();
        if(empty($params['addr']) || empty($params['site_id']) || !isset($params['weight'])){
            return $this->ajaxReturn(1, '参数不完整', '');
        }
        $siteSrv = new SiteSrv();

        $ret = $siteSrv->saveNode($params);

        if($ret){
            return $this->ajaxReturn(0, '成功', '');
        }
        return $this->ajaxReturn(1, '失败', '');
    }

    /**
     * 删除节点
     */
    public function delNode(Request $request){
        $params = $request->toArray();
        if(empty($params['id'])){
            return $this->ajaxReturn(1, '参数不完整', '');
        }
        $siteSrv = new SiteSrv();
        $ret = $siteSrv->removeNode($params['id']);

        if($ret){
            return $this->ajaxReturn(0, '成功', '');
        }
        return $this->ajaxReturn(1, '失败', '');
    }
}