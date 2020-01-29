<?php
    require_once('../includes/config.inc.php');
    require_once("../includes/my_func.inc.php");
    require_once("../includes/const.inc.php");

    isLogined();
    isAdministor();


/*分页数据*/
//获取当前页数
if (isset($_GET['page'])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}

//设置每页最多显示的记录数
$each_page = $PAGE_EACH / 2;

//计算页面的开始位置
if (!$page || $page == 1) {
    $start = 0;
} else {
    $offset = $page - 1;
    $start = ($offset * $each_page);
}

$wd = cleanParameter("get", "wd");

if (!$wd) {
    //无搜索内容
    //获取总页数
    $sql = "SELECT COUNT(1) FROM privilege WHERE rightstr IN ('administrator','normal_teacher','course_teacher','problem_manager','contest_manager', 'notice_manager')";
    $result = pdo_query($sql);
    $total_num = (int)$result[0][0];
    //var_dump($total_num);exit;
    $total_page = ceil($total_num / $each_page);
    //查询权限列表
    $sql = "SELECT * FROM privilege WHERE rightstr IN ('administrator','normal_teacher','course_teacher','problem_manager','contest_manager', 'notice_manager') ORDER BY user_id LIMIT $start, $each_page";
    $result = pdo_query($sql);
} else {
    //有搜索内容
    $sql = "SELECT COUNT(1) FROM privilege WHERE (user_id LIKE '%$wd%' OR rightstr LIKE '%$wd%') AND rightstr IN ('administrator','normal_teacher','course_teacher','problem_manager','contest_manager', 'notice_manager')";
    $result = pdo_query($sql);
    $total_num = (int)$result[0][0];
    $total_page = ceil($total_num / $each_page);
    //查询用户列表
    $sql = "SELECT * FROM privilege WHERE (user_id LIKE '%$wd%' OR rightstr LIKE '%$wd%') AND rightstr IN ('administrator','normal_teacher','course_teacher','problem_manager','contest_manager', 'notice_manager') ORDER BY user_id LIMIT $start, $each_page";
    $result = pdo_query($sql);
}

    /**
     * 几种种权限：管理员、题目添加者、比赛组织者、比赛参加者、代码查看者、手动判题
     * 注册默认为【普通用户】，目前只开放管理员和普通用户
     * 1.administrator：管理员（具有所有权限）
     * 2.problem_editor：问题编辑添加者
     * 3.contest_creator：比赛组织者
     * 4.source_browser:代码查看者
     * 5.primary：普通用户
     * 6.http_judge：手动判题（预留）
     * CreateTime:2019/7/15
    */

    /**
     * 权限已更新（2020/1/24）
     * 几种权限：
     *      1.普通老师：normal_teacher
     *      2.任课老师: course_teacher
     *      3.超级管理员: administrator
     *      4.问题管理员: problem_manager
     *      5.竞赛管理员: contest_manager
     *      6.公告管理员: notice_manager
     * CreateTime:2020/1/24
     */
    global $rightstr;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>用户管理 - AQNUOJ后台系统</title>
    <!-- ================= Favicon ================== -->
    <!-- Styles -->
    <link href="../static/libs/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="../static/libs/themify/themify-icons.css" rel="stylesheet">
    <link href="../static/libs/menubar/sidebar.css" rel="stylesheet">
    <link href="../static/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../static/libs/toastr/toastr.min.css"/>
    <link href="../static/libs/unix/unix.css" rel="stylesheet">
    <link href="../static/self/css/admin.css" rel="stylesheet">
    <style type="text/css">
        /* dataTables列内容居中 */
        .table>tbody>tr>td{
            text-align:center;
        }

        /* dataTables表头居中 */
        .table>thead:first-child>tr:first-child>th{
            text-align:center;
        }
    </style>
</head>


<body>

<!-- SideBar START -->
<?php include('partials/sidebar.php'); ?>
<!-- SideBar END -->

<!-- Header START -->
<?php include('partials/header.php'); ?>
<!-- Header END -->

<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">
                            <h1>权限管理</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">权限管理</a></li>
                                <li class="active">权限信息列表</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->
            <section id="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card alert">
                            <div class="card-header">
                                <h4>&nbsp;</h4>
                                <form action="/admin/userlist.php" method="get">
                                    <table>
                                        <tr>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-search"></i></span>
                                                    <input class="form-control" type="text" placeholder="输入搜索内容..."
                                                           name="wd">
                                                </div>
                                            </td>
                                            <td>
                                                <input class="btn btn-primary btn-sm" type="submit" value="搜索"/>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                            <div class="bootstrap-data-table-panel">
                                <div class="table-responsive">
                                    <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                        <thead align="center">
                                        <tr align="center">
                                            <th class="col-md-5">用户ID</th>
                                            <th class="col-md-5">权限</th>
                                            <th class="col-md-2">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody align="center">
                                        <?php
                                        foreach ($result as $key => $value){
                                            ?>
                                            <tr>
                                                <td><?php echo $value['user_id']; ?></td>
                                                <td>

                                                    <?php
                                                        foreach ($rightstr as $k => $v){
                                                            $right = $value['rightstr'];
                                                            if ($right == $v[0]){
                                                                if ($right == "normal_teacher")
                                                                    echo "<span class=\"btn btn-primary\">" . $v[1] . "</span>";
                                                                elseif ($right == "course_teacher")
                                                                    echo "<span class=\"btn btn-dark\">" . $v[1] . "</span>";
                                                                elseif ($right == "administrator")
                                                                    echo "<span class=\"btn btn-danger\">" . $v[1] . "</span>";
                                                                elseif ($right == "problem_manager")
                                                                    echo "<span class=\"btn btn-default\">" . $v[1] . "</span>";
                                                                elseif ($right == "contest_manager")
                                                                    echo "<span class=\"btn btn-info\">" . $v[1] . "</span>";
                                                                elseif ($right == "notice_manager")
                                                                    echo "<span class=\"btn btn-warning\">" . $v[1] . "</span>";
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                                </td>
<!--                                                <td>--><?php //echo $rows['rightstr']; ?><!--</td>-->
                                                <td>
                                                    <span><a onclick="removePrivilege(<?php echo $value['id'];?>,<?php echo $k;?>)"><i class="ti-trash color-danger"></i> </a></span>
                                                </td>
                                            </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                    <!-- 页码样式 -->
                                    <div class="col-sm-9">
                                        <div class="dataTables_paginate paging_simple_numbers" id="">
                                            <ul class="pagination">
                                                <?php
                                                echo pageLink($page, $total_num, $each_page, 9, "");
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- 跳转页面 -->
                                    <div class="col-sm-3 form-inline" style="padding-top: 2.5%">
                                        <div class="pull-right dataTables_paginate paging_simple_numbers">
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input id="gopage" class="form-control" type="number" placeholder="页码" name="page" value="">
                                                        </div>
                                                        <button class="btn btn-default btn-sm" type="submit" onclick="gotopage('gopage')">Go</button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <!-- Footer START -->
                <?php include('partials/footer.php'); ?>
                <!-- Footer END -->
            </section>
        </div>
    </div>
</div>

</div>

<!-- 信息删除确认 -->
<div class="modal fade" id="delcfmModel">
    <div class="modal-dialog">
        <div class="modal-content message_align">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <p>您确认要删除吗？</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="hiddenid"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a  onclick="submitRemovePrivilege()" class="btn btn-danger" data-dismiss="modal">确定</a>
            </div>
        </div>
    </div>
</div>

<script src="../static/libs/jquery/jquery.min.js"></script>
<!-- jquery vendor -->
<script src="../static/libs/jquery/jquery.nanoscroller.min.js"></script>
<!-- nano scroller -->
<script src="../static/libs/menubar/sidebar.js"></script>
<script src="../static/libs/preloader/pace.min.js"></script>
<!-- sidebar -->
<script src="../static/libs/bootstrap/js/bootstrap.min.js"></script>
<!-- bootstrap -->
<script src="../static/self/js/admin.js"></script>

<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>
<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/self/js/function.js"></script>

<!-- scripit init-->
</body>

</html>