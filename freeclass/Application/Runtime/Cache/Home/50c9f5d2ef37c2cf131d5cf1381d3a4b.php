<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>空课统计登录</title>

    <link href="/freeclass/Public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/freeclass/Public/css/datepicker3.css" rel="stylesheet">
    <link href="/freeclass/Public/css/styles.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="/freeclass/Public/js/html5shiv.js"></script>
    <script src="/freeclass/Public/js/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div class="main">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading" align="center">欢迎来到空课统计</div>
            <div class="panel-body">
                <form role="form" action="<?php echo U('index/index');?>" method="post">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="请输入教务网账号" name="user" type="text" style="text-align: center">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="请输入教务网密码" name="pwd" type="password" style="text-align: center">
                        </div>
                        <input class="form-control" type="submit" style="text-align: center;background-color: dodgerblue;color: white" value="登录">
                    </fieldset>
                </form>
            </div>
        </div>
    </div><!-- /.col-->
</div><!-- /.main -->



<script src="/freeclass/Public/js/jquery-1.11.1.min.js"></script>
<script src="/freeclass/Public/js/bootstrap.min.js"></script>
<script src="/freeclass/Public/js/chart.min.js"></script>
<script src="/freeclass/Public/js/chart-data.js"></script>
<script src="/freeclass/Public/js/easypiechart.js"></script>
<script src="/freeclass/Public/js/easypiechart-data.js"></script>
<script src="/freeclass/Public/js/bootstrap-datepicker.js"></script>
<script>
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
</body>

</html>