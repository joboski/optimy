<?php 
	use optimy\app\core\Application;
	use optimy\app\core\Helper;
?>
<?php if (empty($blogs)) { ?>
	<header class="jumbotron mt-4 mx-auto mb-12">
        <h1 class="display-3">Oops no entry for this type!</h1>
        <?php if (Application::$app->user) { ?>
        	<a class="btn btn-primary btn-lg mt-4" href="/blog">Create Article</a>
        <?php } else {?>  	
        	<a class="btn btn-primary btn-lg mt-4" href="/login">Please Login to create blogs</a>
        <?php } ?>
    </header>

<?php } else { ?>

<div class="spaces">
	<h3> Check out this amazing blogs </h3>
</div>
<div class="row">
<?php $index = 1; $prev = 1; foreach ($blogs as $blog) { ?>

	<div class="col-lg-3 col-md-6 mb-4 mt-2">
		<div class="card">
			<img class="card-img-top" src='<?php echo "/../assets/uploads/" .  $blog->filename ?>' alt="<?php echo $blog->filename?>">
			<div class="card-body">
			    <h5 class="card-title"><?php echo $blog->title ?></h5>
			    <p class="card-text short"><?php echo $blog->content ?></p>
			    <button class="btn btn-primary" data-toggle="modal" data-target="#modal<?php echo $index++ ?>">Find out more!</button>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modal<?php echo $prev; $prev = $index; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $blog->title ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<img class="img" src='<?php echo "/../assets/uploads/" .  $blog->filename ?>' alt="<?php echo $blog->filename?>" width=465>
	        <?php echo $blog->content ?>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
<?php } ?>

<?php } ?>
</div>

