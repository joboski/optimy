
<h2 class="title"> Tell your story </h2>
<?php $form = optimy\app\core\form\Form::instance() ?>
    
<?php echo $form->begin("", "post") ?>

<?php echo $form->field($model, "title")->cssMargin(); ?>
<?php echo $form->textarea($model, "content"); ?>
<?php echo $form->select($model, "category", ["foods", "sports", "places", "people"]); ?>

<div class="input-group margin-tb">
  <div class="custom-file">
    
    <?php echo $form->file($model, "filename"); ?>

    <label class="custom-file-label" for="filename">Choose file</label>
  </div>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
<a class="btn btn-outline-danger" href="/">Cancel</a>

<?php $form->end();?>
