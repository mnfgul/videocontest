<?php 
/*
This file contains all objects and functions related to YouTube interaction
*/

Class LCYoutube
{
	/*--------------------------------------private properties-------------------------------------*/
	private $username = 'lacostenausa@gmail.com';
    private $password = 'Yeoman!963';
    private $service = 'youtube';
    private $client = null;
    private $source = 'LC-Video'; // a short string identifying your application
    private $loginToken = null;
    private $loginCaptcha = null;
	private $authenticationURL = 'https://www.google.com/accounts/ClientLogin';
	
	private $developerKey = 'AI39si5xw7urgorf71dm5nZqNPnVK9RGrZTRc-U4ptDKbeEeooP5wP17uVEnU9daZCg83s4bTFloXygWt64x4oyLPYgJAkp_Cg';
	private $applicationId = 'LCVideo';
	private $clientId = 'LCVideo';
	
	private $uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/default/uploads';
	
	/*--------------------------------------public properties---------------------------------------*/
	public $yt = null;
	public $httpClient = null;
	
	/*---------------------------------------class contruct-----------------------------------------*/
	public function __construct()
	{
		Zend_Loader::loadClass('Zend_Gdata_YouTube');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Gdata_App_Exception');
		
		try
		{
			$this->httpClient = Zend_Gdata_ClientLogin::getHttpClient(
				$this->username, 
				$this->password,
				$this->service,
				$this->client,
				$this->source,
				$this->loginToken,
				$this->loginCaptcha,
				$this->authenticationURL);
				
			$this->yt = new Zend_Gdata_YouTube(
				$this->httpClient,
				$this->applicationId,
				$this->clientId,
				$this->developerKey);
		}
		catch(Zend_Gdata_App_Exception $e)
		{
			echo 'An unexpected error occured! Please contact site administrator.';
		}
	}
	
	/* --------------------------------------private methods------------------------------------------*/
	
	/*----------------------------------------public methods------------------------------------------*/
	
	public function UploadVideo($title, $desc, $source)
	{
		$path_info = pathinfo($source);
    	$ext = strtolower($path_info['extension']);
    	$contentTypes = array('wmv'=>'video/x-ms-wmv', 'mpeg'=>'video/mpeg','mpeg4'=>'video/mp4','mov'=>'video/quicktime','avi'=>'video/x-msvideo','flv'=>'video/x-flv');
    	$type = "video/x-ms-wmv";
    	
    	if(in_array($ext,$contentTypes))
    	{
    		$type = $contentTypes[$ext];
    	}
    	
		// create a new VideoEntry object
		$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();

		// create a new Zend_Gdata_App_MediaFileSource object
		$filesource = $this->yt->newMediaFileSource($source);
		$filesource->setContentType($type);

		// set slug header
		$filesource->setSlug(' ');
		
		//make video private
		$myVideoEntry->setVideoPrivate();
		
		// add the filesource to the video entry
		$myVideoEntry->setMediaSource($filesource);

		$myVideoEntry->setVideoTitle($title);
		$myVideoEntry->setVideoDescription($desc);
		// The category must be a valid YouTube category!
		$myVideoEntry->setVideoCategory('Howto');

		// Set keywords. Please note that this must be a comma-separated string
		// and that individual keywords cannot contain whitespace
		$myVideoEntry->SetVideoTags('la costena, Traditions Your Way');
		
		/*
		// set some developer tags -- this is optional
		// (see Searching by Developer Tags for more details)
		$myVideoEntry->setVideoDeveloperTags(array('mydevtag', 'anotherdevtag'));

		// set the video's location -- this is also optional
		$yt->registerPackage('Zend_Gdata_Geo');
		$yt->registerPackage('Zend_Gdata_Geo_Extension');
		$where = $yt->newGeoRssWhere();
		$position = $yt->newGmlPos('37.0 -122.0');
		$where->point = $yt->newGmlPoint($position);
		$myVideoEntry->setWhere($where);
		*/

		// try to upload the video, catching a Zend_Gdata_App_HttpException, 
		// if available, or just a regular Zend_Gdata_App_Exception otherwise
		try 
		{
			$newEntry = $this->yt->insertEntry($myVideoEntry, $this->uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
			return $newEntry;
		} 
		catch (Zend_Gdata_App_HttpException $httpException) 
		{
			echo 'An unexpected error occured! Please contact site administrator.';
			echo $httpException->getRawResponseBody();
		} 
		catch (Zend_Gdata_App_Exception $e) 
		{
			echo 'An unexpected error occured! Please contact site administrator.';
			echo $e->getMessage();
		}
	}
	
	public function UpdateVideoVisibility($videoEntry, $status)
	{
		$putUrl = $videoEntry->getEditLink()->getHref();
		if($status == 1)
		{
			$videoEntry->setVideoPublic();
		}
		else
		{
			$videoEntry->setVideoPrivate();
		}
		
		try 
		{
			$updatedEntry = $this->yt->updateEntry($videoEntry, $putUrl);
			return $updatedEntry;
		} 
		catch (Zend_Gdata_App_HttpException $httpException) 
		{
			echo $httpException->getRawResponseBody();
			return false;
		} 
		catch (Zend_Gdata_App_Exception $e) 
		{
			echo $e->getMessage();
			return false;
		}
			
	}
	
	
	public function DeleteVideo($videoEntry)
	{
		try 
		{
			$deleteVideo = $this->yt->delete($videoEntry);
			return true;
		} 
		catch (Zend_Gdata_App_HttpException $httpException) 
		{
			echo $httpException->getRawResponseBody();
			return false;
		} 
		catch (Zend_Gdata_App_Exception $e) 
		{
			echo $e->getMessage();
			return false;
		}
	}
	
	public function test()
	{
		$videoEntry = $this->yt->getVideoEntry('the0KZLEacs');
		
		echo $this->printVideoEntry($videoEntry);
	}
	

	
	public function printVideoEntry($videoEntry) 
	{
		  // the videoEntry object contains many helper functions
		  // that access the underlying mediaGroup object
		  echo 'Video: ' . $videoEntry->getVideoTitle() . "\n";
		  echo 'Video ID: ' . $videoEntry->getVideoId() . "\n";
		  echo 'Updated: ' . $videoEntry->getUpdated() . "\n";
		  echo 'Description: ' . $videoEntry->getVideoDescription() . "\n";
		  echo 'Category: ' . $videoEntry->getVideoCategory() . "\n";
		  echo 'Tags: ' . implode(", ", $videoEntry->getVideoTags()) . "\n";
		  echo 'Watch page: ' . $videoEntry->getVideoWatchPageUrl() . "\n";
		  echo 'Flash Player Url: ' . $videoEntry->getFlashPlayerUrl() . "\n";
		  echo 'Duration: ' . $videoEntry->getVideoDuration() . "\n";
		  echo 'View count: ' . $videoEntry->getVideoViewCount() . "\n";
		  echo 'Rating: ' . $videoEntry->getVideoRatingInfo() . "\n";
		  echo 'Geo Location: ' . $videoEntry->getVideoGeoLocation() . "\n";
		  echo 'Recorded on: ' . $videoEntry->getVideoRecorded() . "\n";
		  
		  // see the paragraph above this function for more information on the 
		  // 'mediaGroup' object. in the following code, we use the mediaGroup
		  // object directly to retrieve its 'Mobile RSTP link' child
		  foreach ($videoEntry->mediaGroup->content as $content) {
			if ($content->type === "video/3gpp") {
			  echo 'Mobile RTSP link: ' . $content->url . "\n";
			}
		  }
		  
		  echo "Thumbnails:\n";
		  $videoThumbnails = $videoEntry->getVideoThumbnails();

		  foreach($videoThumbnails as $videoThumbnail) {
			echo $videoThumbnail['time'] . ' - ' . $videoThumbnail['url'];
			echo ' height=' . $videoThumbnail['height'];
			echo ' width=' . $videoThumbnail['width'] . "\n";
		  }
	}
}


/*Videos Helper Class*/

Class VideoHelper
{
	public function __construct()
	{
		
	}
	
	public function videoThumbUrl($videoEntry)
	{
		$videoThumbnails = $videoEntry->getVideoThumbnails();
		if(isset($videoThumbnails[1]['url']))
		{
			$thumbUrl = $videoThumbnails[1]['url'];
		}
		else
		{
			$thumbUrl = $videoThumbnails[0]['url']; 
		}
		
		return $thumbUrl;
	}
	
}

?>