
<?php echo $this->AssetCompress->script('game_engine_AC'); ?>
<?php echo $this->AssetCompress->script('live_node_data_AC'); ?>

<?php echo $this->Html->css('live_node_data'); ?>


<input type ="hidden" id="cnfRoute" value="<?php echo Router::url('/', true) ?>">