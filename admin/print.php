<?php
//include required files
require_once '../include/bootstrap.php';;

//sweeps class
$lcvideo = new LcVideo();
$helper = new Helper();

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
		$id = $_GET['id'];
		switch($q)
		{
			case 'user':{
				$user = $lcvideo->GetUser('user',$id);
			}break;
			case 'winner':{
				$user = $lcvideo->GetUser('winner',$id);
			}break;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Print Participant - La Costena Sweeps Application</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" media="all">
	<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="css/ie.css"><![endif]-->
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			window.print();
		});
	</script>
</head>
    <body style="padding: 10px;">
		<h2 style="text-align: center; padding-bottom: 10px;">La Costena Video Application</h2>
		<div class="block small" style="float: left;">			
			<div class="block_head">	
				<h2 style="text-align: center; display: block; float: none;">
					User Information
				</h2>
			</div>		
			<div class="block_content">
				<?php if($user == false){
					echo '<div class="message errormsg">';
					foreach($lcvideo->errors as $error)
					{
						echo $error.'<br/>';	
					}
					echo '</div>';
				}else{?>
				<table class="list" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="150"></td>
						<td width="250"></td>
					</tr>
					<tr>
						<td><b>First Name</b></td>
						<td><?php echo $user->fname;?></td>
					</tr>
					<tr>
						<td><b>Last Name</b></td>
						<td><?php echo $user->lname;?></td>
					</tr>
					<tr>
						<td><b>Address</b></td>
						<td><?php echo $user->address;?></td>
					</tr>
					<tr>
						<td><b>City</b></td>
						<td><?php echo $user->city;?></td>
					</tr>
					<tr>
						<td><b>State</b></td>
						<td><?php echo $user->state;?></td>
					</tr>
					<tr>
						<td><b>Zip Code</b></td>
						<td><?php echo $user->zip;?></td>
					</tr>
					<tr>
						<td><b>Email</b></td>
						<td><?php echo $user->email;?></td>
					</tr>
					<tr>
						<td><b>Phone</b></td>
						<td><?php echo $user->phone;?></td>
					</tr>
					<tr>
						<td><b>Age</b></td>
						<td><?php echo $helper->ConvertAge($user->age);?></td>
					</tr>					
					<tr>
						<td><b>Receive La Costena News & Promotions</b></td>
						<td><?php echo $helper->ConvertYesNo($user->ads);?></td>
					</tr>
					<tr>
						<td><b>Registration Date</b></td>
						<td><?php echo date("M d, Y", strtotime($user->regDate));?></td>
					</tr>
					<?php if($q == 'winner'){?>					
					<tr>
						<td><b>Win Date</b></td>
						<td><?php echo date("M d, Y", strtotime($user->genDate));?></td>
					</tr>
					<tr>
						<td><b>Generation Type</b></td>
						<td><?php echo $helper->ConvertGenType($user->genType);?></td>
					</tr>
					<?php }?>
				</table>
				<?php }?>					
			</div>
		</div>
		
		<div class="block small" style="float: left;">			
			<div class="block_head">	
				<h2 style="text-align: center; display: block; float: none;">
					Video Information
				</h2>
			</div>		
			<div class="block_content">
				<?php if($user == false){
					echo '<div class="message errormsg">';
					foreach($lcvideo->errors as $error)
					{
						echo $error.'<br/>';	
					}
					echo '</div>';
				}else{?>
				<table class="list" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="150"></td>
						<td width="250"></td>
					</tr>
					<tr>
						<td><b>Title</b></td>
						<td><?php echo $user->vtitle;?></td>
					</tr>
					<tr>
						<td><b>Description</b></td>
						<td><?php echo $user->vdesc;?></td>
					</tr>
					<tr>
						<td><b>YT Upload Status</b></td>
						<td>
							<?php 
								if($user->ytstatus == 1) echo 'Upload Finished';
								if($user->ytstatus == 0) echo 'Pending';
								if($user->ytstatus == -1) echo 'Error Occured!';
							?>
						</td>
					</tr>
					<tr>
						<td><b>Video Status</b></td>
						<td>
							<?php 
								if($user->status == 1) echo 'Approved';
								if($user->status == 0) echo 'Pending';
								if($user->status == -1) echo 'Disapproved';
							?>
						</td>
					</tr>
					
				</table>
				<?php }?>					
			</div>
		</div>
		
    </body>
</html>