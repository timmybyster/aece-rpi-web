
<script>
    $(document).ready(function() {	
	$('#SystemSettingUseDhcp').change(function() {
	    if($(this).is(":checked")) {		
		$('#div_non_dhcp').hide();
	    }else{
		$('#div_non_dhcp').show();
	    }
	});
	$('#SystemSettingUseDhcp').change(); // call so that the boxes could be hidden if in DHCP mode from load
    });
</script>

<div class="warnings form container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Network Configuration'); ?></h1>
	    </div>
	</div>
    </div>

    <?php
    echo $this->Form->create('SystemSetting', array('inputDefaults' => array(
	    'div' => 'form-group',
	    'wrapInput' => true,
	    'class' => 'form-control'
    )));
    ?>

    <div class="form-group">
	<div class="row">
	    <div class="col-md-2">
		<?php echo $this->Form->input('SSID', array('class' => 'form-control', 'label' => 'SSID')); ?>
		<?php echo $this->Form->input('encryption_type', array('class' => 'form-control', 'options' => $network_encryption_enum)); ?>
		<?php echo $this->Form->input('network_password', array('class' => 'form-control')); ?>
		<?php echo $this->Form->input('use_dhcp', array('class' => '', 'type' => 'checkbox', 'label' => 'Use DHCP')); ?>
	    </div>
	</div>
	<div class="row" id="div_non_dhcp">
	    <div class="col-md-2">
		<?php echo $this->Form->input('ip', array('class' => 'form-control', 'label' => 'IP')); ?>
	    </div>
	    <div class="col-md-2">
		<?php echo $this->Form->input('subnet_mask', array('class' => 'form-control')); ?>
	    </div>
	    <div class="col-md-2">
		<?php echo $this->Form->input('default_gateway', array('class' => 'form-control')); ?>
	    </div>
	</div>

	<div class="form-group">
	    <?php echo $this->Form->submit('Submit', array('div' => 'form-group', 'class' => 'btn btn-primary')); ?>
	</div>

	<?php echo $this->Form->end() ?>

	<!-- end col md 12 -->
    </div><!-- end row -->
</div>
