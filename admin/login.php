<?php
//include necessary files
require_once '../include/bootstrap.php';
$admin=new Admin();

//check for admin if already logged in no need to wait
if($admin->IsActive())
{
	header('Location: index.php');
}

//check for form submit and check username-password for admin
if(!empty($_POST)){
	$uname=$_POST['username'];
	$pass=$_POST['password'];	
	if($admin->IsAdmin($uname, $pass))
	{
		header('Location: index.php');
	}
	else
	{
		$notAdmin=true;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login - La Costeña Sweeps Application</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="css/ie.css"><![endif]-->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.img.preload.js"></script>
<script type="text/javascript" src="js/jquery.pngfix.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
</head>
    <body>
        <div id="hld">
            <div class="wrapper">
                <div class="block small center login">
                    <div class="block_head">
                        <h2>Login</h2>
                        <ul>
                            <li>
                                <a href="#">back to the site</a>
                            </li>
                        </ul>
                    </div>
                    <div class="block_content">
                    	<?php if($_GET['q']=='expired'){?>
                        <div class="message warning">
                            <p>
                                You aren't logged in or your session expired. Please re-login using your username and password!
                            </p>
                        </div>
                        <?php }?>
                        <?php if($_GET['q']=='logout'){?>
                        <div class="message success">
                            <p>
                                Your session has been ended successfully.
                            </p>
                        </div>
                        <?php }?>
                        <?php if($notAdmin){?>
                        <div class="message errormsg">
                            <p>
                                Your username and password isn't correct. Please try again. 
                            </p>
                        </div>
                        <?php }?>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                            <p>
                                <label>
                                    Username:
                                </label>
                                <br/>
                                <input type="text" class="text" value="" id="username" name="username"/>
                            </p>
                            <p>
                                <label>
                                    Password:
                                </label>
                                <br/>
                                <input type="password" class="text" value="" id="password" name="password"/>
                            </p>
                            <p>
                                <input type="submit" class="submit" value="Login" />                                
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>