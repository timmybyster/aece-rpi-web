
<?php echo $this->AssetCompress->script('game_engine_AC'); ?>
<?php echo $this->AssetCompress->script('live_node_data_AC'); ?>

<?php echo $this->Html->css('live_node_data'); ?>

<script>



</script>

<!-- configuration of images on status page -->
<input type ="hidden" id="cnfBackgroundImg" value="<?php echo $backgroundImg ?>">
<input type ="hidden" id="cnfBc1Img" value="<?php echo $bc1Img ?>">
<input type ="hidden" id="cnfIsc1Img" value="<?php echo $isc1Img ?>">
<input type ="hidden" id="cnfIb651Img" value="<?php echo $ib651Img ?>">
<input type ="hidden" id="cnfKeySwitchArmedImg" value="<?php echo $keySwitchArmedImg ?>">
<input type ="hidden" id="cnfKeySwitchDisarmedImg" value="<?php echo $keySwitchDisarmedImg ?>">
<input type ="hidden" id="cnfDetonatorConnImg" value="<?php echo $detonatorConnImg ?>">
<input type ="hidden" id="cnfDetonatorNotConnImg" value="<?php echo $detonatorNotConnImg ?>">
<input type ="hidden" id="cnfFaultDisplayImg" value="<?php echo $faultDisplayImg ?>">
<input type ="hidden" id="cnfWarningImg" value="<?php echo $warningImg ?>">

<input type ="hidden" id="cnfBackgroundImgContrast" value="<?php echo $background_image_contrast ?>">
<input type ="hidden" id="cnfBackgroundImgSizeMultiply" value="<?php echo $background_image_size_multiply ?>">

<input type ="hidden" id="cnfMayMoveNodes" value="<?php echo $mayMoveNodes ?>">

<input type ="hidden" id="cnfWarningDismissDelay" value="<?php echo $warning_dismiss_delay ?>">

<input type ="hidden" id="cnfFocusOnNode" value="<?php echo $focus_node_id ?>">

<input type ="hidden" id="cnfRoute" value="<?php echo Router::url('/', true) ?>">
<input type ="hidden" id="cnfCakeDebugMode" value="<?php echo Configure::read('debug') ?>">

<div class="nodes live container-fluid">
    
    <!-- DIV containing the the node images -->
    <div class="row">
	<div id="live_div" style="live_node_div" style="width: 100%; height:300px;">

	    <select id="node_highlight">
		<option value="armed_IB651_detonator_connected">All armed IB651 boosters with Detonator Status CONNECTED</option>
		<option value="armed_IB651_detonator_notconnected">All armed IB651 boosters</option>
		<option value="IB651_detonator_connected">All IB651 boosters with Detonator Status CONNECTED</option>
		<option value="armed_ISC1">Armed ISC-1s</option>
		<option value="disarmed_ISC1">Isolated ISC-1s</option>
		<option value="cable_fault">Units detecting Cable Faults</option>
		<option value="earth_leakage">Units detecting Earth Leakage Faults</option>
		<option value="booster_fired">IB651 Boosters that fired</option>
		<option value="booster_did_not_fire">IB651 Boosters that did not fire</option>
		<option value="none" selected="selected">None</option>
	    </select>
	    
	</div>
    </div>
    
    <!-- Warning message dialog -->
    <div class="row">
	<div class="col-md-12">
	    <div id="dlgWarning" title="Warning">
		<form>
		    <span style ="float: left; margin:0 30px 30px 0;"><?php echo $this->Html->image("warning.png")?></span>
		    <div id="warningMessage">Some warning to display</div>		    
		</form>	
		<div id="dlgLoadingIndic" style="display:none" ><?php echo $this->Html->image("ajax-loader.gif", array('id' => 'dlgLoadingIndic'))?> Please wait...</div>
	    </div>
	</div>
    </div>

</div>