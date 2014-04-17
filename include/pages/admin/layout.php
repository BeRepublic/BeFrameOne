<!DOCTYPE html>
<?php if (!defined('ENVIRONMENT')) die('Direct access not permitted'); ?>
<!--[if IE 8]>         <html class="no-js ie8"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BeFrameOne :: Admin</title>

    <!-- Bootstrap -->
    <link href="<?php echo $App->getWebsite(); ?>/admin/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $App->getWebsite(); ?>/admin/css/normalize-2.1.3.css">
    <link rel="stylesheet" href="<?php echo $App->getWebsite(); ?>/admin/css/smoothness/jquery-ui-1.10.3.custom.css">
    <link rel="stylesheet" href="<?php echo $App->getWebsite(); ?>/admin/css/jquery.multiselect.css">
    <link rel="stylesheet" href="<?php echo $App->getWebsite(); ?>/admin/css/jquery.multiselect.filter.css">
    <link href="<?php echo $App->getWebsite(); ?>/admin/css/main.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo $App->getWebsite(); ?>/admin/js/vendor/jquery-1.10.2.min.js"></script>
    <script src="<?php echo $App->getWebsite(); ?>/admin/js/vendor/modernizr-2.6.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo $App->getWebsite(); ?>/admin/js/vendor/bootstrap.min.js"></script>
    <script src="<?php echo $App->getWebsite(); ?>/admin/js/vendor/jquery.dataTables.js"></script>

    <script type="text/javascript" src="<?php echo $App->getWebsite(); ?>/admin/js/vendor/jquery-ui-1.10.3.custom.js"></script>
    <script type="text/javascript" src="<?php echo $App->getWebsite(); ?>/admin/js/i18n/jquery.ui.datepicker-es.js"></script>
    <script type="text/javascript" src="<?php echo $App->getWebsite(); ?>/admin/js/vendor/jquery.form-3.45.0.js"></script>
    <script type="text/javascript" src="<?php echo $App->getWebsite(); ?>/admin/js/vendor/jquery.validate-1.11.1.js"></script>
    <script type="text/javascript" src="<?php echo $App->getWebsite(); ?>/admin/js/vendor/jquery.multiselect.js"></script>
    <script type="text/javascript" src="<?php echo $App->getWebsite(); ?>/admin/js/vendor/jquery.multiselect.filter.js"></script>

    <script src="<?php echo $App->getWebsite(); ?>/admin/js/main.js"></script>
    <script src="<?php echo $App->getWebsite(); ?>/admin/js/datepicker_custom.js"></script>
    <script src="<?php echo $App->getWebsite(); ?>/admin/js/validate_custom.js"></script>
</head>
<body>
<?php echo $_header ?>
<?php echo $_content; ?>
</body>
</html>