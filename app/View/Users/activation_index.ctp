<script>
    $(document).ready(function() {
	$('.paginated_jquery_table').dataTable({
	    "iDisplayLength": 10,
	    //"bLengthChange": false,
	    "order": [[ 2, "desc" ]],
	    "aoColumnDefs": [
		{ "bSortable": false, "aTargets": [-1]}
	    ],
	    "stateSave": true	    
	});
    });
</script>


<div class="users index container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Users'); ?></h1>
		<p>All users that are able to log in</p>
	    </div>
	</div><!-- end col md 12 -->
    </div><!-- end row -->

    <div class="row">
	<div class="col-md-12">	    
	    <?php echo $this->Html->link($this->Form->button("<span class='glyphicon glyphicon-plus'></span>  New User", array('class' => 'btn btn-primary')), array('controller' => 'users', 'action' => 'activation_add'), array('escape' => false)) ?>	    
	</div>
	<br><br><br>
    </div>

    <div class="row">

	<div class="col-md-10">
	    <table cellpadding="0" cellspacing="0" class="paginated_jquery_table table table-striped">
		<thead>
		    <tr>			
			<th><?php echo __('Email'); ?></th>
			<th><?php echo __('Name'); ?></th>			
			<th><?php echo __('Group'); ?></th>
			<th><?php echo __('Contact Number'); ?></th>			
			<th><?php echo __('Modified'); ?></th>
			<th class="actions"></th>
		    </tr>
		</thead>
		<tbody>
		    <?php foreach ($users as $user): ?>
    		    <tr>		
    			<td><?php echo $this->Html->link($user['User']['email'], array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?></td>    			
    			<td><?php echo h($user['User']['name']); ?>&nbsp;</td>    			
    			<td><?php echo $roles[$user['User']['role_id']]; ?>&nbsp;</td>
    			<td><?php echo h($user['User']['contact_number']); ?>&nbsp;</td>
    			<td><?php echo $this->Time->format($user['User']['modified'], '%Y-%m-%d %H:%M'); ?>&nbsp;</td>
    			<td class="actions">				
				<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'activation_edit', $user['User']['id']), array('escape' => false)); ?>
				<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'activation_delete', $user['User']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
    			</td>
    		    </tr>
		    <?php endforeach; ?>
		</tbody>
	    </table>

	</div> <!-- end col md 9 -->
    </div><!-- end row -->
    
    <div class="row">
	<div class="col-md-12">	    
	    <?php echo $this->Html->link($this->Form->button("<span class='glyphicon glyphicon-chevron-left'></span>  Logout", array('class' => 'btn btn-danger')), array('controller' => 'users', 'action' => 'activation_logout'), array('escape' => false)) ?>	    
	</div>
	<br><br><br>
    </div>


</div><!-- end containing of content -->