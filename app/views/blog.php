<?php 
use optimy\app\core\Application;
use optimy\app\core\Helper;

$path = Application::$app->request->path(); ?>

<h2 class="title"> Tell your story </h2>
<?php $form = optimy\app\core\form\Form::instance() ?>
    
<?php echo $form->begin($path === "/blog/update" ? $path : "", "post") ?>
<?php echo $form->field($model, "id")->fieldType("hidden")->cssStyle("no-display"); ?>
<?php echo $form->field($model, "userid")->fieldType("hidden")->cssStyle("no-display"); ?>
<?php echo $form->field($model, "title"); ?>
<?php echo $form->textarea($model, "content"); ?>
<?php echo $form->select($model, "category", ["foods", "sports", "places", "people"]); ?>

<div class="input-group margin-tb">
	<div class="custom-file">   
    	<?php echo $form->file($model, "filename"); ?>
		<label class="custom-file-label" for="filename">Choose file</label>
	</div>
</div>
<?php if (Application::$app->request->path() === "/blog/update") { ?>
	<button type="submit" class="btn btn-primary">Update</button>
<?php } else { ?>
	<button type="submit" class="btn btn-primary">Submit</button>
<?php } ?>
<a class="btn btn-outline-danger" href="/">Cancel</a>

<?php $form->end();?>
