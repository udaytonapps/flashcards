<!DOCTYPE html>
<html>
  <head>
    <title>Flashcards Preview</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="javascript:void(0);">Learning Apps</a>
<?php
	if(isset($_GET["PHPSESSID"])) {
		echo('<a class="btn btn-warning" style="margin-top:.5em;" href="/tsugi/lti/store/index.php?PHPSESSID='.$_GET["PHPSESSID"].'">Back to Store</a>');
	}
?>

        </div>

      </div>
    </nav>


       <!-- Main jumbotron for a primary marketing message or call to action -->
       <div class="jumbotron">
        <div class="container-fluid"> 
   	   <div class="col-sm-7">
   		<h1>Flashcards</h1>
   	        <p>Create flashcard sets that include text, images, MP3s, or videos that your students can use to study important material in your course sites. It’s fun and works great on a browser or mobile device.
</p>
   	        <p><a class="btn btn-primary btn-lg" target="_blank" href="https://ewiki.udayton.edu/isidore/Flashcards" role="button">Learn more &raquo;</a></p>
   	   </div>
   	   <div class="col-sm-5">
		<div class="videoWrapper">
	   		<iframe width="560" height="315" src="https://www.youtube.com/embed/MxZk1psRyQA?rel=0" frameborder="0" allowfullscreen></iframe>
		</div>
       </div>
	</div>
      </div>
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-sm-4">
          <h2>Track Usage</h2>
          <p>Instructors can easily see which students have looked at the flashcards through the Usage button built into each set. Students are recorded as having seen the card when they flip it to see the other. Usage data can also be exported to MS Excel. </p>
        </div>
        <div class="col-sm-4">
          <h2>Review More</h2>
          <p>Students are able to mark cards they feel like they know well as they’re going through the set. They can use the ‘Review Mode’ to temporarily remove the cards they already know from their view to save time while studying.</p>
       </div>
        <div class="col-sm-4">
          <h2>Shuffle Cards</h2>
          <p>Students can use the built in ‘Shuffle Cards’ button to change the order that they’re seeing the cards in each set.</p>
        </div>
      </div>

    </div> <!-- /container -->


  </body>
</html>

