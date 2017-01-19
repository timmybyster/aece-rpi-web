
<div class="logs index container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Logs'); ?></h1>
	    </div>
	</div><!-- end col md 12 -->
    </div><!-- end row -->

    <div class="row">

	<?php if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['service']): ?> 
    	<div class="row">
    	    <div class="col-md-12">	    
		    <?php echo $this->Html->link($this->Form->button("<span class='glyphicon glyphicon-plus'></span>  New Log", array('class' => 'btn btn-primary')), array('action' => 'add'), array('escape' => false)) ?>	    
    	    </div>
    	    <br><br><br>
    	</div>
	<?php endif; ?>

	<div class="col-md-11">
	    <?php 
	    
	    $exportoptions = array("dom" => 'T<"clear">lfrtip',	
		"tableTools" => array(
		"sSwfPath" => "swf/copy_csv_xls_pdf.swf",
		"aButtons" => array(
                    "sExtends" => "csv",
                    "sFileName" => "CCWP - *.csv",		    
		    "oSelectorOpts" => array("filter" => 'applied', "order" => 'current' ),
                )));
		    
		echo $this->DataTable->render('Default', array('class' => 'dataTable logs_dt table table-striped'), 
		    array('processing' => true, 'stateSave' => true, 'searchDelay' => 500, 'dom' => $exportoptions['dom']));
	    ?>	    

	</div> <!-- end col md 9 -->
    </div><!-- end row -->


</div><!-- end containing of content -->