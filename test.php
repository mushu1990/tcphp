<?php
	include './libs/model/db.class.php';

	$db = new mysql(array("hostname"=>'127.0.0.1','username'=>'root','password'=>'root','database'=>'blog'));
	$db->connect();
	/*$flag = $db->excute("insert into blog_channel(ctitle,isshow) values ('1222','0')");
	echo $flag;
*/

	/*$result = $db->query("select * from blog_channel");
	var_dump($result);*/

	/*$result = $db->getTables('blog');
	var_dump($result);*/

	$flag = $db->replace(array('cid'=>2,'ctitle'=>'kj'),array('table'=>'blog_channel'));
	echo $flag;
?>