<!DOCTYPE html>
<html lang="en">
    <head>
	
	<title>
	    AXXIS
	    <?php //echo $title_for_layout; ?>
	</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<?php
	echo $this->Html->meta(
	'favicon.png',
	'favicon.png',
	array('type' => 'icon')
	);?>
		
	<?php echo $this->AssetCompress->css('all', array('raw' => true)); ?>
	<?php echo $this->AssetCompress->script('all', array('raw' => true));?> 
	
	<?php //echo $this->AssetCompress->css('all'); ?>
	<?php //echo $this->AssetCompress->script('all'); // can't put it at end of page, because some datatable pages depend on this'?> 
	

	
	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('dataTableSettings');
	echo $this->fetch('script');
	?>

    </head>

    <body>

	<?php if ((isset($disable_menu) && $disable_menu == false) || !isset($disable_menu)):?>
		<?php echo $this->Element('loggedin_menu'); ?>
	<?php endif;?>

	<div>

	    <?php echo $this->Session->flash(); ?>

	    <?php echo $this->fetch('content'); ?>

	</div><!-- /.container -->	

	<!-- FOOTER -->
	<?php if ((isset($disable_footer) && $disable_footer == false) || !isset($disable_footer)):?>
	<br>
	<div class="footer">
	    <div class="container">
		<br>
		<p class="pull-right"><a href="#">Back to top</a></p>
		<p>&copy; AXXIS &middot;
	    </div>
	</div> 
	<?php endif;?>
	
	<?php echo $this->Js->writeBuffer(); ?>

    </body>



</html>
