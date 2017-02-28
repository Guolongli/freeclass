<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>欢迎来到空课统计</title>

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
            <a class="navbar-brand" href="#">空课统计</a>
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
        <li class="active"><a href="<?php echo U('Index/index');?>"><span class="glyphicon glyphicon-dashboard"></span> 我的课表</a></li>
        <li><a href="<?php echo U('FreeClass/InputOption');?>"><span class="glyphicon glyphicon-stats"></span> 空课统计</a></li>
        <li class="parent ">
            <a data-toggle="collapse" href="#sub-item-1">
                <span class="glyphicon glyphicon-calendar"></span> 课表管理 <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="glyphicon glyphicon-s glyphicon-plus"></em></span>
            </a>
            <ul class="children collapse" id="sub-item-1">
                <li>
                    <a class="" href="<?php echo U('Course/AddCourse');?>">
                        <span class="glyphicon glyphicon-share-alt"></span> 添加课程
                    </a>
                </li>
                <li>
                    <a class="" href="<?php echo U('Course/ResetCourse');?>">
                        <span class="glyphicon glyphicon-share-alt"></span> 更新课程
                    </a>
                </li>
                <li>
                    <a class="" href="<?php echo U('Course/DeleteCourse');?>">
                        <span class="glyphicon glyphicon-share-alt"></span> 删除课程
                    </a>
                </li>
            </ul>
        </li>
        <li class="parent ">
            <a href="#sub-item-2" data-toggle="collapse">
                <span class="glyphicon glyphicon-th"></span> 群组管理 <span data-toggle="collapse" href="#sub-item-2" class="icon pull-right"><em class="glyphicon glyphicon-s glyphicon-plus"></em></span>
            </a>
            <ul class="children collapse" id="sub-item-2">
                <li>
                    <a class="" href="<?php echo U('Group/MyGroup');?>">
                        <span class="glyphicon glyphicon-share-alt"></span> 我的群组
                    </a>
                </li>
                <li>
                    <a class="" href="<?php echo U('Group/AddGroup');?>">
                        <span class="glyphicon glyphicon-share-alt"></span> 新建群组
                    </a>
                </li>
                <li>
                    <a class="" href="<?php echo U('Group/JoinGroup');?>">
                        <span class="glyphicon glyphicon-share-alt"></span> 加入群组
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div><!--/.sidebar-->
<link rel="stylesheet" href="/freeclass/Public/self/css/MyGroup.css">
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active">我的群组</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">我的群组</h1>
        </div>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">我的群组</div>
                <div class="cont" id="cont">

                </div>
                <div class="panel-body">
                    <table data-toggle="table" data-url="tables/data1.json"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
                        <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">群组名</th>
                            <th data-field="name"  data-sortable="true">群组口令</th>
                            <th data-field="price" data-sortable="true">群组操作</th>
                        </tr>
                        </thead>
                        <?php $__FOR_START_485534206__=0;$__FOR_END_485534206__=count($groups);for($i=$__FOR_START_485534206__;$i < $__FOR_END_485534206__;$i+=1){ ?><tr>
                                <td ><?php echo ($groups[$i]['name']); ?></td>
                                <td ><?php echo ($groups[$i]['word']); ?></td>
                                <td >
                                    <span onclick="Look('<?php echo U('Group/Mygroup',array('action'=>'Look','id'=>$groups[$i]['id']));?>');" data-toggle="modal" data-target="#myModal1">查看群成员</span>&nbsp;
                                    <?php if(($status[$i]['status']) != "2"): ?><a class="spana" href="<?php echo U('Group/Mygroup',array('action'=>'Quit','id'=>$groups[$i]['id']));?>"><span>退出群组</span></a>&nbsp;<?php endif; ?>
                                    <?php if(($status[$i]['status']) > "0"): ?><span onclick="DeleteMember('<?php echo U('Group/Mygroup',array('action'=>'DeleteMember','id'=>$groups[$i]['id']));?>','请选择要删除的成员','<?php echo U('Group/DeleteMember',array('delete'=>'member'));?>');" data-toggle="modal" data-target="#myModal2">删除成员</span>&nbsp;
                                            <?php if(($status[$i]['status']) == "2"): ?><span onclick="AddAdmin('<?php echo U('Group/Mygroup',array('action'=>'AddAdmin','id'=>$groups[$i]['id']));?>','请选择要添加的管理员','<?php echo U('Group/AddAdmin',array('add'=>'admin'));?>');" data-toggle="modal" data-target="#myModal2">添加管理员</span>&nbsp;
                                            <span onclick="DeleteAdmin('<?php echo U('Group/Mygroup',array('action'=>'DeleteAdmin','id'=>$groups[$i]['id']));?>','请选择要删除的管理员','<?php echo U('Group/DeleteAdmin',array('delete'=>'admin'));?>');" data-toggle="modal" data-target="#myModal2">删除管理员</span>&nbsp;
                                            <a href="<?php echo U('Group/Mygroup',array('action'=>'DeleteGroup','id'=>$groups[$i]['id']));?>"><span>解散群组</span></a><?php endif; endif; ?>
                                </td>
                            </tr><?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div><!--/.row-->
</div><!--/.main-->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">本群成员有：</h4>
            </div>
            <div class="modal-body" id="body1">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabe2"></h4>
            </div>
            <form id="form" method="post">
            <div class="modal-body" id="body2">


            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default">确认选择</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
            </div>
            </form>
        </div>
    </div>
</div>
    <script src="/freeclass/Public/js/jquery-1.11.1.min.js"></script>
<script src="/freeclass/Public/js/bootstrap.min.js"></script>
<script src="/freeclass/Public/js/chart.min.js"></script>
<script src="/freeclass/Public/js/chart-data.js"></script>
<script src="/freeclass/Public/js/easypiechart.js"></script>
<script src="/freeclass/Public/js/easypiechart-data.js"></script>
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