<?php
//include required files
require_once('ez_sql_core.php');
require_once('ez_sql_mysql.php');
require_once('securimage.php');

/*
 * General Sweeps Class for user and winner operations
 */ 
Class LCVideo{
	
	//class private properties
	private $DBName='lcvideo';
	private $DBUser='lcvideo';
	private $DBPassword='lcvideo!963';
	private $DBHost='db.menafgul.com';

	private $db;
	
	//class public properties
	public $userData;
	public $errors;
	
	public $datasetLimit;
	
	//construct
	public function __construct()
	{
		$this->userData = array();
		$this->errors = array();
		
		$this->datasetLimit = 10;
	}
	
	//internal methods
	
	/*
	 * Connect to db and initilize db variable, for general use
	 */
	private function connectDb()
	{
		if(!isset($this->db))
		{
			$this->db=new ezSQL_mysql($this->DBUser, $this->DBPassword, $this->DBName, $this->DBHost);			
		}
	}
	
	//public methods
	public function CreateUser()
	{
		if($this->userData['news'] != '1')
		{
			$this->userData['news'] = '0';
		}
		
		$this->connectDb();
		$this->userData['phone']=$this->userData['phone1'].'-'.$this->userData['phone2'].'-'.$this->userData['phone3'];
		$query="INSERT INTO users(fname, lname, address, city, state, zip, email, phone, age, ads, vtitle, vdesc, vname, regDate) 
		VALUES('".
		$this->db->escape($this->userData['fName'])."','". 
		$this->db->escape($this->userData['lName'])."','".
		$this->db->escape($this->userData['address'])."','".
		$this->db->escape($this->userData['city'])."','".
		$this->db->escape($this->userData['state'])."','".
		$this->db->escape($this->userData['zip'])."','".
		$this->db->escape($this->userData['email'])."','".
		$this->db->escape($this->userData['phone'])."','".
		$this->db->escape($this->userData['age'])."','".
		$this->db->escape($this->userData['news'])."','".
		$this->db->escape($this->userData['vtitle'])."','".
		$this->db->escape($this->userData['vdesc'])."','".
		$this->db->escape($this->userData['vname'])."', NULL)";
		
		$this->db->query($query);
       	if(!empty($this->db->captured_errors))
       	{
       		$this->errors['dbError'] = 'The system could not save your information. Please contact to site administrator!';
       		return false;
       	}
       	{
       		$userId = $this->db->insert_id;
       		//possibly send confirmation email
       		
			//trigger the uploading video to youtube
			exec("php -f ".BASE_PATH."/trigger-upload.php > /dev/null &",$output,$return);
       		
			return true;	
       	}
	}
	
	public function GetVideos($filter)
	{
		// connect to db
		$this->connectDb();		
		$query = "SELECT * FROM users WHERE ".$this->db->escape($filter);	
		$videos = $this->db->get_results($query);
		if(!empty($this->db->captured_errors))
       	{
       		$this->errors['dbError'] = 'An error has been occured while getting video(s) information from database.<br/>
       		Error: '.$this->db->last_error;
       		return false;
       	}
       	{
       		if(empty($videos)){
       			$this->errors[] = 'There is no videos to show!';
       			return false;
       		}
    
       		//return all pending videos list
       		return $videos;	
       	}	
	}
	
	public function UpdatePendingVideo($id, $result, $ytid, $ytdata)
	{
			
		//connect to db
		$this->connectDb();
		
		//important to not break the mysql query	
		$cleanData = mysql_real_escape_string(serialize($ytdata));
		
		//update pending video
		$query = "UPDATE users SET ytstatus=$result, ytid='$ytid', ytdata='$cleanData' WHERE id=$id";

		if(!$this->db->query($query))
		{
			$this->errors[] = 'An error occurred updating winner information.<br/>Error: '.$this->db->last_error;
			return false;
		}
	}
	
	public function UpdateVideoStatus($id, $status, $ytdata)
	{
		//connect to db
		$this->connectDb();
		
		//important to not break the mysql query	
		$cleanData = mysql_real_escape_string(serialize($ytdata));
		
		$nowDate = date('Y-m-d H:i:s', time());
		
		//update pending video
		$query = "UPDATE users SET status=".$this->db->escape($status).", ytdata='$cleanData', approveDate='$nowDate' WHERE id=".$this->db->escape($id);
		
		if(!$this->db->query($query))
		{
			$this->errors[] = 'An error occurred updating video information.<br/>Error: '.$this->db->last_error;
			return false;
		}
		return true;
	}
	
	public function GetUsers($filter, $offset=0, $order='id')
	{
		$where="";
		if(!empty($filter))
		{
			$where="WHERE ";
			foreach($filter as $key => $value)
			{
				$where.="($key = $value)";
			}
		}
		$offset = $offset * $this->datasetLimit;
		$query="SELECT * FROM users $where  ORDER BY $order DESC LIMIT $offset, $this->datasetLimit";
		
		// connect to db
		$this->connectDb();
		$users = $this->db->get_results($query);
		if(!empty($this->db->captured_errors))
       	{
       		$this->errors['dbError'] = 'An error has been occured while getting latest participants.<br/>
       		Error: '.$this->db->last_error;
       		return false;
       	}
       	{
       		if(empty($users)){
       			$this->errors[] = 'No information is available for this page!';
       			return false;
       		}
    
       		//possibly send confirmation email
       		return $users;	
       	}
	}
	
	public function GetUser($type,$id)
	{
		if($type == 'winner'){
			$query = "SELECT * FROM winners INNER JOIN users ON winners.userID=users.ID WHERE winners.userID=$id";
		}else{
			$query = "SELECT * FROM users WHERE ID=$id";
		}
		$this->connectDb();
		$user = $this->db->get_row($query);
		if(!empty($this->db->captured_errors))
		{
			$this->errors[] = 'An error occurred while getting user information.<br/>Error: '.$this->db->last_error;
			return false;
		}
		else
		{
			return $user;
		}
	}
	
	public function DeleteUser($id)
	{
		$query = "DELETE FROM users WHERE id=$id";
		$this->connectDb();
		$result = $this->db->query($query);
		if(($EZSQL_ERROR) || ($result == false))
       	{
       		$this->errors['dbError'] = 'Could not delete the user. Please try again!<br/> Error:'.$EZSQL_ERROR;
       		return false;
       	}
       	{
       		return true;	
       	} 
	}

	public function GetStats()
	{
		$lDate = date("Y-m-d H:i:s", strtotime("-1 months"));
		$query = "SELECT regDate FROM users WHERE regDate >= '$lDate'";
		
		$this->connectDb();
		$stats = $this->db->get_results($query);
		
		$result = array(0);		
		$now = date("Y-m-d H:i:s", time());	//datetime class to get difference
		foreach($stats as $stat)
		{
			$diff =  floor((time() - strtotime($stat->regDate))/ 86400);
			
			//this is removed as diff function only supported in php >= 5.3.2
			//$diff = $now->diff(new DateTime($stat->regDate))->d;//difference between today and registration date
			
			//compare and add to necessary place
			//this week's results
			$result['day0'] += $diff == 0 ? 1: 0; //today
			$result['day1'] += $diff == 1 ? 1: 0; //yesterday
			$result['day2'] += $diff == 2 ? 1: 0; //2 days before
			$result['day3'] += $diff == 3 ? 1: 0; //3 days before
			$result['day4'] += $diff == 4 ? 1: 0; //4 days before
			$result['day5'] += $diff == 5 ? 1: 0; //5 days before
			$result['day6'] += $diff == 6 ? 1: 0; //week before
			
			//this months's results
			$result['mon0'] += ($diff >= 0 && $diff < 7 ) ? 1: 0; //part 0 of month
			$result['mon1'] += ($diff >= 7 && $diff < 14 ) ? 1: 0; //part 1 of month
			$result['mon2'] += ($diff >= 14 && $diff < 21 ) ? 1: 0; //part 2 of month
			$result['mon3'] += ($diff >= 21 && $diff < 30 ) ? 1: 0; //part 3 of month
		}
		return $result;
	}
	
	public function GetPagination()
	{		
		// connect to db
		$this->connectDb();
		$count = $this->db->get_var('SELECT count(*) FROM users');		
		$count = ceil($count/$this->datasetLimit);
		return $count;
	}
	
	public function Export($page=0)
	{
		if($page == 0)
		{
			$query = "SELECT * FROM users ORDER BY ID DESC";
		}
		else 
		{
			$page=($page-1)*$this->datasetLimit;
			$query = "SELECT * FROM users ORDER BY ID DESC LIMIT $page,$this->datasetLimit ";
		}
		$this->connectDb();
		$result = $this->db->get_results($query, ARRAY_A);
		
		if(isset($EZSQL_ERROR))
		{
			return false;	
		}
		else
		{
			$headers = array('User Id','First Name', 'Last Name', 'Address', 'City',
			'State', 'Zip Code', 'Email', 'Phone', 'Birth Date','Receive Ads and Promotions',
			'Video Title' ,'Video Description', 'Video File Name', 'Upload YouTube' ,
			'YouTube Link','Status', 'Approve/Disapprove Date', 'Registration Date');
			$export =  new ExportExcel('LC-Video-Participants.csv');
			$export->setHeadersAndValues($headers,$result);
			$export->GenerateExcelFile();
			die();
		}
	}
}

/*
 * Export to Excel Class 
 */
class ExportExcel
{
	//variable of the class
	var $titles=array();
	var $all_values=array();
	var $filename;
	
	//functions of the class
	function ExportExcel($f_name) //constructor
	{
		$this->filename=$f_name;
	}
	function setHeadersAndValues($hdrs,$all_vals) //set headers and query
	{
		$this->titles=$hdrs;
		$this->all_values=$all_vals;
	}
	function GenerateExcelFile() //function to generate excel file
	{
		$header = '';
		$data = '';
		$helper =  new Helper();		
		foreach ($this->titles as $title_val) 
 		{ 
 			$header .= $title_val.","; 
 		} 
 		for($i=0;$i<sizeof($this->all_values);$i++) 
 		{ 
 			$line = ''; 			 
 			foreach($this->all_values[$i] as $key=>$value) 
			{
				if($key == 'ytdata') continue;											//skip this field to exclude from excel file
				if($key == 'ytid') $value = 'www.youtube.com/watch?v='.$value;	//add full link for youtube to the id
				
 				if ((!isset($value)) OR ($value == "")) 
				{ 
 					$value = ","; 
 				} //end of if
				else 
				{
					switch($key)
					{
						case 'ytdata':
							{
								$value = '';
							}break;
						case 'ytstatus':
							{
								$value = $helper->ConvertYtStatus($value);	
							}break;
						case 'ads':
							{
								$value = $helper->ConvertBool($value);	
							}break;
						case 'status':
							{
								$value = $helper->ConvertStatus($value);	
							}break;
						case 'won':
							{
								$value = $helper->ConvertBool($value);	
							}break;
					}
					//$value = str_replace('"', '""', $value); 
					$value = '"' . $value . '"' . ",";
							 					 
 				} //end of else
 				$line .= $value; 
 			} //end of foreach
 			$data .= trim($line)."\n"; 
 		}//end of the while 
 		$data = str_replace("\r", "", $data); 
		if ($data == "") 
 		{ 
 			$data = "\n(0) Records Found!\n"; 
 		} 
		//echo $data;
		header("Content-type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=$this->filename"); 
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		print "$header\n$data";		
	}
}

/*
 * Helper Class for interface 
 */
Class Helper
{
	//public properties
	public $errors;
	public $adminEmail = 'menafgul@gmail.com';
	
	
	//construct
	function __construct()
	{
		$this->errors=array();
	}
	
	//public methods
	public function GetStates($selected)
	{
		$states = array(
			'AL'=>"Alabama",  
			'AZ'=>"Arizona",  
			'AR'=>"Arkansas",  
			'CA'=>"California",  
			'CO'=>"Colorado",  
			'CT'=>"Connecticut",  
			'DE'=>"Delaware",  
			'DC'=>"District Of Columbia",  
			'FL'=>"Florida",  
			'GA'=>"Georgia", 
			'ID'=>"Idaho",  
			'IL'=>"Illinois",  
			'IN'=>"Indiana",  
			'IA'=>"Iowa",  
			'KS'=>"Kansas",  
			'KY'=>"Kentucky",  
			'LA'=>"Louisiana",  
			'ME'=>"Maine", 
			'MA'=>"Massachusetts",  
			'MI'=>"Michigan",  
			'MN'=>"Minnesota",  
			'MS'=>"Mississippi",  
			'MO'=>"Missouri",  
			'MT'=>"Montana",
			'NE'=>"Nebraska",
			'NV'=>"Nevada",
			'NH'=>"New Hampshire",
			'NJ'=>"New Jersey",
			'NM'=>"New Mexico",
			'NY'=>"New York",
			'NC'=>"North Carolina",
			'OH'=>"Ohio",  
			'OK'=>"Oklahoma",  
			'OR'=>"Oregon",  
			'PA'=>"Pennsylvania",  
			'RI'=>"Rhode Island",  
			'SC'=>"South Carolina",  
			'SD'=>"South Dakota",
			'TN'=>"Tennessee",  
			'TX'=>"Texas",  
			'UT'=>"Utah",  
			'VA'=>"Virginia",  
			'WA'=>"Washington",  
			'WV'=>"West Virginia",  
			'WI'=>"Wisconsin",  
			'WY'=>"Wyoming");
		$output = '';
		foreach($states as $key=>$value)
		{
			$output.="\t\t\t<option value='$key'";
			if($key == $selected) $output.=" selected ";
			$output.=">$value</option>\n";	
		}
		return $output;  
	}
	
	public function checkData($data)
	{
		$hasError=false;
		if(empty($data['fName'])){
			$hasError=true;
			$this->errors['fName']='Please enter your <em>First Name</em>';
		}
		
		if(empty($data['lName'])){
			$hasError=true;
			$this->errors['lName']='Please enter your <em>Last Name</em>';
		}
		
		if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
			$hasError=true;
			$this->errors['email']='Please enter a correct <em>Email Address</em>';
		}
		if(empty($data['phone1']) || empty($data['phone2']) || empty($data['phone3'])){
			$hasError=true;
			$this->errors['phone']='Please enter a correct <em>Phone Number</em>';
		}
		if(empty($data['address'])){
			$hasError=true;
			$this->errors['address']='Please enter your <em>Address</em>';
		}
		if(empty($data['state'])){
			$hasError=true;
			$this->errors['state']='Please select your <em>State</em> from the list';
		}
		if(empty($data['city'])){
			$hasError=true;
			$this->errors['city']='Please enter your <em>City</em>';
		}
		if(empty($data['zip']) && (strlen($data['zip']) != 5)){
			$hasError=true;
			$this->errors['zip']='Please enter a valid <em>Zip Code</em>';
		}
		
		if(($data['ageDay'] < 1) || ($data['ageDay'] > 31) || ($data['ageMonth'] < 1) || ($data['ageMonth'] > 12) || ($data['ageYear'] < 1900) || ($data['ageYear'] > 2011)){
			$hasError=true;
			$this->errors['age']='Please enter a valid <em>birth date</em>';
		}
		else
		{
			$bdTemp = $data['ageYear'].'-'.$data['ageMonth'].'-'.$data['ageDay'];
			
			$diff =  floor((time() - strtotime($bdTemp))/ 86400);
			
			//this is removed as diff function only supported in php >= 5.3.2
			//$diff = $now->diff(new DateTime($stat->regDate))->d;//difference between today and registration date
			
			//compare and add to necessary place
			//this week's results
			
			if($diff < 6574)
			{
				$hasError=true;
				header('Location: finish.php?q=e');
			}		
		}
		if(!file_exists(VIDEO_FOLDER.$data['vname']) || empty($data['vname']))
		{
			
			$hasError = true;
			$this->errors['vpath']='Video file is not uploaded or uploaded file is not valid. Please reupload video file.';
		}
		if(empty($data['vtitle'])){
			$hasError=true;
			$this->errors['vtitle']='Please enter <em>Title</em> of the video';
		}
		if(empty($data['scrimg']))
		{
			$hasError = true;
			$this->errors['scrimg']='Please enter the security code seen in the image!';
		}
		else
		{
			$img = new Securimage();
			if( $img->check($data['scrimg']) == false)
			{
				$hasError = true;
				$this->errors['scrimg']='You entered wrong security code!';
			}
		}
		if(empty($data['term']) || $data['term']!=1){
			$hasError=true;
			$this->errors['term']='You have to accept all terms and conditions to submit the form';
		}
		return $hasError;
	}
	
	public function ConvertGender($id)
	{
		switch($id)
		{
			case "m":{ return 'Male';}break;
			case "f":{ return 'Female';}break;
			default: {return ' ';}
		}		
	}
	public function ConvertStatus($id)
	{
		switch($id)
		{
			case '1':{ return 'Approved';}break;
			case '-1':{ return 'Not Approved';}break;
			case '0':{ return 'Pending';}break;
			default: {return ' ';}
		}		
	}
	public function ConvertYtStatus($id)
	{
		switch($id)
		{
			case '1':{ return 'Upload Finished';}break;
			case '-1':{ return 'Error Occured!';}break;
			case '0':{ return 'Pending';}break;
			default: {return ' ';}
		}		
	}
	public function ConvertAge($id)
	{
		switch($id)
		{
			case '1':{ return '18-35';}break;
			case '2':{ return '36-45';}break;
			case '3':{ return '46-55';}break;
			case '4':{ return '56-65';}break;
			case '5':{ return '66+';}break;
			default: {return ' ';}
		}		
	}
	public function ConvertYesNo($id)
	{
		switch($id)
		{
			case '1':{ return 'Yes';}break;
			case '0':{ return 'No';}break;
			default: {return ' ';}
		}		
	}

	
	public function ConvertBool($val)
	{
		switch($val)
		{
			case 1 : {return 'Yes'; }break;
			case 0 : {return 'No';  }break;
			default: { return ' ';}
		}
	}
	
	
	public function SendSystemEmail($subject, $msg, $ccothers=false)
	{
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

		// Additional headers
		$headers .= 'From: La Costeña Video App<videoapp@lacostenausa.com>' . "\r\n";
		
		if($ccothers)
		{
			$headers .= 'Cc: msaenz@interblocstudios.com' . "\r\n";
			$headers .= 'Bcc: Menaf Gul <agirehar@gmail.com>' . "\r\n";
		}
		
		$to = 'La Costeña USA <lacostenausa@gmail.com>';
				
		// Mail it
		@mail($to, $subject, $msg, $headers);	
	}
	
	public function SendStatusEmail($to, $subject, $msg, $ccothers=true)
	{
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

		// Additional headers
		$headers .= 'Cc: La Costeña USA <lacostenausa@gmail.com>' . "\r\n";
		$headers .= 'Bcc: Menaf Gul <agirehar@gmail.com>' . "\r\n";
		
		if($ccothers)
		{
			$headers .= 'From: La Costeña Video App<videoapp@lacostenausa.com>' . "\r\n";		
		}
				
		// Mail it
		@mail($to, $subject, $msg, $headers);	
	}
	
}

/* Admin Class for general Admin section tasks*/
Class Admin
{
	//private properties
	private $uname='admin';
	private $pass='admin';
	
	/*
	 * Check Admin privilage
	 * @return true|false True, if admin session is active, False othervise
	 */
	public function IsActive()
	{
		//session_start(); already started at bootstrap
		if(isset($_SESSION['isadmin']) && ($_SESSION['isadmin']=='yes'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*
	 * Check ogin information for admin rights
	 * @return true|false True, If the username and password is correct	 *
	 */
	public function IsAdmin($uname, $pass)
	{
		if(($this->uname == $uname) && ($this->pass == $pass))
		{
			//session_start(); already started at bootstrap
			$_SESSION['isadmin']='yes';
			session_write_close();
			return true;	
		}
		else
		{
			return false;
		}	
	}
	
	/*
	 * LogOut the admin and clear session information 
	 */
	public function LogOut()
	{
		session_start();
		session_unset();
		session_destroy();
	}
}
?>