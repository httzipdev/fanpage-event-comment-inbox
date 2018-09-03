<?php 
/* Shared on httzip.com
 * Code by fb.com/httzip
 */
header('Content-Type: text/html; charset=utf-8');
	include 'class.event.php';

	$event = new Event();
	
if(!empty($_GET['action'] == "get"))
{
	$event->access_token = "ACCESS-TOKEN";
	$event->post_id = "POST-ID";
	$list_answer = [
		"TEXT 1 ",
		"TEXT 2 ",
		"TEXT 3 ",
		"TEXT 4"];

	$event->list_answer = mb_convert_encoding($list_answer, 'UTF-8');
	$event->check();
}else{
	die("WTF");
}


 ?>