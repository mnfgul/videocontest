<?php
//include required files
include_once './template.php';

//note: all required classes are initilized at the template page

$lcvideo->datasetLimit=5;
$users = $lcvideo->GetUsers(null, 0,'id');

//get stats
$stats = $lcvideo->GetStats();

//print template header
echo head('Dashboard');
?>
			<div class="block">			
				<div class="block_head">	
					<h2>Participant Stats</h2>					
					<ul class="tabs">
						<li><a href="#days">This Week</a></li>
						<li><a href="#months">This Month</a></li>
					</ul>
				</div>		
				<div class="block_content tab_content" id="days">					
					<table class="stats" cellpadding="0" cellspacing="0" width="100%">					
						<thead>
							<tr>
								<td>&nbsp;</td>
								<?php for($i = 6 ; $i >= 0 ; $i-- ){?>
									<th scope="col"><?php echo date('D', strtotime("-$i day"));?></th>
								<?php }?>								
							</tr>
						</thead>						
						<tbody>
							<tr>
								<th scope="row">Participants</th>
								<td><?php echo $stats['day6'];?></td>
								<td><?php echo $stats['day5'];?></td>
								<td><?php echo $stats['day4'];?></td>
								<td><?php echo $stats['day3'];?></td>
								<td><?php echo $stats['day2'];?></td>
								<td><?php echo $stats['day1'];?></td>
								<td><?php echo $stats['day0'];?></td>
							</tr>
						</tbody>
					</table>								
				</div>
				
				<div class="block_content tab_content" id="months">					
					<table class="stats" cellpadding="0" cellspacing="0" width="100%">					
						<thead>
							<tr>
								<td>&nbsp;</td>
								<th scope="col"><?php echo date('M-d', strtotime("-30 days"));?></th>
								<th scope="col"><?php echo date('M-d', strtotime("-21 days"));?></th>
								<th scope="col"><?php echo date('M-d', strtotime("-14 days"));?></th>
								<th scope="col"><?php echo date('M-d', strtotime("-7"));?></th>
							</tr>
						</thead>						
						<tbody>
							<tr>
								<th scope="row">Participants</th>
								<td><?php echo $stats['mon3'];?></td>
								<td><?php echo $stats['mon2'];?></td>
								<td><?php echo $stats['mon1'];?></td>
								<td><?php echo $stats['mon0'];?></td>
							</tr>
						</tbody>
					</table>				
				</div>				
			</div>
			
			<div class="block">			
				<div class="block_head">
					<h2>Latest Submitted Videos</h2>					
					<ul>
						<li><a href="users.php">View All</a></li>
					</ul>
				</div>				
				<div class="block_content">
					<?php if(empty($users)){?>
					<div class="message errormsg">
						There is not any participant to list!
						<?php 
						//foreach($users->errors as $error){
							//echo $error.'<br/>';
						//}
						?>
					</div>
					<?php }
					else {?>
					<table cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px;">						
						<tr>
							<th width="20"></th>
							<th width="140">Video</th>
							<th>Details</th>
							<th></th>
							<th width="130">Status</th>
						</tr>
						<?php foreach($users as $user){?>
						<tr>
							<td style="padding: 0px;">
								<?php if($user->status == 0){?>
								<span class="newIco" id="newIco-<?php echo $user->id;?>"></span>
								<?php }?>
							</td>
							<td>
								<?php 
									$videoEntry = unserialize($user->ytdata);
									$thumbUrl = $vhelper->videoThumbUrl($videoEntry);
									$title = $videoEntry->getVideoTitle();
									$pageUrl = $videoEntry->getVideoWatchPageUrl(0);
								?>
								<a href="<?php echo $pageUrl?>" title="<?php echo $title;?>" rel="videos[latest]" target="_blank" class="videoThumb">
									<img src="<?php echo $thumbUrl;?>"/>
								</a>
							</td>
							<td>
								<p><b>Video Title: </b><?php echo $title;?></p>
								<p><b>Participant: </b><?php echo $user->fname.' '.$user->lname;?></p>
								<p><b>Email: </b><a href="mailto: <?php echo $user->email;?>"><?php echo $user->email;?></a></p>
							</td>
							<td>
								<p><b>Submit Date: </b><?php echo date("M d y", strtotime($user->regDate));?></p>
								<p><b>Phone: </b><?php echo $user->phone;?></p>
							</td>
							<td style="vertical-align: top; padding-top:10px;" class="actionCol">
								<div class="field switch clearfix" style="width: 160px;">
    								<label class="cb-enable <?php if($user->status == 1) echo 'selected'?>"><span>Approved</span></label>
    								<label class="cb-disable <?php if($user->status == -1) echo 'selected'?>"><span>Declined</span></label>
    								<input type="checkbox" class="changeStatus" id="status-<?php echo $user->id;?>" name="status-<?php echo $user->id;?>" value="<?php echo $user->id;?>" <?php if($user->status == 1) echo 'checked="checked"'?>/>    								
								</div>
								<?php if($user->status == 0){?>
								<div class="feedback pending">Status Pending...</div>
								<?php }?>								
							</td>
						</tr>
						<?php }?>							
					</table>
					<?php }?>								
				</div>				
			</div>
<?php 
//print template footer
echo footer();
?>