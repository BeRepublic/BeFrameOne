<div class="container">

    <form class="form-signin" role="form" method="post" action="<?php echo $App->getWebsite(); ?>/admin/language.html">
        <img class="logo-login" src="<?php echo $App->getWebsite(); ?>/admin/img/logo.png" />
        <input type="hidden" name="_token" value="<?php echo $token; ?>" />
        <?php if( isset($error_ip) ) echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$error_ip.'</div>'; ?>
        <input type="text" class="form-control" placeholder="Usuario" required autofocus name="_user" />
        <input type="password" class="form-control" placeholder="Contraseña" required name="_pass"  />
        <button class="btn btn-lg btn-primary btn-block btn-submit" type="submit">Entrar</button>
    </form>

</div>