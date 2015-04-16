<?php
if ($debug) {
    //Do not cache while debugging
    header("Content-Type: text/html; charset=utf-8");
    header('Expires: ' . gmdate('D, d M Y H:i:s') . 'GMT');
    header('Cache-Control: no-cache;');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require("content/header.php"); ?>
    </head>
    <body>
        <!-- Page menu -->
        <?php require("content/menu.php"); ?>

        <!-- Page content -->
        <div class="container">
            <div class="row">
                <?php
                if (is_file("pages/" . $page . ".php")) {
                    require("pages/" . $page . ".php");
                } else {
                    require("pages/error.php");
                }
                ?>
            </div>
        </div>

        <!-- Page footer -->
        <?php require("content/footer.php"); ?>
    </body>
</html>