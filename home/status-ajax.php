<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

////////////////////////////Common head
        $cache_time=2;
        $OJ_CACHE_SHARE=false;
	require_once('../includes/config.inc.php');
	require_once("../includes/my_func.inc.php");
        $view_title= "$MSG_STATUS";

	require_once "../includes/memcache.php";
	require_once "../includes/const.inc.php";

$solution_id=0;
// check the top arg

if (isset($_GET['solution_id'])){
        $solution_id=intval($_GET['solution_id']);
}
		$sql="select * from solution where solution_id=? LIMIT 1";
		$result = pdo_query($sql,$solution_id);
		

	if (count($result)>0){
		$row=$result[0];
		if(isset($_GET['tr'])&&isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
				$res=$row['result'];
			if($res==11){
				$sql="SELECT `error` FROM `compileinfo` WHERE `solution_id`=?";
			}else{
				$sql="SELECT `error` FROM `runtimeinfo` WHERE `solution_id`=?";
			}
			$result=pdo_query($sql,$solution_id);
			 $row=$result[0];
			if($row){
					echo htmlentities(str_replace("\n\r","\n",$row['error']),ENT_QUOTES,"UTF-8");
					$sql="delete from custominput where solution_id=?";
					pdo_query($sql,$solution_id);     
			}

		
			//echo $sql.$res;
		}else{
		    if(isset($_GET['q'])&&"user_id"==$_GET['q']){
			echo $row['user_id'];
		    }else{
			echo $row['result'].",".$row['memory'].",".$row['time'].",".$row['judger'].",".($row['pass_rate']*100);
		    }
		}
	}else{
		echo $solution_id;
		echo "0, 0, 0,unknown,0";
	}


?>
