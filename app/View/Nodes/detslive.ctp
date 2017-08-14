
<?php echo $this->Html->script('paginated.jquery.datatable.setup'); ?>

<div class="nodes index container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('CBB SN: '); ?><?php echo __($parentNode['Node']['serial']); ?><?php echo __(' Detonators'); ?></h1>
	    </div>
	</div><!-- end col md 12 -->
    </div><!-- end row -->



    <div class="row">


	<div class="col-md-11">
	    <table cellpadding="0" cellspacing="0" class="paginated_jquery_table table table-striped">
		<thead>
		    <tr>
			<th><?php echo __('IP'); ?></th>
			<th><?php echo __('Type'); ?></th>			
			<th><?php echo __('Connection Status'); ?></th>
			<th><?php echo __('Window'); ?></th>
			<th><?php echo __('Logged'); ?></th>
			<th><?php echo __('Calibrated'); ?></th>
			<th><?php echo __('Delay (ms)'); ?></th>
		    </tr>
		</thead>
		<tbody>
		    <?php foreach ($nodes as $node): ?>
    		    <?php if($node['Node']['parent_id'] == $parentNode['Node']['id']) : ?>
					<tr>
					<th><?php echo h((($node['Node']['serial'])>>24) & 255);?><?php echo __('.'); ?> 
						<?php echo h((($node['Node']['serial'])>>16) & 255);?> <?php echo __('.'); ?>
						<?php echo h((($node['Node']['serial'])>>8) & 255);?><?php echo __('.'); ?>
						<?php echo h((($node['Node']['serial'])) & 255);?>
					</th>
					<td><?php echo h($types[$node['Node']['type_id']]); ?></td>
					<td><?php echo h($node['Node']['detonator_status_text']); ?></td>			
					<td><?php echo h($node['Node']['window_id']); ?></td>
					<td><?php echo h($node['Node']['tagged_text']); ?></td>
					<td><?php echo h($node['Node']['calibration_text']); ?></td>
					<td><?php echo h($node['Node']['delay']); ?></td>
					</tr>
				<?php endif; ?>	
		    <?php endforeach; ?>
		</tbody>
	    </table>
	</div> <!-- end col md 9 -->
    </div><!-- end row -->


</div><!-- end containing of content -->