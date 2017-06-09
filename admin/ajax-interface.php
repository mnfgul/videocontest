<?php
//set content-type to json with no cache
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

//include required files
require_once '../include/bootstrap.php';

$lcvideo = new LCVideo();
$youtube = new LCYoutube();
$helper = new Helper();

//check if the user is admin
$admin = new Admin();

if(!$admin->IsActive())
{
	echo 'not autorized';
}
else
{
	//check for get params
	$action = $_POST['action'];

	$response = array('result'=>0, 'msg'=>'');
	
	switch($action)
	{
		case 'change-status':
		{
			$id = $_POST['id'];
			$status = $_POST['status'];
			
			//check validty of information, then pass information
			if((($status == -1) || ($status == 1)) && is_numeric($id))
			{

				//get video information from database				
				$video = $lcvideo->GetVideos("id=$id");
				if($video !== false)
				{
					//update video status on YouTube
					$ytdata = unserialize($video[0]->ytdata);
					$updatedData = $youtube->UpdateVideoVisibility($ytdata,$status);
					if($updatedData !== false)
					{
						//if changes done on YouTube update database info too
						if($lcvideo->UpdateVideoStatus($id,$status,$updatedData))
						{
							$response['result'] = 1;
							$response['msg'] = 'Changes saved successfully';
							
							$subject = 'La Costena Video Result';
							
							if($status == 1)
							{
								$msg = '<p>Thank you for your submission to the La Costeña "Traditions Your Way" Contest. <b>Your submission has been accepted</b>. You may view your video at <a href="www.LaCostenaUSA.com">www.LaCostenaUSA.com</a>.</p>';
								$msg .= '<p><b>La Costeña "Traditions Your Way" Contest</b></p>'; 
							}
							else
							{
								$msg = '<p>Thank you for your submission to the La Costeña "Traditions Your Way" Contest. <b>Your submission was not approved</b>. Please review the requirements at <a href="www.LaCostenaUSA.com">www.LaCostenaUSA.com</a> and re-submit.</p>';
								$msg .= '<p><b>La Costeña "Traditions Your Way" Contest</b></p>';
							}
							
							$to = $video[0]->email;
							$helper->SendStatusEmail($to, $subject, $msg, true);
						}
						else
						{
							$response['result'] = -1;
							$response['msg'] = $lcvideo->errors[0];
						}
					}
					else
					{
						$response['result'] = -1;
						$response['msg'] = 'An error occurred while updating video data on YouTube please try again!';
					}
				}
				else
				{
					$response['result'] = -1;
					$response['msg'] = $lcvideo->errors[0];
				}
			}
			else
			{
				$response['result'] = -1;
				$response['msg'] = 'Invalid Paramaters';
			}
		}break;
		default:
		{
			$response['result'] = -1;
			$response['msg'] = 'Invalid Action';
		}	
	}	
	echo json_encode($response); 
}
?>