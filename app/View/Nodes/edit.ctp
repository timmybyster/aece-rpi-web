<div class="nodes form container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Edit Node'); ?></h1>
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
				<?php if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['service']): ?>
			    <li><?php echo $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete'), array('action' => 'delete', $this->Form->value('Node.id')), array('escape' => false), __('Are you sure you want to delete # %s?', $this->Form->value('Node.id'))); ?></li>
			    <?php endif; ?>
				<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Nodes'), array('action' => 'index'), array('escape' => false)); ?></li>

			</ul>
		    </div>
		</div>
	    </div>			
	</div><!-- end col md 3 -->
	<div class="col-md-9">
	    <?php
	    echo $this->Form->create('Node', array('inputDefaults' => array(
		    'div' => 'form-group',
		    'wrapInput' => true,
		    'class' => 'form-control'
	    )));
	    ?>

		<?php echo $this->Form->input('id', array('class' => 'form-control', 'placeholder' => 'Id')); ?>
		<?php if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['service']): ?>
		<?php echo $this->Form->input('parent_id', array('class' => 'form-control', 'placeholder' => 'Parent')); ?>
		<?php echo $this->Form->input('serial', array('class' => 'form-control', 'placeholder' => 'Serial')); ?>
		<?php echo $this->Form->input('id', array('class' => 'form-control', 'placeholder' => 'Id')); ?>
		<?php echo $this->Form->input('type_id', array('class' => 'form-control', 'placeholder' => 'Type')); ?>
		<?php echo $this->Form->input('key_switch_status', array('class' => 'form-control', 'placeholder' => 'Key Switch Status', 'options' => $key_switch_statuses)); ?>
		<?php echo $this->Form->input('communication_status', array('class' => 'form-control', 'placeholder' => 'Communication Status', 'options' => $communication_statuses)); ?>
		<?php echo $this->Form->input('blast_armed', array('class' => 'form-control', 'placeholder' => 'Blast Armed', 'options' => $blast_armed_enums)); ?>
		<?php echo $this->Form->input('detonator_status', array('class' => 'form-control', 'placeholder' => 'Detonator Status', 'options' => $detonator_statuses)); ?>
		<?php echo $this->Form->input('partial_blast_lfs', array('class' => 'form-control', 'placeholder' => 'Partial Blast Lfs', 'options' => $partial_blast_lfs_enum)); ?>
		<?php echo $this->Form->input('full_blast_lfs', array('class' => 'form-control', 'placeholder' => 'Full Blast Lfs', 'options' => $full_blast_lfs_enum)); ?>
		<?php echo $this->Form->input('booster_fired_lfs', array('class' => 'form-control', 'placeholder' => 'Booster Fired Lfs', 'options' => $booster_fired_lfs_enum)); ?>
		<?php echo $this->Form->input('missing_pulse_detected_lfs', array('class' => 'form-control', 'placeholder' => 'Missing Pulse Detected Lfs', 'options' => $missing_pulse_detected_lfs_enum)); ?>
		<?php echo $this->Form->input('AC_supply_voltage_lfs', array('class' => 'form-control', 'placeholder' => 'AC Supply Voltage Lfs')); ?>
		<?php echo $this->Form->input('DC_supply_voltage', array('class' => 'form-control', 'placeholder' => 'DC Supply Voltage')); ?>
		<?php endif; ?>
		<?php echo $this->Form->input('x', array('class' => 'form-control', 'placeholder' => 'X')); ?>
		<?php echo $this->Form->input('y', array('class' => 'form-control', 'placeholder' => 'Y')); ?>		
		<?php echo $this->Form->input('comment', array('class' => 'form-control', 'placeholder' => 'Comment')); ?>
	    </div>
	    <div class="form-group">
		<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
	    </div>

	    <?php echo $this->Form->end() ?>

	</div><!-- end col md 12 -->
    </div><!-- end row -->
</div>
