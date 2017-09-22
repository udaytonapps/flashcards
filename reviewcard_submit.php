<?php
require_once "../config.php";
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
$LAUNCH = LTIX::requireData(); 


if (isset($_GET["Answer"])) {
	$Answer = $_GET["Answer"];
	}
else{
	$Answer = 0;}
$CardNum = $_GET["CardNum"];

// View
$OUTPUT->header();
$OUTPUT->bodyStart();
include("menu.php"); 
?>
<link href="styles/FlashCards.css" rel="stylesheet">

<br><br>
<div style="width:400px; height:300px; margin-top:20px;">
 <table class="Review" >
        <tr height="250">
          <td valign="middle">
		  
		  <?php echo $_SESSION["SideA"]; ?>
		  
		 
</td></tr>
		 
		
		 
   </table>

   
    <a href="reviewcard.php" style="height:50px; margin-top:-50px;float:right;"><img src="images/Next.png" width='50' ></a>
   
</div>



<div style="margin-left:410px; margin-top:-300px; width:600px; height:auto; ">
	
  
  
  <?php 
for ($x = 0; $x <= 3; $x++) {
	$variable = "A".$x;
	$Option = "B".$x;
	
	
?>
  
  <label >
    <span><?php   //echo $_SESSION[$Option];?> 
	<?php if($CardNum == $_SESSION[$Option]){ 
			echo "<img src='images/CM.PNG'>";
		}else {echo "<img src='images/CM2.PNG'>";} ?>
	
	</span>
	<p style=" margin-left:50px; margin-top:-40px;"><?php   echo $_SESSION[$variable];?></p>
  </label>

 
<?php
}// for loop 


?>
	 
</div>


<br><br>

<?php


if ($CardNum == $Answer){
	
	//$_SESSION['success'] = __('Great!');
	echo "<img src='images/Correct1.png'>";
		
}
else {
	//$_SESSION['error'] = __('Code incorrect');
echo "<img src='images/Incorrect1.png'>";	
}



//$OUTPUT->flashMessages();// feedback

$OUTPUT->footer();


