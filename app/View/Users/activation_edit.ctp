<div class="users form container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Edit User'); ?></h1>
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

			    <li><?php echo $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete'), array('action' => 'activation_delete', $this->Form->value('User.id')), array('escape' => false), __('Are you sure you want to delete # %s?', $this->Form->value('User.id'))); ?></li>
			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Users'), array('action' => 'activation_index'), array('escape' => false)); ?></li>

			</ul>
		    </div>
		</div>
	    </div>			
	</div><!-- end col md 3 -->
	<div class="col-md-9">
	    <?php
	    echo $this->Form->create('User', array('inputDefaults' => array(
		    'div' => 'form-group',
		    'wrapInput' => true,
		    'class' => 'form-control'
	    )));
	    ?>

	    <div class="form-group">
		<?php echo $this->Form->input('id', array('class' => 'form-control', 'placeholder' => 'Id')); ?>
		<?php echo $this->Form->input('email', array('class' => 'form-control', 'placeholder' => 'Email')); ?>
		<?php echo $this->Form->input('pwd', array('label' => __('Password'))); ?>		
		<?php echo $this->Form->input('name', array('class' => 'form-control', 'placeholder' => 'Name')); ?>		
		<?php echo $this->Form->input('contact_number', array('class' => 'form-control', 'placeholder' => 'Contact Number')); ?>		
		<?php echo $this->Form->input('role_id', array('class' => 'form-control', 'placeholder' => 'Role Id', 'label' => 'Group')); ?>
		
	    </div>
	    <div class="form-group">
		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
	    </div>

	    <?php echo $this->Form->end() ?>

	</div><!-- end col md 12 -->
    </div><!-- end row -->
</div>
