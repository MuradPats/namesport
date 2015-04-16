<?php

// Single person OR table
if (isset($UE[2]) AND !empty($UE[2])) {
    
    $uexp = explode("-", $UE[2]);
    
    
    $ballerSQL = mysqli_query($db, "SELECT id, name FROM baller WHERE id=".$uexp[0]);
    $nbrows = mysqli_num_rows($ballerSQL);
    
    if($nbrows == 1) {
        //Picture location
        $orig_img_url = "images/";
        $crop_img_url = "cropped/";
        
        $ballerData = mysqli_fetch_assoc($ballerSQL);
        
        echo "<h2>".$ballerData["name"]."</h2>";
        
        $ballerPicSQL = mysqli_query($db, "SELECT id, file_name, review FROM ballerpicture WHERE baller_id=".$uexp[0]);
        
        $nprows = mysqli_num_rows($ballerPicSQL);
        
        echo "<p>Leidsin ".$nprows.($nprows == 1 ? " pildi" : " pilti")."</p>";
        
        if ($nprows > 0) {
            echo "<div class='row baller-images voffsete1'>";
            $prc = 0;
            while(list($p_id, $p_file, $p_review)=mysqli_fetch_row($ballerPicSQL)) {
                echo "
                <div class='col-xs-12 col-sm-6 col-lg-3'>
                    <div class='thumbnail baller-image'>
                        <img src='".$baseURL.$orig_img_url.$p_file."' alt='".$ballerData["name"]."'>
                        <div class='caption'>
                            <p>".$p_review."</p>
                            <p>".$p_file."</p>
                        </div>
                    </div>
                </div>";
                $prc++;
                if ($prc % 4 == 0 AND $prc % 2 == 0) {
                    echo "<div class='clear visible-sm visible-lg'></div>";
                } else if ($prc % 2 == 0) {
                    echo "<div class='clear visible-sm'></div>";
                }
            }
            echo "</div>";
        }
        
        echo "<div class='clear voffset4'></div>";
    } else if ($nbrows > 1) {
        error_log("MAJOR FUCKUP! multiple baller cms_alias-s for " . $UE[2]);
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
$bpicSQL = mysqli_query($db, "SELECT b.id, b.cms_alias, b.name, b.rank, SUM(IF(p.review IS NULL, 1, 0)) AS nreviewed, SUM(IF(p.review IS NOT NULL, 1, 0)) AS reviewed FROM baller b LEFT JOIN ballerpicture p ON b.id=p.baller_id GROUP BY b.id ORDER BY nreviewed DESC, -b.rank DESC LIMIT " . ($pgn - 1) * $PAGE_SIZE . ", " . ($PAGE_SIZE + 1));

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
$(document).ready(function () {
    $(".pager li.disabled").on("click", "a", function (e) {
        e.preventDefault();
    });
});
</script>