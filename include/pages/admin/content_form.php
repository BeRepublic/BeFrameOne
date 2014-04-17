<?php 
// Some ussed values in tis view.
$translations = $content->getTranslation();

?>

<div class="container">
    <?php if(isset($content->id)): ?>
        <h1>Content: <?php echo $content->name; ?> <small>(<?php echo $content->id; ?>)</small></h1>
    <?php else: ?>
        <h1>New Content</h1>
    <?php endif; ?>
    
    <p><a href="<?php echo $App->getWebsite(); ?>/admin/content.html"><span class="glyphicon glyphicon-arrow-left "></span> Return</a></p>
    <form role="form" enctype="multipart/form-data" method="post" action="<?php echo $App->getWebsite(); ?>/admin/content_form.html?id=<?php if($content->id) echo $content->id; ?>">
    	<input type="hidden" name="content[id]" value="<?php echo $content->id; ?>">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">General Data</h3>
            </div>
            <div class="panel-body">

                <div class="form-group <?php if(isset($errors['name'])):?>has-error<?php endif; ?>">
                    <label class="control-label" for="name">name</label>
                    <input <?php if(isset($content->id)): ?>readonly="readonly" <?php endif; ?> type="text" class="form-control" id="name" placeholder="Enter a name" name="content[name]" value="<?php if($content->name) echo $content->name; ?>">
                </div>
                
                <div class="form-group <?php if(isset($errors['template'])):?>has-error<?php endif; ?>">
                    <label class="control-label" for="template">Template</label>
                    <select class="form-control" name="content[template]">
                    	<?php 
                    	foreach ($content->getTemplates() as $tpl){
							$selected = ($content->template==$tpl) ? 'selected' : '';
							echo '<option '.$selected.' value="'.$tpl.'">'.$tpl.'</option>';	
						}
                    	?>
                    </select>
                </div>
                
                <div class="form-group <?php if(isset($form_error['status'])):?>has-error<?php endif; ?>">
                    <label class="control-label" for="status1">Estado</label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="content[status]" value="draft" <?php if(!$content->status || $content->status == 'draft'): ?> checked <?php endif; ?>>
                            Draft
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="content[status]" value="active" <?php if($content->status && $content->status == 'active'): ?> checked <?php endif; ?>>
                            Active
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="content[status]" value="winners"  <?php if($content->status && $content->status == 'trash'): ?> checked <?php endif; ?>>
                            Trash
                        </label>
                    </div>
                </div>
                <div class="form-group <?php if(isset($form_error['languages'])):?>has-error<?php endif; ?>">
                    <label class="control-label" for="languages">Idiomas</label>
                    <select name="languages[]" multiple="multiple">
                        <?php foreach($languages as $lang):
                            $selected = '';
                                foreach($translations as $translation)
                                    if($lang->id == $translation->language_id)
                                        $selected = 'selected';
                            ?>
                            <option <?php echo $selected ?> value="<?php echo $lang->id; ?>"><?php echo $lang->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
            </div>
        </div>

        <?php 
        $cnt = 0;
        if(!empty($translations)): ?>
        <ul class="nav nav-tabs">
        <?php foreach($translations as $iso=>$translation):?>
            <li <?php if($cnt==0):?>class="active"<?php endif; $cnt++ ?>><a data-toggle="tab" href="#lang-<?php echo $iso; ?>"><?php echo $iso; ?></a></li>
        <?php endforeach; ?>
        </ul>

        <div class="tab-content" id="myTabContent">
        <?php 
        $cnt = 0;
        // URL TITLE BODY
        foreach($translations as $iso=>$translation): ?>
            <div id="lang-<?php echo $iso; ?>" class="tab-pane fade <?php if($cnt==0):?>active in<?php endif; $cnt++ ?>">
            	<input type="hidden" name="contentTranslation[<?php echo $iso ?>][id]" value="<?php echo $translation->id ?>">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Content <?php echo $iso ?></h3>
                    </div>
                    <div class="panel-body">
                    	<?php if(isset($errors['contentTranslation'][$iso])):?>
                    	<div class="form-group has-error">
                            <?php
								foreach ($errors['contentTranslation'][$iso] as $err) {
									echo '<h5 class="error">'.$err.'</h4>';
								}
                            ?>
                        </div>
                    	<?php endif; ?>
                    	
                        <div class="form-group <?php if(isset($errors['contentTranslation'][$iso]['url'])):?>has-error<?php endif; ?>">
                            <label class="control-label" for="position">URL</label>
                            <input type="number" class="form-control" placeholder="some/link/you/like" name="contentTranslation[<?php echo $iso ?>][url]" value="<?php echo $translation->url ?>">
                        </div>

                        <p class="textBlue">Html</p>
                        
                        <div class="form-group <?php if(isset($errors['contentTranslation'][$iso]['title'])):?>has-error<?php endif; ?>">
                            <label class="control-label" for="page_title">Title</label>
                            <input type="text" class="form-control" placeholder="Page Title" name="contentTranslation[<?php echo $iso ?>][title]" value="<?php echo $translation->title ?>">
                        </div>         
                        
                        <div class="form-group <?php if(isset($errors['contentTranslation'][$iso]['body'])):?>has-error<?php endif; ?>">
                            <label class="control-label" for="legal">Body</label>
                            <textarea class="form-control" name="contentTranslation[<?php echo $iso ?>][body]"><?php echo $translation->body ?></textarea>
                        </div>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-default btn-submit">Submit</button>
    </form>
</div>