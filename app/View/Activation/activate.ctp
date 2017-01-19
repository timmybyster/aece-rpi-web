<div class="loginform">
    <div class='row container-fluid'>
	<h2>Activation</h2>
	
	<?php
	echo "<br>";
	echo $this->Form->create('Activation', array('url' => array('controller' => 'activation', 'action' => 'activate'),
	    'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,		
		'class' => 'form-control'
	    )
	));
	echo $this->Form->input('client_key', array('placeholder' => 'Code', 'value' => $client_key));
	echo $this->Form->input('activation_code', array('placeholder' => 'Activation Code'));	
	echo $this->Form->input('client_key_protect', array('placeholder' => 'Code', 'value' => $client_key_protect, 'type'=>'hidden'));
	echo "<br>";
	echo $this->Form->submit('Activate', array('div' => 'form-group', 'class' => 'btn btn-primary'));
	echo $this->Form->end();
	?>

    </div>
</div>