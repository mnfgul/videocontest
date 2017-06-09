<?php
//function needed for all actions
include_once('include/bootstrap.php');

$helper=new Helper();
$isPost =  false;

$youtube = new LCYoutube();
$vhelper = new VideoHelper();
$lcvideo = new LCVideo();


//just for post
if(!empty($_POST)){
	
	$hasError = false;
	$isPost = true;
	
	//validate submitted data
	
	if($helper->checkData($_POST))
	{
		$hasError=true;	
	}
	else
	{
		//save info and redirect user to thank you page
		$lcvideo = new LCVideo();
		$lcvideo->userData = $_POST;
		$result = $lcvideo->CreateUser();
		if($result == true)
		{
			//echo "Thank You!";
			//redirect page to finish
			header('Location: finish.php?q=s');	
		}
		else
		{
			$hasError = true;
		}
		
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>La Costeña USA - "Traditions Your Way" Contest</title>
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/prettyPhoto.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" href="uploadify/uploadify.css" type="text/css" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/easySlider.js"></script>
	<script type="text/javascript" src="js/swfobject.js"></script>
	<script type="text/javascript" src="js/jquery.uploadify.js"></script>
	<script type="text/javascript" src="js/form.js"></script>
	<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
	<?php if ($hasError){?>
	<script type="text/javascript">
		location.hash = "errors";
	  	e.preventDefault();
	</script>
	<?php }?>
</head>
<body>
	<div id="mainTop" class="mainSec">
		<div id="mainTopContent">
			<p class="white">
				Participa en el concurso "Tradiciones a Tu Estilo" de La Costeña. Es fácil. Dínos en un video cómo preparas un plato tradicional con tu toque moderno. Sube tu video siguiendo tres sencillos pasos y podrías ganarte un retiro culinario de cuatro días y tres noches para dos personas en un complejo turístico de lujo en Santa Fe, New Mexico. Tu video podría ser el ganador. La Costeña proporciona el gran sabor y la calidad de sus productos tradicionales; tú añades tu creatividad.
			</p>
			<p class="yellow" style="padding-left:75px;">
				Participate in La Costeña's “Traditions Your Way” contest. It's easy. Tell us in a video how you prepare a traditional dish with your modern twist. Upload your video following three easy steps and you could win a 4-day, 3-night culinary retreat for two at a luxury resort in Santa Fe, New Mexico. Your video could be the winner. La Costeña provides the great flavor and quality of its traditional products; you add your creativity.
			</p>
		</div>
	</div>
	<div id="mainTop2">
		<div id="mainTop21">
			<div id="mainTop2Content" class="mainSec">
				<div id="mainTop2Text">
					<p class="white">Haz clic aquí para ver cómo Patricia López añade su propio toque moderno a un plato tradicional, ensalada de frijoles negros de La Costeña. Verás qué fácil le será crear su video con una duración de 90 segundos.</p>
					<p class="yellow">Click here to watch how Patricia Lopez adds her own modern twist to a traditional dish, La Costeña Black Bean Salad. You'll see how easy it is for you to create a 90 second video.</p>
					<ul class="nostyle introVideos">
						<li>
							<a href="http://www.youtube.com/watch?v=5bKDsazIf3s" title="Tradiciones a Tu Estilo" rel="videos[patricia]" target="_blank">
								<img src="files/images/patricia-intro-thumb.jpg"/>
							</a>
							Español
						</li>						
						<li>
							<a href="http://www.youtube.com/watch?v=brnKQZgO11o" title="Traditions Your Way" rel="videos[patricia]" target="_blank">
								<img src="files/images/patricia-intro-thumb.jpg"/>
							</a>
							English
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div id="formSec" class="mainSec">
		<div id="formSec2">
			<div id="formSecTop"></div>
			<div id="formSecContent">			
			
				<div id="formStep1" class="formStep">
					<h1>
						<span class="white">Sigue estos tres sencillos pasos para participar</span><br/>
						<span class="yellow">Follow these 3 easy steps to participate</span>
					</h1>
					<p>
						Crea un video original con una duración máxima de 90 segundos, que muestre cómo añades tu propio toque moderno y estilo personal a un platillo tradicional usando los productos de La Costeña y otros ingredientes adicionales. Tu video puede ser en inglés o en español.
					</p>
					<p>
						Se juzgarán las participaciones por lo adecuadas que sean al tema del concurso, la originalidad y la creatividad, así como la pasión y el entusiasmo al usar los productos de La Costeña en tu plato tradicional.
					</p>
					<p>
						El ganador se anunciará el 30 de septiembre de 2011, o antes. Debes ser mayor de 18 años de edad y residente legal de los EE. UU. Participa antes de la fecha y hora límite estipuladas para el concurso: las 11:59:59 p.m. horario del este (ET), del sábado 27 de agosto.
					</p>
					<p class="yellow">
						Create an original video of 90 seconds or less that conveys how you add your own modern twist and personal style to a traditional dish using La Costeña products and any additional ingredients. Your video may be in English or Spanish.
					</p>
					<p class="yellow">
						Entries will be judged on appropriateness to contest theme, originality and creativity, and passion and enthusiasm for using La Costeña products in your traditional dish.
					</p>
					<p class="yellow">
						Winner will be announced on or before September 30, 2011. You must be 18 and a legal U.S. resident. Enter before the contest deadline of 11:59:59 p.m. ET, Saturday, August 27.
					</p>
				</div>
				
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return validateForm();">				
				<div id="formStep2" class="formStep">
					<h1>
						<span class="white">Inscripción / </span><span class="yellow">Registration</span>
					</h1>
					<?php 
						if($hasError)
						{
						?>
						<div class="error" id="errors">
							<ul>
								<?php 
									foreach($helper->errors as $key => $value)
									{
										echo '<li>'.$value.'</li>';
									}
									if(isset($lcvideo->errors))
									{
										foreach($lcvideo->errors as $key => $value)
										{
											echo '<li>'.$value.'</li>';
										}
									}
								?>
							</ul>
						</div>
						<?php 
						}
					?>
					<table class="formPart" id="part1">
						<tbody>
							<tr>
								<td>
									<span class="label">Nombre</span>
									<span class="label yellow">First Name</span> 
								</td>
								<td style="width: 600px;">
									<input type="text" id="fName" name="fName" class="text required" value="<?php if($isPost){echo $_POST['fName'];}?>">
								</td>
							</tr>
							<tr>
								<td>
									<span class="label">Apellido</span>
									<span class="label yellow">Last Name</span>
								</td>
								<td>
									<input type="text" id="lName" name="lName" class="text required" value="<?php if($isPost){echo $_POST['lName'];}?>">
								</td>
							</tr>
							<tr>
								<td>
									<span class="label">Dirección</span>
									<span class="label yellow">Address</span>
								</td>
								<td>
									<input type="text" id="address" name="address" class="text required" value="<?php if($isPost){echo $_POST['address'];}?>">
								</td>
							</tr>
							<tr>
								<td>
									<span class="label">Ciudad</span>
									<span class="label yellow">City</span> 
								</td>
								<td>
									<input type="text" id="city" name="city" class="text short required" value="<?php if($isPost){echo $_POST['city'];}?>">
								</td>
							</tr>
							<tr>
								<td>
									<span class="label">Estado</span>
									<span class="label yellow">State</span>
								</td>
								<td>
									<select name="state" id="select" class="required">
										<?php echo $helper->GetStates($_POST['state']);?>
									</select>
								</td>
							</tr>			
							<tr>
								<td>
									<span class="label">Código Postal</span>
									<span class="label yellow">Zip Code</span>
								</td>
								<td>
									<input type="text" id="zip" name="zip" class="text short requiredZip number" maxlength="5" value="<?php if($isPost){echo $_POST['zip'];}?>">
								</td>
							</tr>
							<tr>
								<td>
									<span class="label">Correo electrónico</span>
									<span class="label yellow">Email</span>
								</td>
								<td>
									<input type="text" id="email" name="email" class="text email" value="<?php if($isPost){echo $_POST['email'];}?>">
								</td>
							</tr>
							<tr>
								<td>
									<span class="label">Teléfono</span>
									<span class="label yellow">Phone</span> 
								</td>
								<td>
									<input type="text" id="phone1" name="phone1" class="text phone short3 number" maxlength="3" value="<?php if($isPost){ echo $_POST['phone1'];}?>"> - 
									<input type="text" id="phone2" name="phone2" class="text phone short3 number" maxlength="3" value="<?php if($isPost){ echo $_POST['phone2'];}?>"> - 
									<input type="text" id="phone3" name="phone3" class="text phone short3 number required" maxlength="4" value="<?php if($isPost){ echo $_POST['phone1'];}?>">
								</td>
							</tr>
							<tr>
								<td>
									<span class="label">Edad</span>
									<span class="label yellow">Age</span>
								</td>
								<td>
									<table style="width: 420px; margin: 0px; padding: 0px;" width="420">
										<tr>
											<td style="width: 60px; padding: 0px;">
												Mes / <span class="yellow">Month</span><br/>
												<input type="text" id="ageMonth" name="ageMonth" class="text age short3 number" maxlength="2" value="<?php if($isPost){ echo $_POST['ageMonth'];}?>"> /										
											</td>
											<td style="width: 60px; padding: 0px;">
												Día / <span class="yellow">Day</span><br/>
												<input type="text" id="ageDay" name="ageDay" class="text age short3 number" maxlength="2" value="<?php if($isPost){ echo $_POST['ageDay'];}?>"> /
											</td>
											<td style="width: 250px; padding: 0px;">
												Año / <span class="yellow">Year</span><br/>
												<input type="text" id="ageYear" name="ageYear" class="text age short3 number" maxlength="4" value="<?php if($isPost){ echo $_POST['ageYear'];}?>">
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="text-align: right; padding-right: 15px; vertical-align: top;">
									<input type="checkbox" id="term" name="term" value="1" <?php if($isPost && $_POST['term']=='1') echo 'selected="selected"';?>/>
								</td>
								<td>
									<span class="white label2">Sí, he leído y acepto todos los <a href="#terms-es" rel="rules[inline]" title="Términos y Condiciones">términos y condiciones</a>.</span>
									<span class="yellow label2">Yes, I have read and accept all <a href="#terms-en" rel="rules[inline]" title="Terms and Conditions">terms and conditions</a>.</span>
								</td>
							</tr>
							<tr>
								<td style="text-align: right; padding-right: 15px; vertical-align: top;">
									<input type="checkbox" id="news" name="news" value="1" checked="checked"/>
								</td>
								<td>
									<span class="white label2">Sí, Manténganme informado sobre noticias, productos e información promocional de La Costeña.</span>
									<span class="yellow label2">Yes, keep me up to date with La Costeña news, product and promotional information.</span>
								</td>
							</tr>
						</tbody>				
					</table>									
				</div>
				
				<div id="formStep33" class="formStep">
					<p>			
						<a id="vupload" href="#formStep3" title="Click to upload video">
							<span id="videoUpload">You have a problem with your javascript</span>
						</a>
					</p>
					<table class="formPart" id="part2" style="display: none;">						
						<tr>
							<td>
								<span>Título / </span><span class="yellow">Title</span><br/>
								<input type="text" id="vtitle" name="vtitle" class="text required" value="<?php if($isPost){echo $_POST['vtitle'];}?>">
							</td>
						</tr>	
						<tr>
							<td>
								<span>Descripción / </span><span class="yellow">Description</span> <br/>
								<textarea rows="5" cols="6" style="height: 80px;" id="vdesc" name="vdesc"><?php if($isPost){echo $_POST['vdesc'];}?></textarea>  
							</td>  
						</tr>
						<tr>
							<td>
								Please enter below code to textbox next to image:
								<div>
									<img src="get-captcha.php?sid=<?php echo md5(uniqid(time())); ?>" style="float: left;">
									<div style="float: left; margin-left: 10px; padding-top: 5px;">
										<input type="text" class="text short required" id="scrimg" name="scrimg"/>
									</div>							
								</div>											
							</td>	
						</tr>
						<tr>
							<td style="width: 600px;">
								<input type="hidden" name="sid" id="sid" value="<?php echo session_id();?>"/><br/>
								<input type="hidden" name="vname" id="vname" value=""/>	<br/>
								<input type="submit" value="SUBMIT" id="btSend" class="rc5">
							</td>
						</tr>
					</table>
					<p class="white" style="width: 330px;">
						Tamaño y formato del video: el tamaño debe ser de menos de 1 GB; el formato del video puede ser WMV, MPEG, MPEG4, MOV, AVI o FLV.
					</p>
					<p class="yellow" style="width: 330px;">
						Video Size & Format:  Less than 1 GB in size; either WMV, MPEG, MPEG4, MOV, AVI or FLV file format.
					</p>					
				</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="footerSec">
		<div id="footerSec2" class="mainSec">
			<div id="footerSecContent" class="clearfix">
				<span class="white">¡Haz clic en estos videos para ver participaciones que ya se han recibido!</span><br/>
				<span class="yellow">Click on these videos to watch entries that have been submitted already!</span><br/>
				<div class="clearfix" id="sliderbox">
					<div id="slider">  
					<ul id="latestVideos" class="nostyle">
					<?php 
						$latestVideos = $lcvideo->GetVideos("ytstatus=1 AND status=1 ORDER BY id DESC LIMIT 15");
						$count = 0;
						if(!empty($latestVideos))
						{
							foreach($latestVideos as $video)
							{
								$videoEntry = unserialize($video->ytdata);
								$thumbUrl = $vhelper->videoThumbUrl($videoEntry);
								$title = $videoEntry->getVideoTitle();
								$pageUrl = $videoEntry->getVideoWatchPageUrl(0);
							?>
							<?php if(($count % 5) == 0){?>
							<li>
							<?php }?>
								<a href="<?php echo $pageUrl?>" title="<?php echo $title;?>" rel="videos[latest]" target="_blank">
									<img src="<?php echo $thumbUrl;?>" width="120" height="90"/>
								</a>
							<?php if(($count % 5) == 4){?>
							</li><li>
							<?php }?>
							<?php
							$count++; 											
							}
							?>
							</li>
						<?php 
						}
						else
						{
							echo '<p align="center"><b>There is not any video to show yet!</b></p>';
						}
					?>
					</ul>
					</div>
					<?php if($count > 0){?>
					<p style="padding: 10px; margin: 0px;">
						<a href="http://www.youtube.com/user/LaCostenaUSA" title="View all videos on YouTube" target="_blank">Click to view all videos</a>
					</p>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	
	<div id="crSec" class="mainSec">
		<div id="crSec2">
			<div id="crSecContent">
				<p id="inline_demo">
					<span id="terms-es">Esta promoción está abierta únicamente a los residentes legales de los 48 estados contiguos que conforman los Estados Unidos (excepto en Maryland, Dakota del Norte y Vermont) y el Distrito de Columbia, con un número de seguro social válido o número de identificación de los impuestos federales, que tengan 18 años de edad cumplidos en el momento de participar. Nulo en Alaska, Hawaii, Maryland, North Dakota y Vermont y en donde esté prohibido. El concurso comienza a las 12:01 AM hora del este (ET) del 4 de julio de 2011 y termina a las 11:59:59 PM ET del 27 de agosto de 2011.</span> 
					<a href="rules-es.html?ajax=true&width=620&height=470" rel="rules[e]" class="rules" title="REGLAS OFICIALES">Haz clic aquí para ver las Reglas Oficiales y los detalles.</a>
				</p>
				<p class="yellow">
					<span id="terms-en">Open only to legal residents of the 48 contiguous United States (except in Maryland, North Dakota and Vermont) and the District of Columbia with a valid social security or Federal Tax ID number who are 18 years of age or older at the time of entry. Void in Alaska, Hawaii, Maryland, North Dakota and Vermont and where prohibited.  Contest begins at 12:01 AM ET on 7/4/11 and ends at 11:59:59 PM ET on 8/27/11.</span> 
					<a href="rules-en.html?ajax=true&width=620&height=470" rel="rules[e]" class="rules" title="OFFICIAL RULES">Click here for Official Rules and details.</a>
				</p>
			</div>
		</div>
	</div>
</body>
</html>