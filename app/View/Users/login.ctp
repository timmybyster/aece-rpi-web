<div class="loginform">
    <div class='row container-fluid'>
	<h2>Login</h2>
	<?php
	echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login'),
	    'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'label' => false,
		'class' => 'form-control'
	    )
	));
	echo $this->Form->input('username', array('placeholder' => 'Username'));
	echo $this->Form->input('password', array('placeholder' => 'Password'));
	//echo $this->Form->input('options.remember_username', array('type' => 'checkbox', 'label' => __('Remember User Name')));
	//echo $this->Form->input('options.remember_username', array('type' => 'checkbox', 'div' => 'checkbox', 'class' => false, 'label' => 'Remember me'));
	echo "<br>";
	echo $this->Form->submit('Login', array('div' => 'form-group', 'class' => 'btn btn-primary'));
	echo $this->Form->end();
	?>

    </div>
</div>
