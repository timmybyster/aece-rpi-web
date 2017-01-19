<div class="warnings form container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Add Warning'); ?></h1>
	    </div>
	</div>
    </div>



    <div class="row">
	<div class="col-md-3">
	    <div class="actions">
		<div class="panel panel-default">
		    <div class="panel-heading">Actions</div>
		    <div class="panel-body">
			<ul class="nav nav-pills nav-stacked">

			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Warnings'), array('action' => 'index'), array('escape' => false)); ?></li>

			</ul>
		    </div>
		</div>
	    </div>			
	</div><!-- end col md 3 -->
	<div class="col-md-9">
	    <?php
	    echo $this->Form->create('Warning', array('inputDefaults' => array(
		    'div' => 'form-group',
		    'wrapInput' => true,
		    'class' => 'form-control'
	    )));
	    ?>

	    <div class="form-group">
		<?php echo $this->Form->input('message', array('class' => 'form-control', 'placeholder' => 'Message')); ?>
		<?php echo $this->Form->input('acknowledged', array('class' => 'form-control', 'placeholder' => 'Acknowledged')); ?>
		<?php echo $this->Form->input('user_id', array('class' => 'form-control', 'placeholder' => 'User Id')); ?>
	    </div>
	    <div class="form-group">
		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
	    </div>

	    <?php echo $this->Form->end() ?>

	</div><!-- end col md 12 -->
    </div><!-- end row -->
</div>
