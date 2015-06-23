<?php

// Single person OR table
if (isset($UE[2]) AND !empty($UE[2])) {
    
    $uexp = explode("-", $UE[2]);
    
    
    $ballerSQL = mysqli_query($db, "SELECT id, name FROM baller WHERE id=".$uexp[0]);
    $nbrows = mysqli_num_rows($ballerSQL);
    
    if($nbrows == 1) {
        // Baller profile
        $ballerData = mysqli_fetch_assoc($ballerSQL);
        
        echo "
        <h2>".$ballerData["name"]."</h2>";
        
        // Baller images
        $ballerPicSQL = mysqli_query($db, "SELECT id, file_name, review FROM ballerpicture WHERE baller_id=".$uexp[0]);
        
        $nprows = mysqli_num_rows($ballerPicSQL);
        
        echo "
        <p>Leidsin ".$nprows.($nprows == 1 ? " pildi" : " pilti")."</p>";
        
        // Image form
        if ($nprows > 0) {
            $bimgs = array();
            echo "
        <form method='POST' role='form' id='cropform'>
            <div class='row baller-images voffsete1'>";
            $prc = 0;
            while(list($p_id, $p_file, $p_review)=mysqli_fetch_row($ballerPicSQL)) {
                if ($p_review == "" OR $p_review == "NOK") {
                    $image = "<img src='".$baseURL.$orig_img_url.$p_file."' alt='".$ballerData["name"]."' class='baller-image-img' id='bimg-".$p_id."'>";
                    $bimgs[] = $p_id;
                } else {
                    $image = "<img src='".$baseURL.$crop_img_url.$p_file."' alt='".$ballerData["name"]."' class='baller-image-img-ok' id='bimg-".$p_id."'>";
                }
                
                echo "
                <div class='col-xs-12 col-sm-6 col-lg-3'>
                    <div class='thumbnail baller-image'>
                        ".$image."
                        <div class='caption'>
                            ".(($p_review == "" OR $p_review == "NOK") ? "<div class='checkbox'>
                                <label for='bimg-hide-".$p_id."'>
                                    <input type='checkbox' id='bimg-hide-".$p_id."' name='bimg-hide-".$p_id."' value='NOK' ".(($p_review == "" OR $p_review == "NOK") ? "checked" : "")."/> 
                                    <span class='glyphicon glyphicon-eye-close'></span> Peida see pilt
                                </label>
                            </div>
                            <div class='hidden'>
                                <input type='hidden' id='bimg-x-".$p_id."' name='bimg-x-".$p_id."' />
                                <input type='hidden' id='bimg-y-".$p_id."' name='bimg-y-".$p_id."' />
                                <input type='hidden' id='bimg-w-".$p_id."' name='bimg-w-".$p_id."' />
                                <input type='hidden' id='bimg-h-".$p_id."' name='bimg-h-".$p_id."' />
                                
                                <input type='hidden' id='bimg-uiw-".$p_id."' name='bimg-uiw-".$p_id."' />
                            </div>"
                            : 
                            "<p>".$p_review."</p>
                            <p class='text-right'>
                                <button type='submit' class='btn btn-default' name='trashcropped' value='".$p_id."'>
                                    <span class='glyphicon glyphicon-trash'></span> Kustuta lõige
                                </button>
                            </p>")."
                        </div>
                    </div>
                </div>";
                $prc++;
                if ($prc % 4 == 0 AND $prc % 2 == 0) {
                    echo "
                <div class='clear visible-sm visible-lg'></div>";
                } else if ($prc % 2 == 0) {
                    echo "
                <div class='clear visible-sm'></div>";
                }
            }
            echo "
            </div>
            <div class='hidden'>
                <input type='hidden' name='cropids' value='".implode("|", $bimgs)."' />
            </div>
            <div class='text-right'>
                <button type='submit' class='btn btn-default' name='cropimages'>
                    <span class='glyphicon glyphicon-floppy-save'></span> Salvesta
                </button>
            </div>
        </form>";
        }
        
        echo "<div class='clear voffset4'></div>";
    } else {
        echo "
        <h2 class='voffsete3'>Sellist inimest ei leitud...</h2>";
    }
}

//Pagination
$pgn = 1;
if (isset($_GET["page"]) AND is_numeric($_GET["page"]) AND $_GET["page"] > 0) {
    $pgn = $_GET["page"];
}
$PAGE_SIZE = 25;

//Content DOM;
$content = "";

//SQL
//nreviewed DESC,
$aQuery = "SELECT b.id, b.cms_alias, b.name, b.rank, SUM(IF(p.review IS NULL, 1, 0)) AS nreviewed, SUM(IF(p.review IS NOT NULL, 1, 0)) AS reviewed FROM baller b LEFT JOIN ballerpicture p ON b.id=p.baller_id WHERE b.rank > 0 GROUP BY b.id ORDER BY b.rank ASC LIMIT " . ($pgn - 1) * $PAGE_SIZE . ", " . ($PAGE_SIZE + 1);
if ($debug) {
    echo "<pre>";
    echo $aQuery;
    echo "</pre>";
}
$bpicSQL = mysqli_query($db, $aQuery);

$nrows = mysqli_num_rows($bpicSQL);

if ($nrows > 0) {
    $rc = 0;
    while(list($b_id, $b_cms, $b_name, $b_rank, $b_nrev, $b_rev) = mysqli_fetch_row($bpicSQL)) {
        if ($rc >= $PAGE_SIZE) break;
        $content .= "
            <tr>
                <td>
                    <a href='".$baseURL.$page."/".$b_id."-".$b_cms."/?page=".$pgn."'>".$b_name.($b_rank > 0 ? " (#".$b_rank.")":"")."</a>
                </td>
                <td>".($b_rev > 0 ? "<span class='color-green'>" . $b_rev . "</span>" : $b_rev ) . ($b_nrev > 0 ? " / <span class='strong color-red'>".$b_nrev."</span>" : ""). "</td>
            </tr>";
        $rc++;
    }
} else {
    $content .= "
            <tr>
                <td colspan='2' class='text-center'>Päring ei andnud vastuseid!</td>
            </tr>";
}

$pager = "
    <nav>
        <ul class='pager voffsete1'>
            <li class='previous".($pgn <= 1 ? " disabled" : "")."'>
                <a href='".$baseURL.$page."/?page=".($pgn - 1)."'>&laquo; Eelmine</a>
            </li>
            <li class='next".($nrows < ($PAGE_SIZE + 1) ? " disabled" : "")."'>
                <a href='".$baseURL.$page."/?page=".($pgn + 1)."'>Järgmine &raquo;</a>
            </li>
        </ul>
    </nav>";

?>
<div class="table-responsive">
    <?php 
    echo 
    $pager;
    ?>
    <table class="table table-striped voffsete0">
        <thead>
            <tr>
                <th>Pallur (rank)</th>
                <th>Pildid (OK / TBD)</th>
            </tr>
        </thead>
        <tbody>
        <?php
            echo 
            $content;
        ?>
        </tbody>
    </table>
    <?php 
    echo 
    $pager;
    ?>
</div>

<script>

// Update inputs on imgAreaSelect
function setInfo(i, e) {
    var elID = $(i).attr("id").split("-")[1];
    if (e.width > 0 && e.height > 0) {
        $("#bimg-hide-" + elID).prop("checked", false);
        
        $("#bimg-x-" + elID).val(e.x1);
        $("#bimg-y-" + elID).val(e.y1);
        $("#bimg-w-" + elID).val(e.width);
        $("#bimg-h-" + elID).val(e.height);
        $("#bimg-uiw-" + elID).val($(i).width());
    } else {
        $("#bimg-hide-" + elID).prop("checked", true);
    }
}

$(document).ready(function () {
    // Disabled pager click
    $(".pager li.disabled").on("click", "a", function (e) {
        e.preventDefault();
    });
    
    // Make images resizeable
    // http://odyniec.net/projects/imgareaselect/usage.html
    $(".baller-image-img").each(function (index, element) {
        $(element).imgAreaSelect({
            aspectRatio: '1:1', // Crop ratio (w:h) 1:1
            onSelectEnd: setInfo // Update required inputs
        });
    });
});




</script>