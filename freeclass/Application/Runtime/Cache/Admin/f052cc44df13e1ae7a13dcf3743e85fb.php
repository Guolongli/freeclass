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

<link href="/freeclass/Public/css/bootstrap-table.css" rel="stylesheet">
<link href="/freeclass/Public/self/css/index.css" rel="stylesheet">

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active">我的课表</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <table data-toggle="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>星<br>期<br>一</th>
                        <th>星<br>期<br>二</th>
                        <th>星<br>期<br>三</th>
                        <th>星<br>期<br>四</th>
                        <th>星<br>期<br>五</th>
                        <th>星<br>期<br>六</th>
                        <th>星<br>期<br>日</th>
                    </tr>
                    </thead>
                    <?php $__FOR_START_1475717917__=0;$__FOR_END_1475717917__=5;for($i=$__FOR_START_1475717917__;$i < $__FOR_END_1475717917__;$i+=1){ ?><tr>
                            <td>
                                第<?php echo ($i+1); ?>节
                            </td>
                            <?php $__FOR_START_411130364__=0;$__FOR_END_411130364__=7;for($j=$__FOR_START_411130364__;$j < $__FOR_END_411130364__;$j+=1){ ?><td>
                                    <?php $__FOR_START_112520406__=0;$__FOR_END_112520406__=count($course[$i+1+5*$j]);for($k=$__FOR_START_112520406__;$k < $__FOR_END_112520406__;$k+=1){ echo ($course[$i+1+5*$j][$k]); ?>
                                        <br><?php } ?>
                                </td><?php } ?>
                        </tr><?php } ?>
                </table>
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

<script src="/freeclass/Public/self/js/index.js"></script>
</body>

</html>