<?php
session_start();
ob_start();
?>
<html>
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width initial-scale=1">
    <title>Polkatu Party</title>
    <link rel="stylesheet" type="text/css" href="CSS/styleagain.css">
</head>
<body>
	<?php include 'navbar.php';?>
	<div id="main">
    <div class=banner>
		<img src="Images/coconut.png" class="logo">	
		<div class="title">
		<center>
			
        <h1 class="bannertext">Polkatu Party</h1>
			
			
			</center>
		</div>
		
		<div class="textbanner1">
			<div class="text">
				
				<h1>No more boundaries</h1>
			  <p>Beat the Geographical and Social bourdaries with Polkatu Party. Now with UNILIMITED minutes for all!</p>
			  
			    <form action="Images/comingsoon_02/">
			        <button class="startparty">Start a Party</button>
			        <div class="join">
				        <input class="image_sub" type="image" onmouseover="this.src = 'Images/key3.png'" onmouseout="this.src = 'Images/key.png'" src="Images/key.png" alt="submit">
				        <input type="text" class="joint" placeholder="Enter Link to Join">
				    </div>
				</form>
				
				 
				 <br>
			  <a href="Images/comingsoon_02/">Learn more..</a>
		  </div>
			
		</div>
		<div class="textbanner2">
			<img src="Images/hero2.jpg" class="hero">
			
		</div>
    </div>
	<img src="Images/coconut.png" class="logo">
		</div>
    <?php 
      session_unset();
	?>
	<script>
		function link() {
			window.location.href = "#";
			
		}

	</script>
</body>
</html>
