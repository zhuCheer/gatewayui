@extends('admin/layout')



@section('content')
<div class="row">
<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
           数据总览
        </header>
        <div class="panel-body">
            <div style="line-height: 30px;">
            欢迎使用智能网关平台。<br>

            网关服务状态：
            @if($pingStatus)
            <button class="btn btn-success btn-xs">正常</button>
            @else
                <button class="btn btn-danger btn-xs">异常 请检查proxy服务 {{config('app.proxyServer.urlPre')}} 确保服务连通性。</button>
            @endif

            <br>
            </div>
        </div>
    </section>
</div>
</div>
@endsection




@section('javascript')
<script>

</script>
@endsection