<?php
$cur_role = "technician";
if ($this->Session->read('Auth') == null || !array_key_exists('User', $this->Session->read('Auth')))
    return;

if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['admin']) {
    $cur_role = "admin";
}

if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['supervisor']) {
    $cur_role = "supervisor";
}

if ($this->Session->read('Auth')['User']['role_id'] == Configure::read('Role')['service']) {
    $cur_role = "service";
}
?>    

<?php //echo $this->Html->css('navbar'); // cached by asset compress  ?>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	    </button>
	    <?php echo $this->Html->link("System Status", array('controller' => 'users', 'action' => 'home'), array('class' => "navbar-brand")); ?>
        </div>
        <div class="collapse navbar-collapse">
	    <ul class="nav navbar-nav">		
		<li><?php echo $this->Html->link(__('Nodes'), array('controller' => 'nodes', 'action' => 'index')); ?></li> 
		<li><?php echo $this->Html->link(__('Logging'), array('controller' => 'logs', 'action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Warnings'), array('controller' => 'warnings', 'action' => 'index')); ?></li>
		<?php if ($cur_role == "admin" || $cur_role == "service"): ?>
    		<li class="dropdown">
    		    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Config <span class="caret"></span></a>
    		    <ul class="dropdown-menu" role="menu">			    
    			<li><?php echo $this->Html->link(__('Users'), array('controller' => 'users', 'action' => 'index')); ?></li>				
    			<li><?php echo $this->Html->link(__('Logs'), array('controller' => 'logs', 'action' => 'config')); ?></li>
    			<li><?php echo $this->Html->link(__('Status Page'), array('controller' => 'nodes', 'action' => 'config')); ?></li>
			<?php if ($cur_role == "service"): ?>
			<li><?php echo $this->Html->link(__('Network'), array('controller' => 'system_settings', 'action' => 'config_network')); ?></li>
			<li><?php echo $this->Html->link(__('Time'), array('controller' => 'system_settings', 'action' => 'config_time')); ?></li>
			<?php endif; ?>
    		    </ul>
    		</li>
		<?php endif; ?>
		<li><?php echo $this->Html->link(__('Change Pwd'), array('controller' => 'users', 'action' => 'change_password')); ?></li>

		<li><?php echo $this->Html->link(__('Logout'), array('controller' => 'users', 'action' => 'logout')); ?></li>		
	    </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<br><br><br>