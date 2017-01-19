
<?php echo $this->Html->script('paginated.jquery.datatable.setup'); ?>

<div class="nodes index container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Nodes'); ?></h1>
	    </div>
	</div><!-- end col md 12 -->
    </div><!-- end row -->



    <div class="row">


	<?php if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['service']): ?> 
	<div class="row">
	    <div class="col-md-12">	    
		<?php echo $this->Html->link($this->Form->button("<span class='glyphicon glyphicon-plus'></span>  New Node", array('class' => 'btn btn-primary')), array('action' => 'add'), array('escape' => false)) ?>	    
	    </div>
	    <br><br><br>
	</div>
	<?php endif;?>


	<div class="col-md-11">
	    <table cellpadding="0" cellspacing="0" class="paginated_jquery_table table table-striped">
		<thead>
		    <tr>
			
			<th><?php echo __('Serial'); ?></th>
			<th><?php echo __('Type'); ?></th>			
			<th><?php echo __('COMM Status'); ?></th>
			<th><?php echo __('Comment'); ?></th>
			<th><?php echo __('Parent (serial)'); ?></th>
			<th><?php echo __('Modified'); ?></th>			
			<th class="actions"></th>
			<th><?php echo __(''); ?></th>
		    </tr>
		</thead>
		<tbody>
		    <?php foreach ($nodes as $node): ?>
    		    <tr>
			<td><?php echo $this->Html->link(h($node['Node']['serial']), array('action' => 'view', $node['Node']['id'])); ?></td>
    			<td><?php echo h($types[$node['Node']['type_id']]); ?></td>
    			<td><?php echo h($node['Node']['communication_status_text']); ?></td>	
			<td><?php if($node['Node']['type_id'] < 3)
				 echo h($node['Node']['comment']); ?></td>	
    			<td><?php echo h($node['Parent']['serial']); ?></td>	
    			<td><?php echo h($node['Node']['modified']); ?></td>
    			<td class="actions">
				<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $node['Node']['id']), array('escape' => false)); ?>				
    			</td>
			<td>
			    <?php echo $this->Html->link('See on map', array('controller' => 'nodes', 'action' => 'live', $node['Node']['id']), array('escape' => false)); ?>
			</td>
    		    </tr>
		    <?php endforeach; ?>
		</tbody>
	    </table>
	</div> <!-- end col md 9 -->
    </div><!-- end row -->


</div><!-- end containing of content -->