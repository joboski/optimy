<h2>Create an Account</h2>

<?php $form = optimy\app\core\form\Form::instance() ?>
	
<?php echo $form->begin("", "post") ?>

<div class="row">
	<div class="col"><?php echo $form->field($model, "firstname") ?></div>
	<div class="col"><?php echo $form->field($model, "lastname"); ?></div>
</div>

<?php echo $form->field($model, "email"); ?>
<?php echo $form->field($model, "password"); ?>
<?php echo $form->field($model, "confirmPassword"); ?>

<button type="submit" class="btn btn-primary">Submit</button>
<a class="btn btn-outline-danger" href="#">Cancel</a>

<?php $form->end();?>

<!-- <div class="content">
    <form method="post" enctype="multipart/form-data" action="/register">

    	<div class="form-group">
            <label>Email <small>This will be your username</small></label>
            <input type="email" name="email" class="form-control">
        </div>
    	<div class="row">
    		<div class="col">
    			<div class="form-group">
            		<label>Firstname</label>
            		<input type="text" name="firstname" class="form-control">
        		</div>
    		</div>
    		<div class="col">
    			<div class="form-group">
		            <label>Lastname</label>
		            <input type="text" name="lastname" class="form-control">
		        </div>
    		</div>
    	</div>
        <div class="row">
        	<div class="col">
        		<div class="form-group">
            		<label>Password</label>
            		<input type="password" name="password" class="form-control">
        		</div>
        	</div>
        	<div class="col">
        		<div class="form-group">
		            <label>Confirm Password</label>
		            <input type="password" name="confirmPassword" class="form-control">
		        </div>
        	</div>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
        <a class="btn btn-outline-danger" href="#">Cancel</a>
    </form>
</div> -->

