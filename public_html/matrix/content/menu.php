<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#namesport-menu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo $baseURL; ?>">Namesport</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="namesport-menu">
            <ul class="nav navbar-nav">
                <?php
                foreach ($pages AS $mp) {
                    echo "
                        <li" . ($mp == $page ? " class='active'" : "") . ">
                            <a href='" . $baseURL . $mp . "/'>" . $mp . "</a>
                        </li>";
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo $baseURL; ?>?login=logout">Logi välja</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>