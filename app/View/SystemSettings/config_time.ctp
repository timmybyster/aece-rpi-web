<?php echo $this->Html->script('jquery-ui-timepicker-addon'); ?>
<?php echo $this->Html->css('jquery-ui-timepicker-addon'); ?>

<script>
    $(document).ready(function() {	
	$('#SystemSettingsTime').datetimepicker({
	    timeFormat: "HH:mm:ss",
	    dateFormat: "yy-mm-dd",
	});
    });
</script>

<div class="warnings form container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('System Time'); ?></h1>
	    </div>
	</div>
    </div>


    <div class="row">

	<div class="col-md-3">
	    <?php
	    echo $this->Form->create('SystemSettings', array('inputDefaults' => array(
		    'div' => 'form-group',
		    'wrapInput' => true,
		    'class' => 'form-control'
	    )));
	    ?>

	    <div class="form-group">
		<?php echo $this->Form->input('time', array('class' => 'form-control', 'placeholder' => 'Time', 'label' => 'Current System Time', 'default' => $time)); ?>
	    </div>
	    <div class="form-group">
		<?php echo $this->Form->submit('Submit', array('div' => 'form-group', 'class' => 'btn btn-primary')); ?>
	    </div>

	    <?php echo $this->Form->end() ?>

	</div><!-- end col md 12 -->
    </div><!-- end row -->
</div>
