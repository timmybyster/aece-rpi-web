
<div class="logs config container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Logs Config'); ?></h1>
	    </div>
	</div><!-- end col md 12 -->
    </div><!-- end row -->

    <div class="row">
	<div class="col-md-4">
	    <?php
	    echo "<br>";
	    echo $this->Form->create('LogsConfig', array('url' => array('controller' => 'logs', 'action' => 'config'),
		'inputDefaults' => array(
		    'div' => 'form-group',
		    'wrapInput' => false,
		    'class' => 'form-control'
		)
	    ));
	    echo $this->Form->input('period_id', array('placeholder' => 'Code', 'label' => 'Time to keep logs:', 'default' => $period_id));
	    echo "<br>";
	    echo $this->Form->submit('Submit', array('div' => 'form-group', 'class' => 'btn btn-primary'));
	    echo $this->Form->end();
	    ?>
	</div>
    </div><!-- end row -->


</div><!-- end containing of content -->