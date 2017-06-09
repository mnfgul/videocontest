<?php 
//function needed for all actions
include_once('include/bootstrap.php');

$youtube = new LCYoutube();
$helper = new Helper();

//get all pending videos from model
$lcvideo = new LCVideo();
$pendings = $lcvideo->GetVideos("ytstatus=0");			//the paramter is important as it is used in the query

//loop through pendings and upload them to youtube, if False then there is no pending or there is error
if($pendings !==false)
{
	foreach($pendings as $pending)
	{
		$id = $pending->id;
		$title = $pending->vtitle;
		$desc = $pending->vdesc;
		$path = VIDEO_FOLDER.$pending->vname;
		
		$ytdata = $youtube->UploadVideo($title, $desc, $path);
		$ytId = $ytdata->getVideoId();

		if(!empty($ytdata))
		{
			$result =1;
			
			$lcvideo->UpdatePendingVideo($id, $result, $ytId, $ytdata);
			
			$subject = 'LC Video App - New Video Submitted'; 
			$msg = '<p>New video has been submitted to the La Costena Video Application. Please visit admin section to manage the video.</p>';
			$msg .= '<p>-System Robot</p>';
			$helper->SendSystemEmail($subject, $msg, true);
		}
		else
		{
			$result = -1;
			$lcvideo->UpdatePendingVideo($id, $result, $ytId, $ytdata);
			
			$subject = 'LC Video App - YouTube Upload Error'; 
			$msg = '<p>New video has been submitted to the La Costena Video Application. However an error occured while uploading video to YouTube. Please check the details!</p>';
			$msg .= '<p>-System Robot</p>';
			$helper->SendSystemEmail($subject, $msg, false);
		}
	}
}

?>