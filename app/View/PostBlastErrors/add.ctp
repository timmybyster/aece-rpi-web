<div class="postBlastErrors form container">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo __('Add Post Blast Error'); ?></h1>
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

																<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Post Blast Errors'), array('action' => 'index'), array('escape' => false)); ?></li>
	
							</ul>
						</div>
					</div>
				</div>			
		</div><!-- end col md 3 -->
		<div class="col-md-9">
			<?php echo $this->Form->create('PostBlastError', array('inputDefaults' => array(
				'div' => 'form-group',
				'wrapInput' => true,
				'class' => 'form-control'
				))); ?>

				<div class="form-group">
					<?php echo $this->Form->input('message', array('class' => 'form-control', 'placeholder' => 'Message'));?>
					<?php echo $this->Form->input('node_serial', array('class' => 'form-control', 'placeholder' => 'Node Serial'));?>
					<?php echo $this->Form->input('blast_time', array('class' => 'form-control', 'placeholder' => 'Blast Time'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
