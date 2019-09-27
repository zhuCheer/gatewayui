@extends('admin/layout')




@section('content')
    <div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                站点信息
            </header>
            <div class="panel-body">
                <div class="bio-row">
                    <span>域名 :</span> {{$info->domain}}
                </div>
                <div class="bio-row">
                    <span>源站协议 ：</span>
                    <div class="btn-group">
                        <select class="selectScheme form-control changeSiteBtn" id="selectScheme">
                            <option value="http" @if($info->scheme == 'http') selected @endif>http</option>
                            <option value="https" @if($info->scheme == 'https') selected @endif>https</option>
                        </select>
                    </div>

                </div>

                <div class="bio-row">
                    <span>均衡算法 ：</span>
                    <div class="btn-group">
                        <select class="form-control changeSiteBtn" name="balance" id="selectBalance">
                            <option value="random" @if($info->balance == 'random') selected @endif>随机(randow)</option>
                            <option value="roundrobin" @if($info->balance == 'roundrobin') selected @endif>轮询(roundrobin)</option>
                            <option value="wroundrobin" @if($info->balance == 'wroundrobin') selected @endif>加权轮询(wroundrobin)</option>
                        </select>
                    </div>

                </div>
            </div>
        </section>
    </div>

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    节点管理
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-success btn nodeAdd">添加节点</button>
                    <a class="btn btn-primary btn" href="{{route('admin.siteList')}}"><i class="icon-arrow-left"></i>返回站点列表</a>
                </header>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>源站IP:PORT</th>
                            <th>权重</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($nodes as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->addr}}</td>
                            <td>{{$item->weight}}</td>
                            <td>{{$item->updated_at}}</td>
                            <td>
                                <button class="btn btn-danger btn-xs delBtn" data-id="{{$item->id}}" data-addr="{{$item->addr}}">
                                    <i class="icon-trash "></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('javascript')
<script  id="nodetpl" type="text/template">
    <form class="form-horizontal nodeForm" role="form" style="margin:15px 20px;">
        <div class="form-group">

            <label for="addr" class="col-lg-3 control-label">IP端口</label>
            <input type="hidden" value="{{$info->id}}" name="site_id">
            <div class="col-lg-9">
                <input type="text" class="form-control" id="addr" name="addr" placeholder="ip:port" >
            </div>
        </div>
        <div class="form-group">
            <label for="weight" class="col-lg-3 control-label">权重</label>
            <div class="col-lg-9">
                <input type="text" class="form-control" id="weight" name="weight" placeholder="权重" value="10">
                <p class="help-block">请填写域名 如：www.qiproxy.cn</p>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-success">确认</button>
            </div>
        </div>
    </form>
</script>

<script>
$(function(){

    $(".nodeAdd").click(function(){
        var gettpl = document.getElementById('nodetpl').innerHTML;
        var layHtml = juicer(gettpl, {info:{}});
        layer.open({
            type: 1,
            title:'添加节点',
            skin: 'layui-layer-rim', //加上边框
            area: ['380px', '250px'], //宽高
            content: layHtml
        });
    });

    $(".delBtn").click(function(){
        var url = '{{route("admin.delNode")}}';
        var id = $(this).data('id');
        var addr = $(this).data('addr');
        QI.confirmDo('是否删除节点['+addr+']', url, {id:id});

    });

    $(".changeSiteBtn").change(function(){
        var siteId = '{{$info->id}}';
        var scheme = $('#selectScheme').val();
        var balance = $('#selectBalance').val();


        var params = {id:siteId, scheme:scheme, balance:balance};
        console.log(params);
        var url = '{{route("admin.siteSave")}}';
        QI.ajaxForm(url, params, '');


    });

    $('body').on('submit','.nodeForm',function(){
        var params = $(this).serialize();
        var url = '{{route("admin.saveNode")}}';
        QI.ajaxForm(url, params, '');


        return false;
    });

});


</script>
@endsection