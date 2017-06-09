<?php
$q = $_GET['q'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>La Costeña USA - "Traditions Your Way" Contest</title>
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div id="mainDiv">
		<div id="thnkTop"></div>
		<div id="thnkContent">
			<?php if($q == "s"){?>
			<p>
				Thank you for your submission to the La Costeña "Traditions Your Way" Contest. The video is currently being reviewed. If it meets the contest requirements it will be posted to the La Costena website within 48 hours. 
			</p>
			<?php }?>
			<?php if($q == "e"){?>
			<p>
				Sorry, you are ineligible to enter but please visit the rest of our site. 
			</p>
			<?php }?>
			<p>
				<a href="http://www.lacostenausa.com/" title="Visit La Costena Website">www.lacostenausa.com</a>
			</p>
		</div>
	</div>
</body>
</html>