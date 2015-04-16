<?php

// Handle POST-s
if (isset($_POST) AND !empty($_POST)) {
    
    if ($debug) {
        echo "<pre>";
        echo "POST ";
        print_r($_POST);
        echo "</pre>";
    }
    
    if (isset($_POST["cropimages"])) {
        
        $expects = explode("|", $_POST["cropids"]);
        
        foreach($expects AS $p_id) {
            if (isset($_POST["bimg-hide-".$p_id]) AND $_POST["bimg-hide-".$p_id] == "NOK") {
                mysqli_query($db, "UPDATE ballerpicture SET `review`='NOK' WHERE id=".$p_id);
            } else {
                $pSQL = mysqli_query($db, "SELECT `file_name` AS ourl FROM ballerpicture WHERE id=".$p_id);
                if (mysqli_num_rows($pSQL) > 0) {
                    $pData = mysqli_fetch_assoc($pSQL);
                
                    // Calculate ratio
                    $size = getimagesize($orig_img_url.$pData["ourl"]);
                    $ratio = 1;
                    $uiw = (int) $_POST["bimg-uiw-".$p_id];
                    if ($uiw < $size[0] OR $uiw > $size[0]) {
                        $ratio = $uiw / $size[0];
                    }
                    
                    // Get params
                    $x = (int) floor($_POST["bimg-x-".$p_id] / $ratio);
                    $y = (int) floor($_POST["bimg-y-".$p_id] / $ratio);
                    $w = (int) floor(($_POST["bimg-w-".$p_id] ? $_POST["bimg-w-".$p_id] : $size[0] - $_POST["bimg-x-".$p_id]) / $ratio);
                    $h = (int) floor(($_POST["bimg-h-".$p_id] ? $_POST["bimg-h-".$p_id] : $size[1] - $_POST["bimg-y-".$p_id]) / $ratio);
                    
                    // crop-n-resize and save to png format
                    $convert = "convert ".$orig_img_url.$pData["ourl"]." -crop ".$w."x".$h."+".$x."+".$y." -format jpg ".$crop_img_url.$pData["ourl"];
                    
                    if ($debug) {
                        echo "<pre>"; 
                        echo $convert;
                        echo "</pre>";
                    }
                    
                    exec($convert); 
                    mysqli_query($db, "UPDATE ballerpicture SET `review`='$x $y $w $h' WHERE id=".$p_id);
                }
            }
        }
    }
    
    if (isset($_POST["trashcropped"])) {
        $pSQL = mysqli_query($db, "SELECT `file_name` AS ourl FROM ballerpicture WHERE id=".$_POST["trashcropped"]);
        if (mysqli_num_rows($pSQL) > 0) {
            $pData = mysqli_fetch_assoc($pSQL);
            unlink($crop_img_url.$pData["ourl"]);
            mysqli_query($db, "UPDATE ballerpicture SET `review`='NOK' WHERE id=".$_POST["trashcropped"]);
        }
        
    }
}
?>