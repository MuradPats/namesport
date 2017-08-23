<?php
	include 'dbh.php'
?>

<!DOCTYPE html>
<html>
    <head>
        <title>NameSport - The Ultimate Sports Guessing Game</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="author" content="Taavi Jaanson | Ideelabor OÜ | taavi@ideelabor.ee">
        <meta name="description" content="Name this football player">
		<meta name="google-signin-client_id" content="45704542268-3m8u37ktmsa046fs7qfpi7m1gr70f5c0.apps.googleusercontent.com">
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css" />
        <!-- Added CSS -->
		<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/grayscale.css?ver=<?php echo rand(111,999)?>" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/style.css?ver=<?php echo rand(111,999)?>" />
		<!-- Fonts -->
		<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
		<!-- jQuery -->
		<script src="vendor/jquery/jquery.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

		<!-- Plugin JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

		<!-- Google Maps API Key - Use your own API key to enable the map feature. More information on the Google Maps API can be found at https://developers.google.com/maps/ -->
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>
		
		<!-- Google sign in script -->
		<script src="https://apis.google.com/js/platform.js" async defer></script>

		
		<!-- Javascript Original-->
		<script src="js/libs/jquery/jquery.js"></script>
        <script src="js/pietimer.js"></script>
        <script src="js/main.js"></script>
		<!-- jQuery Code-->
		<script>
			
		</script>
		
		
    </head>
	
    <body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
	
	<!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">
                    <!--<i class="fa fa-soccer-ball-o"></i> <span class="light">Name</span> Sport-->
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li id="menu_play">
                        <a  class="page-scroll" href="#">Play</a>
                    </li>
                    <li id="menu_about">
                        <a  class="page-scroll" href="#">About</a>
                    </li>
                    <li id="menu_leaderboards">
                        <a  class="page-scroll" href="#">Leaderboards</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
	
	<!-- Intro Header -->
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div id="playrow" class="row">
                    <div id="bootstrap-playrow-class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading">NameSport</h1>
                        <p class="intro-text">Can You Name These Football Players?
                            <br>Created by IdeeLabor</p>
						<div id="difficulty" class="row">	
	
							<div  id="startgame_easy" class="btn btn-circle page-scroll">
								<i class="fa fa-soccer-ball-o animated"></i>
								<p class="intro-text2">EASY</p>
							</div>

							<div   id="startgame_medium"  class="btn btn-circle page-scroll">
								<i class="fa fa-soccer-ball-o animated"></i>
								<p class="intro-text2">MEDIUM</p>
							</div>
							<div   id="startgame_hard"  class="btn btn-circle page-scroll">							
								<i class="fa fa-soccer-ball-o animated"></i>
								<p class="intro-text2">HARD</p>
							</div>
							
						</div>
                            
                            
						
                    </div>
                </div>
				<div id="countdownrow" class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 id="counterelement" class="brand-heading"></h1>
                        					
                    </div>
                </div>
				
				
				
				<div id="gamerow" class="row">
                            
                            <div id="game_container" class="col-lg-3  col-lg-offset-3">
                                
                                <div id="question">
                                    <img>   
                                </div>
								<span id="time"></span>
								<p class="intro-text">SCORE <span id="score" class="intro-text" ><span>0</span></span></p>
								<p id="demo" style="height: 150px;"></p>
                                
                            </div>
							
							<div id="game_container" class="col-lg-3">
                                <div id="question">
                                    
                                    <ul id="answers" class="list-unstyled"></ul>
                                </div>
                                
                            </div>
                            
                </div>
				
				<div id="aboutrow" class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading">About</h1>
                        <p class="intro-text">Can You Name These Football Players?
						
                    </div>
                </div>
				
				<div id="leaderboardsrow" class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading">Leaderboards</h1>
                        <div id="board">
							<ul id="leaders" class="newbar"></ul>
						</div>
					</div>
				
						
				</div>
				
				
				<div id="submitrow" class="row">
                    <div class="col-md-8 col-md-offset-2">
						<h1 class="brand-heading">Game Over</h1>
						<p>Submit your score</p>
						<span id="score" class="intro-text"><span>0</span></span>
						<div class="g-signin2" align="center" data-onsuccess="onSignIn"></div>
                    </div>
                </div>
				
				
			</div>
			
    </header>

	
        <div id="overlay"></div>
        
        
        
        <div id="insert-json-here"></div>
        
        <script>
			
		
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-58250000-1', 'auto');
            ga('send', 'pageview');
        </script>
        
    </body>
</html>
