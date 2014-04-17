<nav class="navbar navbar-default sabadell-color" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img class="logo" src="<?php echo $App->getWebsite(); ?>/admin/img/logo_bar.png" />
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li <?php if($page == 'content' || $page == 'content_form'):?>class="active"<?php endif; ?>>
                    <a href="<?php echo $App->getWebsite(); ?>/admin/content.html">Contents</a>
                </li>
                <li <?php if($page == 'language' || $page == 'language_form'):?>class="active"<?php endif; ?>>
                    <a href="<?php echo $App->getWebsite(); ?>/admin/language.html">Languages</a>
                </li>
                <li><a href="<?php echo $App->getWebsite(); ?>/admin/logout">Logout</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
