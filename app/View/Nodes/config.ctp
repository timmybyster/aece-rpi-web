<?php echo $this->Html->css('ajax-multi-upload'); ?>

<div class="nodes config container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Status Page Config'); ?></h1>
	    </div>
	</div><!-- end col md 12 -->
    </div><!-- end row -->

    <div class="row">
	<div class="col-md-3">
	    <h2><?php echo __('Warnings'); ?></h2>
	    <?php
	    echo "<br>";
	    echo $this->Form->create('WarningConfig', array('url' => array('controller' => 'nodes', 'action' => 'config_warning'),
		'inputDefaults' => array(
		    'div' => 'form-group',
		    'wrapInput' => false,
		    'class' => 'form-control'
		)
	    ));
	    echo $this->Form->input('dismiss_delay', array('type' => 'number', 'min' => '5', 'placeholder' => 'Delay', 'label' => 'Delay in seconds warning should show again after dismissal', 'default' => $warning_dismiss_delay));
	    echo $this->Form->submit('Save', array('div' => 'form-group', 'class' => 'btn btn-primary'));
	    echo $this->Form->end();
	    ?>
	</div>
    </div>

    <h2><?php echo __('Images'); ?></h2>
    <p>Please use the correct image sizes as indicated in brackets</p>
    <br>
    <div class="row">	

	<div class="col-md-3">
	    <?php
	    //echo $this->Upload->edit('Config', "", true, true);
	    $options = array();
	    $options['allowed_extensions'] = array('jpg', 'png', 'jpeg');
	    $options['use_bootstrap'] = true;

	    echo '<b>BC-1</b> (128x128)';
	    echo $this->Upload->oneFileUpload('statusPage', 'BC-1', null, $options);

	    echo '<b>ISC-1</b> (128x128)';
	    echo $this->Upload->oneFileUpload('statusPage', 'ISC-1', null, $options);

	    echo '<b>IB651</b> (128x128)';
	    echo $this->Upload->oneFileUpload('statusPage', 'IB651', null, $options);
	    ?>
	</div>
	<div class="col-md-3">
	    <?php
	    echo '<b>Background image</b>';
	    echo $this->Upload->oneFileUpload('statusPage', 'background', null, $options);

	    echo '<b>Key Switch: Armed</b> (32x32)';
	    echo $this->Upload->oneFileUpload('statusPage', 'KeySwitchArmed', null, $options);

	    echo '<b>Key Switch: Disarmed</b> (32x32)';
	    echo $this->Upload->oneFileUpload('statusPage', 'KeySwitchDisarmed', null, $options);
	    ?>
	</div>
	<div class="col-md-3">
	    <?php
	    echo '<b>Detonator: Connected</b> (32x32)';
	    echo $this->Upload->oneFileUpload('statusPage', 'DetonatorConnected', null, $options);

	    echo '<b>Detonator: Not Connected</b> (32x32)';
	    echo $this->Upload->oneFileUpload('statusPage', 'DetonatorNotConnected', null, $options);
	    ?>
	</div>

    </div>

    <h2><?php echo __('Background Image'); ?></h2>   
    <p>Additional Background Image settings</p>
    <div class="row">	

	<div class="col-md-3">
	    <?php
	    echo "<br>";
	    echo $this->Form->create('BackgroundConfig', array('url' => array('controller' => 'nodes', 'action' => 'config_backgroundimg'),
		'inputDefaults' => array(
		    'div' => 'form-group',
		    'wrapInput' => false,
		    'class' => 'form-control'
		)
	    ));
	    echo $this->Form->input('background_image_contrast', array('type' => 'number', 'min' => '1', 'max' => '255', 'placeholder' => 'Contrast', 'default' => $background_image_contrast));
	    echo $this->Form->input('background_image_size_multiply', array('type' => 'number', 'min' => '1', 'max' => '5', 'placeholder' => 'Size Multiply', 'default' => $background_image_size_multiply));
	    echo $this->Form->submit('Save', array('div' => 'form-group', 'class' => 'btn btn-primary'));
	    echo $this->Form->end();
	    ?>
	</div>


    </div>	


</div><!-- end row -->


</div><!-- end containing of content -->