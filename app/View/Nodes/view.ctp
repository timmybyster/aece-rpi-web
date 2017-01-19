<div class="nodes view container">
    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo __('Node'); ?></h1>
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
			    <?php if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['service']): ?> 
    			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp&nbsp;Edit Node'), array('action' => 'edit', $node['Node']['id']), array('escape' => false)); ?> </li>
    			    <li><?php echo $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete Node'), array('action' => 'delete', $node['Node']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $node['Node']['id'])); ?> </li>			    
    			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp&nbsp;New Node'), array('action' => 'add'), array('escape' => false)); ?> </li>
			    <?php endif; ?>
				<?php if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['admin']): ?> 
    			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp&nbsp;Edit Node'), array('action' => 'edit', $node['Node']['id']), array('escape' => false)); ?> </li>
    			    <?php endif; ?>
			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp&nbsp;List Nodes'), array('action' => 'index'), array('escape' => false)); ?> </li>
			    <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-search"></span>&nbsp&nbsp;View on Map'), array('action' => 'live', $node['Node']['id']), array('escape' => false)); ?> </li>
			</ul>
		    </div><!-- end body -->
		</div><!-- end panel -->
	    </div><!-- end actions -->
	</div><!-- end col md 3 -->

	<div class="col-md-9">			
	    <table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>	
		    <!-- All node types -->
		    <tr>
			<th><?php echo __('Serial'); ?></th>
			<td>
			    <?php echo h($node['Node']['serial']); ?>
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Type'); ?></th>
			<td>
			    <?php echo Node::types()[$node['Node']['type_id']]; ?>
			</td>
		    </tr>
		    <?php if (Configure::read('debug') > 0): ?>
		    <tr>
			<th><?php echo __('X (DEBUG)'); ?></th>
			<td>
			    <?php echo h($node['Node']['x']); ?>
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Y (DEBUG)'); ?></th>
			<td>
			    <?php echo h($node['Node']['y']); ?>
			</td>
		    </tr>
		    <?php endif; ?>
		    <tr>
			<th><?php echo __('Key Switch Status'); ?></th>
			<td>
			    <?php echo Node::key_switch_enum()[$node['Node']['key_switch_status']]; ?>
			</td>
		    </tr>
		    <tr>
			<th><?php echo __('Communication Status'); ?></th>
			<td>
			    <?php echo Node::communication_status_enum()[$node['Node']['communication_status']]; ?>
			</td>
		    </tr>		    
		    <!-- ISC1 types -->
		    <?php if ($node['Node']['type_id'] == Node::TYPE_ISC1):?>
		    <tr>
			<th><?php echo __('Blast Armed'); ?></th>
			<td>
			    <?php echo Node::blast_armed_enum()[$node['Node']['blast_armed']]; ?>
			</td>
		    </tr>
		    <?php endif; ?>
		    <!-- IB651 types -->
		    <?php if ($node['Node']['type_id'] == Node::TYPE_IB651):?>
				<tr>
				<th><?php echo __('Detonator Status'); ?></th>
				<td>
					<?php echo Node::detonator_status_enum()[$node['Node']['detonator_status']]; ?>
				</td>
				</tr>
				<tr>
				<th><?php echo __('Booster Fired Lfs'); ?></th>
				<td>
					<?php echo Node::booster_fired_lfs_enum()[$node['Node']['booster_fired_lfs']]; ?>
				</td>
				</tr>
				<?php if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['service']): ?>
						<tr>
						<th><?php echo __('Partial Blast Lfs'); ?></th>
						<td>
							<?php echo Node::partial_blast_lfs_enum()[$node['Node']['partial_blast_lfs']]; ?>
						</td>
						</tr>
						<tr>
						<th><?php echo __('Full Blast Lfs'); ?></th>
						<td>
							<?php echo Node::full_blast_lfs_enum()[$node['Node']['full_blast_lfs']]; ?>
						</td>
						</tr>
						<tr>
						<th><?php echo __('Missing Pulse Detected Lfs'); ?></th>
						<td>
							<?php echo Node::missing_pulse_detected_lfs_enum()[$node['Node']['missing_pulse_detected_lfs']] ?>
						</td>
						</tr>
						<tr>
						<th><?php echo __('AC Supply Voltage Lfs'); ?></th>
						<td>
							<?php echo h($node['Node']['AC_supply_voltage_lfs']); ?>
						</td>
						</tr>
						<tr>
						<th><?php echo __('DC Supply Voltage'); ?></th>
						<td>
							<?php echo h($node['Node']['DC_supply_voltage']); ?>
						</td>
						</tr>
					<?php endif; ?>		
		    <?php endif; ?>		    
		    <tr>
			<th><?php echo __('Modified'); ?></th>
			<td>
			    <?php echo h($node['Node']['modified']); ?>
			</td>
		    </tr>
		</tbody>
	    </table>

	</div><!-- end col md 9 -->

    </div>
</div>
