<div class="postBlastErrors view container">
    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Post Blast Error'); ?></h1>
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
			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp&nbsp;Edit Post Blast Error'), array('action' => 'edit', $postBlastError['PostBlastError']['id']), array('escape' => false)); ?> </li>
			    <li><?php echo $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete Post Blast Error'), array('action' => 'delete', $postBlastError['PostBlastError']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $postBlastError['PostBlastError']['id'])); ?> </li>
			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp&nbsp;List Post Blast Errors'), array('action' => 'index'), array('escape' => false)); ?> </li>
			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp&nbsp;New Post Blast Error'), array('action' => 'add'), array('escape' => false)); ?> </li>
			</ul>
		    </div><!-- end body -->
		</div><!-- end panel -->
	    </div><!-- end actions -->
	</div><!-- end col md 3 -->

	<div class="col-md-9">			
	    <table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		    <tr>
			<th><?php echo __('Id'); ?></th>
			<td>
			    <?php echo h($postBlastError['PostBlastError']['id']); ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Message'); ?></th>
			<td>
			    <?php echo h($postBlastError['PostBlastError']['message']); ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Node Serial'); ?></th>
			<td>
			    <?php echo h($postBlastError['PostBlastError']['node_serial']); ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Blast Event ID'); ?></th>
			<td>
			    <?php echo h($postBlastError['BlastEvent']['id']); ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Blast Event Time'); ?></th>
			<td>
			    <?php echo h($postBlastError['BlastEvent']['blast_time']); ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Created'); ?></th>
			<td>
			    <?php echo h($postBlastError['PostBlastError']['created']); ?>
			    &nbsp;
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Modified'); ?></th>
			<td>
			    <?php echo h($postBlastError['PostBlastError']['modified']); ?>
			    &nbsp;
			</td>
		    </tr>
		</tbody>
	    </table>

	</div><!-- end col md 9 -->

    </div>
</div>
