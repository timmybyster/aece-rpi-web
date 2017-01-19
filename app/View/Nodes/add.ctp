<div class="nodes form container">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo __('Add Node'); ?></h1>
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

																<li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Nodes'), array('action' => 'index'), array('escape' => false)); ?></li>
	
							</ul>
						</div>
					</div>
				</div>			
		</div><!-- end col md 3 -->
		<div class="col-md-9">
			<?php echo $this->Form->create('Node', array('inputDefaults' => array(
				'div' => 'form-group',
				'wrapInput' => true,
				'class' => 'form-control'
				))); ?>

				<div class="form-group">
					<?php echo $this->Form->input('x', array('class' => 'form-control', 'placeholder' => 'X'));?>
					<?php echo $this->Form->input('y', array('class' => 'form-control', 'placeholder' => 'Y'));?>
					<?php echo $this->Form->input('serial', array('class' => 'form-control', 'placeholder' => 'Serial'));?>
					<?php echo $this->Form->input('type', array('class' => 'form-control', 'placeholder' => 'Type'));?>
					<?php echo $this->Form->input('key_switch_status', array('class' => 'form-control', 'placeholder' => 'Key Switch Status'));?>
					<?php echo $this->Form->input('communication_status', array('class' => 'form-control', 'placeholder' => 'Communication Status'));?>
					<?php echo $this->Form->input('temperature', array('class' => 'form-control', 'placeholder' => 'Temperature'));?>
					<?php echo $this->Form->input('blast_armed', array('class' => 'form-control', 'placeholder' => 'Blast Armed'));?>
					<?php echo $this->Form->input('fire_button', array('class' => 'form-control', 'placeholder' => 'Fire Button'));?>
					<?php echo $this->Form->input('isolation_relay', array('class' => 'form-control', 'placeholder' => 'Isolation Relay'));?>
					<?php echo $this->Form->input('cable_fault', array('class' => 'form-control', 'placeholder' => 'Cable Fault'));?>
					<?php echo $this->Form->input('earth_leakage', array('class' => 'form-control', 'placeholder' => 'Earth Leakage'));?>
					<?php echo $this->Form->input('detonator_status', array('class' => 'form-control', 'placeholder' => 'Detonator Status'));?>
					<?php echo $this->Form->input('partial_blast_lfs', array('class' => 'form-control', 'placeholder' => 'Partial Blast Lfs'));?>
					<?php echo $this->Form->input('full_blast_lfs', array('class' => 'form-control', 'placeholder' => 'Full Blast Lfs'));?>
					<?php echo $this->Form->input('booster_fired_lfs', array('class' => 'form-control', 'placeholder' => 'Booster Fired Lfs'));?>
					<?php echo $this->Form->input('missing_pulse_detected_lfs', array('class' => 'form-control', 'placeholder' => 'Missing Pulse Detected Lfs'));?>
					<?php echo $this->Form->input('AC_supply_voltage_lfs', array('class' => 'form-control', 'placeholder' => 'AC Supply Voltage Lfs'));?>
					<?php echo $this->Form->input('DC_supply_voltage', array('class' => 'form-control', 'placeholder' => 'DC Supply Voltage'));?>
					<?php echo $this->Form->input('DC_supply_voltage_status', array('class' => 'form-control', 'placeholder' => 'DC Supply Voltage Status'));?>
					<?php echo $this->Form->input('mains', array('class' => 'form-control', 'placeholder' => 'Mains'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
