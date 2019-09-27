@extends('admin/layout')



@section('content')
<div class="row">
<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
           <button class="btn btn-success btn" id="addSite">添加站点</button>
        </header>
        <table class="table table-striped table-advance table-hover">
            <thead>
            <tr>
                <th> ID</th>
                <th > 名称</th>
                <th> 域名</th>
                <th> 协议类型</th>
                <th> 均衡类型</th>
                <th>节点数量</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($list as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td >{{$item->name}}</td>
                <td>{{$item->domain}}</td>
                <td>{{$item->scheme}}</td>
                <td>{{$item->balance}}</td>
                <td>{{$item->nodeCount}}</td>
                <td>
                    <button class="btn btn-success btn-xs nodeBtn" data-id="{{$item->id}}">节点管理</button>
                    <button class="btn btn-primary btn-xs editBtn" data-id="{{$item->id}}"><i class="icon-pencil"></i>修改</button>
                    <button class="btn btn-danger btn-xs detBtn" data-id="{{$item->id}}" data-name="{{$item->name}}"><i class="icon-trash "></i>删除</button>
                </td>
            </tr>
            @endforeach


            </tbody>
        </table>

        <div class="row" style="margin-top: 20px; padding-bottom: 10px;">
            <div class="col-sm-6">
                <div class="dataTables_info" id="sample_1_info">共{{ $list->total() }}条记录</div>
            </div>
            <div class="col-sm-6">
                <div class="dataTables_paginate paging_bootstrap pagination">
                    {{ $list->links() }}

                </div>
            </div>



        </div>
    </section>
</div>
</div>
@endsection

@section('javascript')
<script  id="sitetpl" type="text/template">
    <form class="form-horizontal siteForm" role="form" style="margin:15px 20px;">
        <div class="form-group">
            {&if info.id > 0}
                <input type="hidden" value="${info.id}" name="id">
            {&/if}
            <label for="name" class="col-lg-3 control-label">名称</label>
            <div class="col-lg-9">
                <input type="text" class="form-control" id="name" name="name" placeholder="名称" value="${info.name}">
            </div>
        </div>
        <div class="form-group">
            <label for="domain" class="col-lg-3 control-label">域名</label>
            <div class="col-lg-9">
            <input type="text" class="form-control" id="domain" name="domain" placeholder="域名" value="${info.domain}">
            <p class="help-block">请填写域名 如：www.qiproxy.cn</p>
        </div>
        </div>
        <div class="form-group">
            <label for="scheme" class="col-lg-3 control-label">代理协议</label>
            <div class="col-lg-9">
                <select class="form-control" name="scheme" id="scheme">
                    <option value="http" {&if info.scheme == "http" } selected {&/if}>http</option>
                    <option value="https" {&if info.scheme == "https" } selected {&/if}>https</option>
                </select>
                <p class="help-block">代理目标url的协议类型</p>
            </div>
        </div>

        <div class="form-group">
            <label for="balance" class="col-lg-3 control-label">均衡类型</label>
            <div class="col-lg-9">
                <select class="form-control" name="balance" id="balance">
                    <option value="random" {&if info.balance == "random" } selected {&/if}>随机(randow)</option>
                    <option value="roundrobin" {&if info.balance == "roundrobin" } selected {&/if}>轮询(roundrobin)</option>
                    <option value="wroundrobin" {&if info.balance == "wroundrobin" } selected {&/if}>加权轮询(wroundrobin)</option>
                </select>
                <p class="help-block">代理目标url的协议类型</p>
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

    $('.nodeBtn').click(function(){
        var id = $(this).data('id');

        location.href='{{route("admin.nodes")}}?id='+id;
    });

    $("#addSite").click(function(){
        var gettpl = document.getElementById('sitetpl').innerHTML;
        var layHtml = juicer(gettpl, {info:{}});
        layer.open({
            type: 1,
            title:'添加站点',
            skin: 'layui-layer-rim', //加上边框
            area: ['380px', '420px'], //宽高
            content: layHtml
        });
    });
    $('.editBtn').click(function(){
        var url = '{{route("admin.siteInfo")}}';
        var id = $(this).data('id');


        QI.ajaxForm(url, {id:id}, function(json){
            var gettpl = document.getElementById('sitetpl').innerHTML;
            var layHtml = juicer(gettpl, {info:json.data});
            layer.open({
                type: 1,
                title:'编辑站点',
                skin: 'layui-layer-rim', //加上边框
                area: ['380px', '420px'], //宽高
                content: layHtml
            });

            console.log(json);
        });

    });


    $('.detBtn').click(function(){
        var url = '{{route("admin.siteRemove")}}';
        var id = $(this).data('id');
        var name = $(this).data('name');
        QI.confirmDo('是否删除站点['+name+']', url, {id:id});
    });

    $('body').on('submit','.siteForm',function(){
        var params = $(this).serialize();
        var url = '{{route("admin.siteSave")}}';
        QI.ajaxForm(url, params, '');


        return false;
    });
});
</script>
@endsection