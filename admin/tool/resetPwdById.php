<?php
require_once("../../includes/config.inc.php");
//require_once("../../includes/check_post_key.php");
require_once ("../../includes/my_func.inc.php");

//对用户管理权限进行检测

if (!authUserManage()){
    $result = ['code' => 0, 'msg' => '重置失败！您没有权限！', 'url' => '/admin/userlist.php'];
    echo json_encode($result);
    return ;
}

$id = $_POST['id'];
$url = '/admin/userlist.php';
$password = "oj123456";
//密码加密
//var_dump($password);
$pwd=pwGen($password);
//var_dump($pwd);
$sql = "UPDATE users SET password='$pwd' WHERE user_id = '$id'";
//var_dump($sql);exit;
$result = pdo_query($sql);
if ($result){
    $res = ['code' => 1, 'msg' => '重置密码成功！', 'url' => $url];
}else{
    $res = ['code' => 0, 'msg' => '重置密码失败！请重试...', 'url' => $url];
}
echo json_encode($res);
?>