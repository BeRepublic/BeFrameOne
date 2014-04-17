<div class="container">
    <?php if(($language->id)): ?>
        <h1>Modify language <?php echo $language->id; ?></h1>
    <?php else: ?>
        <h1>New language</h1>
    <?php endif; ?>
    <p><a href="<?php echo $App->getWebsite(); ?>/admin/language.html"><span class="glyphicon glyphicon-arrow-left "></span> Volver</a></p>
    <form role="form" method="post" action="<?php echo $App->getWebsite(); ?>/admin/language_form.html">
    
    	<input type="hidden" name="id" value="<?php if($language->id) echo $language->id; ?>">
    	
        <div class="form-group <?php if(isset($errors['name'])):?>has-error<?php endif; ?>">
            <label class="control-label" for="name">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Introduce el nombre" name="name" value="<?php if($language->name) echo $language->name; ?>">
        </div>
        
        <div class="form-group">
            <label class="control-label <?php if(isset($errors['iso'])):?>has-error<?php endif; ?>" for="iso">ISO</label>
            <input type="text" class="form-control" id="iso" placeholder="Introduce el ISO" name="iso" value="<?php if($language->iso) echo $language->iso; ?>">
        </div>
        
        <?php
         if($errors)
        	foreach ($errors as $field=>$error)
        		echo '<div class="error">'.$error.'</div>';
         ?>
        
        <button type="submit" class="btn btn-default btn-submit">Submit</button>
    </form>

</div>