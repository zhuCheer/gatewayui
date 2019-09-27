<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', '智能网关后台')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{$adminStatic}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{$adminStatic}}/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{$adminStatic}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{$adminStatic}}/css/style.css" rel="stylesheet">
    <link href="{{$adminStatic}}/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{$adminStatic}}/js/html5shiv.js"></script>
    <script src="{{$adminStatic}}/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<section id="container" class="">
    <!--header start-->
    <header class="header white-bg">
        <div class="sidebar-toggle-box">
            <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
        </div>
        <!--logo start-->
        <a href="#" class="logo" >智能网关<span>gateway</span></a>
        <!--logo end-->
        <div class="nav notify-row" id="top_menu">
            <div>&nbsp;</div>
        </div>
        <div class="top-nav ">
            <ul class="nav pull-right top-menu">

                <!-- user login dropdown start-->
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <img height="28" alt="" src="https://apic.douyucdn.cn/upload/avanew/face/201710/12/18/4c9baf2853f3286476c9e3b880d4f5a2_middle.jpg">
                        <span class="username">{{$loginInfo['username']}}</span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu extended logout">
                        <div class="log-arrow-up"></div>
                        <li><a href="{{route('logout')}}"><i class="icon-key"></i> 退出</a></li>
                    </ul>
                </li>
                <!-- user login dropdown end -->
            </ul>
        </div>
    </header>
    <!--header end-->
    <!--sidebar start-->
    <aside>
        <div id="sidebar"  class="nav-collapse ">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu">

                @foreach($siteMenus as $item)
                    <li class="@if(!empty($item['children'])) sub-menu @endif">
                        @if(!empty($item['children']))
                            <a class="" href="javascript:;">
                        @else
                            <a class="" href="{{$item['link']}}">
                        @endif

                            @if(!empty($item['icon']))
                                <i class="{{$item['icon']}}"></i>
                            @else
                                <i class="icon-circle"></i>
                            @endif

                            <span>{{$item['name']}}</span>

                         @if(!empty($item['children']))
                            <span class="arrow"></span>
                         @endif
                        </a>

                        @if(!empty($item['children']))
                        <ul class="sub">
                        @foreach($item['children'] as $node)
                        <li><a class="" href="{{$node['link']}}">{{$node['name']}}</a></li>
                        @endforeach
                        </ul>
                        @endif
                    </li>
                @endforeach

            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->


    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row" style="display: none;">
                <div class="col-lg-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb">
                        <li><a href="#"><i class="icon-home"></i> Home</a></li>
                        <li><a href="#">Library</a></li>
                        <li class="active">Data</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>


            @yield('content')


            <!-- page end-->
        </section>
    </section>
    <!--main content end-->
</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="{{$adminStatic}}/js/jquery.js"></script>
<script src="{{$adminStatic}}/js/bootstrap.min.js"></script>
<script src="{{$adminStatic}}/js/jquery.scrollTo.min.js"></script>
<script src="{{$adminStatic}}/js/jquery.nicescroll.js" type="text/javascript"></script>
<!--common script for all pages-->
<script src="{{$adminStatic}}/js/common-scripts.js"></script>
<script src="{{$siteStatic}}/js/juicer.js"></script>
<script>
juicer.set({
    'tag::operationOpen': '{&',
    'tag::operationClose': '}',
    'tag::interpolateOpen': '${',
    'tag::interpolateClose': '}',
    'tag::noneencodeOpen': '$${',
    'tag::noneencodeClose': '}',
    'tag::commentOpen': '{#',
    'tag::commentClose': '}'
});
</script>
@include('admin.baseJS')

@yield('javascript')

</body>
</html>