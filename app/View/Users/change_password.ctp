<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
	<h2><?php echo __('Change Password') ?></h2>
	<?php echo $this->Form->create('User', 
		    array('url' => array('controller' => 'users', 'action' => 'change_password'),
			    'inputDefaults' => array(
				'div' => 'form-group',
				'wrapInput' => false,
				'label' => false,
				'class' => 'form-control'
			)
		));
	echo $this->Form->input('pwd_current', array('type' => 'password', 'label' => '<b>' . __('Current Password') . '</b>'));
	echo $this->Form->input('pwd', array('type' => 'password', 'label' => '<b>' . __('New Password') . '</b>'));
	echo $this->Form->input('pwd_repeat', array('type' => 'password', 'label' => '<b>' . __('Re-enter Password') . '</b>'));
	echo $this->Form->input('options.originalreferer', array('type' => 'hidden'));
	echo $this->Form->submit('Change', array('div' => 'form-group','class' => 'btn btn-primary'));
	echo $this->Form->end();	
	?>
    </div>
    <div class="col-sm-3"></div>
</div>

