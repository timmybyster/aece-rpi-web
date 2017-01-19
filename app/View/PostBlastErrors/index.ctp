
<?php echo $this->Html->script('paginated.jquery.datatable.setup'); ?>

<div class="postBlastErrors index container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Post Blast Report'); ?></h1>
	    </div>
	</div><!-- end col md 12 -->
    </div><!-- end row -->

    <?php if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['service']): ?> 
        <div class="row">
    	<div class="col-md-12">	    
		<?php echo $this->Html->link($this->Form->button("<span class='glyphicon glyphicon-plus'></span>  New", array('class' => 'btn btn-primary')), array('action' => 'add'), array('escape' => false)) ?>	    
    	</div>
    	<br><br><br>
        </div>
    <?php endif; ?>

    <div class="row">
	<div class="col-md-12">
	    <?php echo "Last Blast at: ".$this->Time->format('j F Y h:i:s A', $last_blast_time);?>
	    <br>
	    <?php if (count($postBlastErrors) > 0):?>
		<h2><p class="text-danger">Blast Error</p></h2>
	    <?php else: ?>
		<h2><p class="text-success">Blast OK</p></h2>
	    <?php endif; ?>
	</div>
    </div>

    <div class="row">

	<div class="col-md-9">
	    <table cellpadding="0" cellspacing="0" class="paginated_jquery_table table table-striped">
		<thead>
		    <tr>			
			<th><?php echo __('Log'); ?></th>
			<th><?php echo __('Node Serial'); ?></th>
			<th class="actions"></th>
		    </tr>
		</thead>
		<tbody>
		    <?php foreach ($postBlastErrors as $postBlastError): ?>
    		    <tr>    			
    			<td><?php echo h($postBlastError['PostBlastError']['message']); ?>&nbsp;</td>
    			<td><?php echo h($postBlastError['PostBlastError']['node_serial']); ?>&nbsp;</td>    			
    			<td>
				<?php
				if ($postBlastError['PostBlastError']['node_id'] != false)
				    echo $this->Html->link('See on map', array('controller' => 'nodes', 'action' => 'live', $postBlastError['PostBlastError']['node_id']), array('escape' => false));
				?>
    			</td>
    		    </tr>
		    <?php endforeach; ?>
		</tbody>
	    </table>

	</div> <!-- end col md 9 -->
    </div><!-- end row -->


</div><!-- end containing of content -->