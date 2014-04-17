<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js ie8"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $App->translate('title', 'all'); ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>
        
        <!-- Header -->

    	<header>
    		<h4><?php echo $_currentPage;?></h4>
    		<div>
    			<?php 
    			foreach ($_languages as $k => $lang){
					echo ' <a href="/'.$k.'">'.$lang.'</a> ';
				}	
    			?>
    		</div>
    		
    		<div class="row">
                <div class="page">
					<ul id="nav" class="nav-menu">
						<li><a class="enlace" href="<?php echo $App->url('ismael');?>" title="">Ismael</a></li>
						<li><a class="enlace" href="<?php echo $App->url('faq');?>" title="">Faq</a></li>
					</ul>
                </div>
    		</div>
    	</header>

        <!-- End Header -->

        <!-- CONTENT -->
        
        <?php echo $_content; ?>
        
        <!-- END CONTENT -->

        <!-- Footer -->

        <footer>
       
        </footer>

        <!-- End Footer -->

    </body>
</html>