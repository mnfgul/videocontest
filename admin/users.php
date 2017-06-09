<?php
//include required files
include_once './template.php';

//new sweeps class
$lcvideo = new LCVideo();
$helper =  new Helper();

$page = 1;
$deleteId = 0;
$deleteMsg = '';
if(!empty($_GET['page']))
{
	//need to add a valudation here
	$page = $_GET['page'];  
}
if(!empty($_GET['delete-user'])){
	//need validation here
	$deleteId = $_GET['delete-user'];
	if($lcvideo->DeleteUser($deleteId))
	{
		$deleteMsg = '<div class="message success">User has been deleted successfully.</div>';
	}
	else
	{
		$deleteMsg = '<div class="message errormsg">'.$lcvideo->errors['dbError'].'</div>';
	}
}

$users = $lcvideo->GetUsers(null,$page-1,'ID');

//print template header
echo head('Participants');
?>
	
			<div class="block">			
				<div class="block_head">
					<h2><img src="images/participants.png"/>&ensp;Sweeps Participants</h2>					
					<ul>
						<li><a href="export.php?q=all">Export All</a></li>
						<li><a href="export.php?q=list&page=<?php echo $page?>">Export Current List</a></li>
					</ul>
				</div>				
				<div class="block_content">
					<?php if(!empty($deleteMsg)) echo $deleteMsg;?>
					<?php if($users == false){?>
					<div class="message errormsg">
						<?php foreach($lcvideo->errors as $key => $value){
							echo $value.'<br/>';
							}
					?>
					</div>
					<?php
					}else {?>
					<table cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px;" class="list">						
						<tr>
							<th width="20"></th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Date</th>
							<th></th><th></th><th></th>
						</tr>
						<?php foreach($users as $user){?>
						<tr>
							<td></td>
							<td><?php echo $user->fname.' '.$user->lname;?></td>
							<td><a href="mailto: <?php echo $user->email;?>"><?php echo $user->email;?></a></td>
							<td><?php echo $user->phone;?></td>
							<td><?php echo date("M d, Y", strtotime($user->regDate));?></td>
							<td class="delete" width="15">
								<a id="<?php echo $user->id;?>" class="detailsLink" href="#" title="View User Details">
									<img src="images/profile.png" alt="View User Details"/>
								</a>
							</td>
							<td class="delete" width="15">
								<a class="printLink" href="print.php?q=user&id=<?php echo $user->ID;?>" title="Print Participant Details" target="_blank">
									<img src="images/print.png" alt="Print"/>
								</a>
							</td>
							<td class="delete" width="15">
								<a href="?page=<?php echo $page?>&delete-user=<?php echo $user->id;?>" title="Delete User" class="userDeleteLink">
									<img src="images/delete.png" alt="Delete User"/>
								</a>
							</td>
						</tr>
						<tr class="detailRow" id="detailRow-<?php echo $user->id;?>">
							<td colspan="8">
								<table>
									<tr>
										<td width="10">
										</td>
										<td width="400">
											<b>Name:</b> <?php echo $user->fname.' '.$user->lname;?><br/>
											<b>Address:</b> <?php echo $user->address?><br/>
											<b>City:</b> <?php echo $user->city;?><br/>
											<b>State:</b> <?php echo $user->state;?><br/>
											<b>Zip Code:</b> <?php echo $user->zip?><br/>
										</td>
										<td width="400">
											<b>Email:</b> <?php echo $user->email;?><br/>
											<b>Phone:</b> <?php echo $user->phone?><br/>
											<b>Age:</b> <?php echo $helper->ConvertAge($user->age);?><br/>
											<b>Gender:</b> <?php echo $helper->ConvertGender($user->gender);?><br/>
											<b>Registration Date:</b> <?php echo date("M d, Y", strtotime($user->regDate));?><br/>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<?php }?>							
					</table>
					<div class="pagination clearfix">
						<?php
							$count = $lcvideo->GetPagination();
							for($i=1; $i <= $count ; $i++){?>
							<a href="?page=<?php echo $i;?>" class="<?php if($i == $page) echo 'active'?>"><?php echo $i?></a>&ensp;
						<?php }?>
					</div>	
					<?php }?>								
				</div>				
			</div>
<?php 
//print template footer
echo footer();
?>