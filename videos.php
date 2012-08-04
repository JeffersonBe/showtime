<?php include'Scripts/form.php';?>
<!doctype html>
<!-- 
                                                                                                        
 ad88888ba   88                                                     88                                  
d8"     "8b  88                                              ,d     ""                                  
Y8,          88                                              88                                         
`Y8aaaaa,    88,dPPYba,    ,adPPYba,   8b      db      d8  MM88MMM  88  88,dPYba,,adPYba,    ,adPPYba,  
  `"""""8b,  88P'    "8a  a8"     "8a  `8b    d88b    d8'    88     88  88P'   "88"    "8a  a8P_____88  
        `8b  88       88  8b       d8   `8b  d8'`8b  d8'     88     88  88      88      88  8PP"""""""  
Y8a     a8P  88       88  "8a,   ,a8"    `8bd8'  `8bd8'      88,    88  88      88      88  "8b,   ,aa  
 "Y88888P"   88       88   `"YbbdP"'       YP      YP        "Y888  88  88      88      88   `"Ybbd8"'  

 -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="fr"><![endif]-->
<html class="no-js" lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Nos bonus vidéos</title>
	<meta name="description" content="">
	<?php include'includes/header.php'?>
</head>
<body>
<div id="barre-header"><?php include'includes/barre-header.php';?></div>
<div id="container">
	<div id="header">
		<a href="index.php"><div id="head-left"></div></a>
<!--menu-->
<?php include'includes/menu.php'?>
<!--fin-menu-->
	</div><!-- #end de header -->
	<?php include'includes/navigation.php'?>
	<div id="main" role="main">
		<div id="container-body"><?php include'includes/bonus-videos.php'?></div>
	</div><!-- #end of main -->
	
	<?php include'includes/footer.php'?>
</div> <!--! end of #container -->
<?php include'includes/scripts.php' ?>
</body>
</html>