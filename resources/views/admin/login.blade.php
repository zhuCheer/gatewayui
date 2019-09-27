<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.html">

    <title>后台登陆</title>

    <!-- Bootstrap core CSS -->
    <link href="{{$adminStatic}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{$adminStatic}}/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{$adminStatic}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{$adminStatic}}/css/style.css" rel="stylesheet">
    <link href="{{$adminStatic}}/css/style-responsive.css" rel="stylesheet" />

    <style>
        #captcha{
            cursor: pointer;
        }
    </style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{$adminStatic}}/js/html5shiv.js"></script>
    <script src="{{$adminStatic}}/js/respond.min.js"></script>
    <![endif]-->
    <script src="{{$adminStatic}}/js/jquery-1.8.3.min.js"></script>
</head>

<body class="login-body">

<div class="container">

    <form class="form-signin" id="loginForm" action="{{route('login')}}">
        <h2 class="form-signin-heading">登录</h2>
        <div class="login-wrap">
            <input type="text" name="username" class="form-control" placeholder="用户名" autofocus>
            <input type="password" name="password" class="form-control" placeholder="密码">

            <div>
                <input type="text" style="width: 50%; float: left; margin-right: 20px;" name="captcha" class="form-control" placeholder="验证码">
                <img src="/captcha" style="height: 38px; float: left;" id="captcha">
            </div>


            <button class="btn btn-lg btn-login btn-block" id="submitBtn" type="submit">提交</button>
            <!-- <p>后台登陆</p> -->


        </div>

    </form>

</div>
@include('admin.baseJS')

<script>
    var isLogin = +'{{$isLogin}}';
    $(function(){

        if(isLogin){
            layer.msg("已经登录",{icon: 1,time:1000});
            QI.delayJump("{{route('admin.welcome')}}");
        }

        $('#captcha').click(function(){
            var _t = Date.parse(new Date());

            $(this).attr('src', '/captcha?_t='+_t);
        });

        $('#loginForm').submit(function(){
            var url  = $(this).attr('action')
            var params = $(this).serialize();
            if($("input[name='username']").val() == ''){
                QI.errorAlert('请填写用户名');
                return false;
            }
            if($("input[name='password']").val() == ''){
                QI.errorAlert('请填写密码');
                return false;
            }

            if($("input[name='captcha']").val() == ''){
                QI.errorAlert('请填写验证码');
                return false;
            }

            QI.ajaxForm(url, params, function(json){
                var _t = Date.parse(new Date());
                $('#captcha').attr('src', '/captcha?_t='+_t);

                QI.successAlert(json.info);
                QI.delayJump(json.data.url);

            });


            return false
        });

    });

</script>
</body>
</html>
