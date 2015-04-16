<?php
if ($debug) {
    //Do not cache while debugging
    header("Content-Type: text/html; charset=utf-8");
    header('Expires: ' . gmdate('D, d M Y H:i:s') . 'GMT');
    header('Cache-Control: no-cache;');
}
?>
<!DOCTYPE html>
<html class="login">
    <head>
        <?php require("content/header.php"); ?>
    </head>
    <body class="login text-center">
        <div id="wrap">
            <div class="parent-center-center-1">
                <div class="parent-center-center-2">
                    <div class="parent-center-center-3">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 voffsete3">
                                    <a href="<?php echo $baseURL; ?>?login=google" class="login-button login-button-google"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page footer -->
        <?php require("content/footer.php"); ?>
    </body>
</html>