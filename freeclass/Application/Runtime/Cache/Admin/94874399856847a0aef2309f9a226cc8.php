<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>欢迎来到空课统计管理员界面</title>

    <link href="/freeclass/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/freeclass/Public/css/datepicker3.css" rel="stylesheet">
    <link href="/freeclass/Public/css/styles.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="/freeclass/Public/js/html5shiv.js"></script>
    <script src="/freeclass/Public/js/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">空课统计管理员界面</a>
            <ul class="user-menu">
                <li class="dropdown pull-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> User <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo U('Index/Logout');?>"><span class="glyphicon glyphicon-log-out"></span> 注销登陆</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- /.container-fluid -->
</nav>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <ul class="nav menu">
        <li class="active"><a href="<?php echo U('Index/Home');?>"><span class="glyphicon glyphicon-dashboard"></span> 管理主页</a></li>
        <li><a href="<?php echo U('User/index');?>"><span class="glyphicon glyphicon-stats"></span> 成员管理</a></li>
        <li><a href="<?php echo U('Group/index');?>"><span class="glyphicon glyphicon-stats"></span> 群组管理</a></li>
        <li><a href="<?php echo U('Course/index');?>"><span class="glyphicon glyphicon-stats"></span> 课表管理</a></li>
        <li><a href="<?php echo U('Record/index');?>"><span class="glyphicon glyphicon-stats"></span> 操作日志</a></li>
        <li><a href="<?php echo U('Setting/index');?>"><span class="glyphicon glyphicon-stats"></span> 系统设置</a></li>
    </ul>
</div><!--/.sidebar-->
<link rel="stylesheet" href="/freeclass/Public/self/css/Setting.css">
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active">系统设置</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">系统设置</h1>
        </div>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">系统参数</div>
                <div class="cont" id="cont">

                </div>
                <div class="panel-body">
                    <?php $__FOR_START_1458228524__=0;$__FOR_END_1458228524__=count($setting);for($i=$__FOR_START_1458228524__;$i < $__FOR_END_1458228524__;$i+=1){ ?><form role="form" action="<?php echo U('Setting/change');?>" method="post">
                            <div class="settinggroup">
                                <span>key</span>
                                <input class="settingcontroller" value="value">
                            </div>
                                <div class="settinggroup">
                                    <span><?php echo ($setting[$i]['key']); ?></span>
                                    <input class="settingcontroller" name="<?php echo ($setting[$i]['key']); ?>" placeholder="<?php echo ($setting[$i]['value']); ?>">
                                </div>
                            <span class="settingspan"><button class="btn btn-primary settingbtn" type="submit">确认修改</button></span>
                        </form><?php } ?>
                </div>
            </div>
        </div>
    </div><!--/.row-->
</div><!--/.main-->
<script src="/freeclass/Public/js/jquery-1.11.1.min.js"></script>
<script src="/freeclass/Public/js/bootstrap.min.js"></script>
<script src="/freeclass/Public/js/chart.min.js"></script>
<script src="/freeclass/Public/js/bootstrap-datepicker.js"></script>
<script src="/freeclass/Public/js/bootstrap-table.js"></script>
<script>
    $('#calendar').datepicker({
    });

    !function ($) {
        $(document).on("click","ul.nav li.parent > a > span.icon", function(){
            $(this).find('em:first').toggleClass("glyphicon-minus");
        });
        $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
    }(window.jQuery);

    $(window).on('resize', function () {
        if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
    })
    $(window).on('resize', function () {
        if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
    })
</script>

<script src="/freeclass/Public/self/js/MyGroup.js"></script>
</body>

</html>