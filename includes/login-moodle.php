<?php 
	require_once("../include/my_func.inc.php");
    
	function check_login($user_id,$password){
		session_destroy();
		session_start();
		$moodle_host="127.0.0.1";
		$moodle_port="3306";
		$moodle_user="root";
		$moodle_db="moodle";
		$moodle_pass="";
		$moodle_conn=mysql_connect($moodle_host.":".$moodle_port,$moodle_user,$moodle_pass);
		$moodle_salt= '-Y9-h0;),c@<i)D~*i/j7.pD6lh/,B';
		$password=md5($password.$moodle_salt);
		$ret=false;
		$moodle_pre="mdl_";
		$sql="select password from ".$moodle_pre."user where username=?";
		if($moodle_conn){
			
			$result=pdo_query($sql,$user_id);
			$row=$result[0];
			if($row&&$password==$row[0]){
				$ret=$user_id;
			}
		}
		
		return $ret; 
	}
?>
