<div class="wrapper fadeInDown">
  <div id="formContent">
		<?php $form = optimy\app\core\form\Form::instance() ?>
		    
		<?php echo $form->begin("", "post") ?>

		<?php echo $form->field($model, "title"); ?>
		<?php echo $form->textarea($model, "content"); ?>
		<?php echo $form->select($model, "category", ["foods", "sports", "places", "people"]); ?>

		<div class="input-group bottom_space">
		  <div class="custom-file">
		    <!-- <input type="file" class="custom-file-input" id="filename" name="filename" value=<?php echo $model->title; ?>> -->
		    <?php echo $form->file($model, "filename"); ?>

		    <label class="custom-file-label" for="filename">Choose file</label>
		  </div>
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
		<a class="btn btn-outline-danger" href="/">Cancel</a>

		<?php $form->end();?>
	</div>
</div>