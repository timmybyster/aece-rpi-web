<div class="users view container">
    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('User'); ?></h1>
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
			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp&nbsp;Edit User'), array('action' => 'edit', $user['User']['id']), array('escape' => false)); ?> </li>
			    <li><?php echo $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete User'), array('action' => 'delete', $user['User']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp&nbsp;List Users'), array('action' => 'index'), array('escape' => false)); ?> </li>
			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp&nbsp;New User'), array('action' => 'add'), array('escape' => false)); ?> </li>
			</ul>
		    </div><!-- end body -->
		</div><!-- end panel -->
	    </div><!-- end actions -->
	</div><!-- end col md 3 -->

	<div class="col-md-9">			
	    <table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		    <tr>
			<th><?php echo __('Email'); ?></th>
			<td>
			    <?php echo h($user['User']['email']); ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Name'); ?></th>
			<td>
			    <?php echo h($user['User']['name']); ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Contact Number'); ?></th>
			<td>
			    <?php echo h($user['User']['contact_number']); ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Group'); ?></th>
			<td>
			    <?php echo $roles[$user['User']['role_id']]; ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Created'); ?></th>
			<td>
			    <?php echo $this->Time->format($user['User']['created'],'%Y-%m-%d %H:%M');?>
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Modified'); ?></th>
			<td>
			    <?php echo $this->Time->format($user['User']['modified'],'%Y-%m-%d %H:%M');?>
			</td>
		    </tr>
		</tbody>
	    </table>

	</div><!-- end col md 9 -->

    </div>
</div>
