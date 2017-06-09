<?php
/*
As we set include_path before, the include path is 'include' folder
*/
//include required files
require_once '../include/bootstrap.php';

//initilize required classes

$youtube = new LCYoutube();
$lcvideo = new LCVideo();
$vhelper = new VideoHelper();

//check if the user is admin
$admin = new Admin();


if(!$admin->IsActive())
{
	header('Location: login.php?q=expired');
}
else
{
	//check for get params
	if(!empty($_GET['q'])){
		$q = $_GET['q'];
		switch($q)
		{
			case 'logout':
				{
					$admin -> LogOut();
					header('Location: ./login.php?q=logout');
				}
		}
	}
}
?>
<?php 
function head($title){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title;?> - La Costeña US - Video Application</title>
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/facebox.css">
	<link rel="stylesheet" type="text/css" href="css/visualize.css">
	<link rel="stylesheet" type="text/css" href="../css/prettyPhoto.css">	
	<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="css/ie.css"><![endif]-->
	
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery.prettyPhoto.js"></script>
	<script type="text/javascript" src="js/jquery.img.preload.js"></script>
	<script type="text/javascript" src="js/excanvas.js"></script>
	<script type="text/javascript" src="js/jquery.visualize.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
</head>
    <body>
        <div id="hld">
            <div class="wrapper">
                <div id="header">
                    <h1>
                    	<img src="images/logo.png"/>
                    </h1>
                    <ul id="nav">
                        <li class="<?php if($title == 'Dashboard') echo 'active';?>">
                            <a href="index.php">Dashboard</a>
                        </li>
                        <li class="<?php if($title == 'Videos') echo 'active';?>">
                            <a href="videos.php">Videos</a>
                        </li>
                        <li class="<?php if($title == 'Settings') echo 'active';?>" style="display: none;">
                            <a href="winners.php">Settings</a>
                        </li>
                    </ul>
                    <p class="user">
                        Hello, <i>admin</i>
                        | <a href="index.php?q=logout">Logout</a>
                    </p>
                </div>
                <div id="contentArea">
                <?php }
                function footer(){
                ?>
                </div>
                <div id="footer">
                    <p class="left">
                        &copy; Copyright 2010 <a href="#">La Costena U.S.</a>
                    </p>
                    <p class="right">
                        Powered by <a href="http://www.interblockstudios.com" target="_blank" title="Interblock Studios">Interblock Studios</a> v1.0
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
<?php }?>