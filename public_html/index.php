<!DOCTYPE html>
<html>
    <head>
        <title>NameSport - The Ultimate Sports Guessing Game</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="author" content="Taavi Jaanson | Ideelabor OÜ | taavi@ideelabor.ee">
        
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        
        <!-- Javascript -->
        <script src="js/libs/jquery/jquery.js"></script>
        <script src="js/main.js"></script>
    </head>
    <body>
        <div id="overlay"></div>
        
        <div class="parent-center-center-1">
            <div class="parent-center-center-2">
                <div class="parent-center-center-3">
                    
                    <div class="container-fluid">
                        <div class="row">
                        
                            <div id="start_game_container" class="col-lg-2 col-lg-offset-5 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                                <button type="button" class="btn btn-primary btn-lg btn-block" id="start_game">Start game</button>
                            </div>
                            
                            <div id="game_container" class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
                                <span id="time"></span>
                                <span id="score"><span>0</span></span>
                                <div id="question">
                                    <img>
                                    <ul id="answers" class="list-unstyled"></ul>
                                </div>
                                <button id="submit">Answer</button>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
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