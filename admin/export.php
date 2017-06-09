<?php
//include necessary file
include_once '../include/bootstrap.php';

$lcvideo = new LcVideo();

//get q paramater
if(!empty($_GET['q']))
{
	if($_GET['q'] == 'all')
	{
		$lcvideo->Export();
	}
	else if($_GET['q'] == 'list')
	{
		$page = 1;
		if(!empty($_GET['page'])) $page = intval($_GET['page']);
		$lcvideo->Export($page);
	}
}
?>