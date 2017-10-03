var gameHeight = window.innerHeight * window.devicePixelRatio;
var gameWidth = window.innerWidth * window.devicePixelRatio;

var cameraX = 0;
var cameraY = 0;

var game;

var nodes = [];

var infoWindow;
var nodeVisuals = [];
var infoWindowlbls = {};
var infoWindowtxts = {};
var infoWindowNumlbls = 15;

var cursors;
var cam_cursor_movement_speed = 15;

$(document).ready(function() {

    game = new Phaser.Game(gameWidth, gameHeight, Phaser.CANVAS, 'live_div', {preload: preload, create: create, update: update, render: render});
	setInterval(updateData, 2000);
	
});

function preload(){
	game.stage.backgroundColor = "#FFFFFF";
	game.load.spritesheet('infowindow', $('#cnfRoute').val() + '/img/infoWindow.png', 300, 300);
	game.load.image('ccb', $('#cnfRoute').val() + '/img/ccb.png');
	game.load.image('cbb', $('#cnfRoute').val() + '/img/cbb.png');
	game.load.image('keySwitch', $('#cnfRoute').val() + '/img/keySwitch.png');
}

function create(){
	getAllNodeDataFromServer();
    createInfoWindow();
	
	cursors = game.input.keyboard.createCursorKeys();
	game.world.setBounds(-10000, -10000, 20000, 20000);
}

function update(){
	//update_camera();
	if(game.camera.y < cameraY)
		game.camera.y ++;
	if(game.camera.y > cameraY)
		game.camera.y --;
	if(game.camera.x < cameraX)
		game.camera.x ++;
	if(game.camera.x > cameraX)
		game.camera.x --;
}

function updateData(){
	var posting2 = $.post($('#cnfRoute').val() + "nodes/get_all_data/");
	posting2.done(function(data) {
	    nodes = data;
		updateNodes();
	});
}

function render(){
	game.debug.cameraInfo(game.camera, 32, 32);
}

function getAllNodeDataFromServer() {
    var posting = $.post($('#cnfRoute').val() + "/nodes/get_all_data/");
    posting.done(function(data) {
		nodes = data;
		createNodeVisuals();
    });
}

function createNodeVisuals(){
	nodes.forEach(node =>{
		createNodeVisual(node.Node);
	});
	updateData();
}

function createNodeVisual(node){
	var nodeVisual;
	switch(parseInt(node.type_id)){
		case 0 :
			nodeVisual = createCcb(node);
			break;
			
		case 3 :
			nodeVisual = createCbb(node);
			break;
			
		default :
			break;
	}
	var keySwitch = game.add.sprite(nodeVisual.width + 20, 0, 'keySwitch');
	nodeVisual.addChild(keySwitch);
	nodeVisual.keySwitch = keySwitch;
	
	nodeVisual.scale.set(0.5, 0.5);
	nodeVisual.db_id = node.id;
	
	nodeVisual.inputEnabled = true;
	nodeVisual.input.useHandCursor = true;
	nodeVisual.events.onInputOver.add(showInfoWindow, this);
	nodeVisual.events.onInputOut.add(hideInfoWindow, this);
	nodeVisual.events.onInputDown.add(focusCameraOnNode, this);
	
	nodeVisuals[node.id] = nodeVisual;
}

function createCcb(node){
	var ccb = game.add.sprite(parseInt(node.x), parseInt(node.y), 'ccb');
	return ccb;
	
}

function createCbb(node){
	var cbb = game.add.sprite(parseInt(node.x), parseInt(node.y), 'cbb');
	return cbb;
}

function createInfoWindow(){
	infoWindow = game.add.sprite(game.world.centerX, game.world.centerY, 'infowindow');
    infoWindow.alpha = 0.8;
    infoWindow.visible = false;
    infoWindow.anchor.set(0);
	infoWindow.scale.set(0.7);
    var style = {font: "12px Arial", fill: "#eeeeee", wordWrap: true, wordWrapWidth: infoWindow.width, align: "center"};
    xOffset = 150;
    yOffset = 20;
    for (var i = 0; i < infoWindowNumlbls; i++) {
		infoWindowlbls[i] = game.add.text(20, 20 + yOffset * i, "", style);
		infoWindowtxts[i] = game.add.text(xOffset, 20 + yOffset * i, "", style);
		infoWindow.addChild(infoWindowlbls[i]);
		infoWindow.addChild(infoWindowtxts[i]);
	}
}

function showInfoWindow(sprite, pointer) {

    if (game.input.mouse.button == 0)
	return;

	infoWindow.width = 300;
	infoWindow.height = 300;

    newx = Number(sprite.x) + 50;
    infoWindow.x = newx;
    infoWindow.y = sprite.y;
	infoWindow.width = infoWindow.width;
	infoWindow.height = infoWindow.height;
    infoWindow.visible = true;
    infoWindow.bringToTop();

    // check if inside camera
    // only need to check right edgse and bottom edge - the user can't mouse over any other position		
    cam = game.camera;
    if ((Number(infoWindow.x) + infoWindow.width) > (cam.x + cam.width))
		infoWindow.x -= infoWindow.width + 100;
    if ((Number(infoWindow.y) + infoWindow.height) > (cam.y + cam.height))
		infoWindow.y -= infoWindow.height - 100;
	
	node = getNodeDataById(sprite.db_id);
	
    display_params = ['type', 'comment'];
    display_labels = ['Type', 'Comment'];

    for (var i = 0; i < infoWindowNumlbls; i++) {
	infoWindowlbls[i].text = "";
	infoWindowtxts[i].text = "";
    }

    if (node['type'] === 'CCB') { //IBC1
	display_params.push('serial');
	display_labels.push('Serial');
	display_params.push('key_switch_status_text');
	display_labels.push('Key Switch');
	display_params.push('fire_button_text');
	display_labels.push('Fire Button');
	display_params.push('cable_fault_text');
	display_labels.push('Cable fault');
	display_params.push('earth_leakage_text');
	display_labels.push('Earth Leakage');
    }

	if (node['type'] === 'CBB') { //CBB
		display_params.push('serial');
		display_labels.push('Serial');
		display_params.push('communication_status_text');
		display_labels.push('COMM Status');
		display_params.push('key_switch_status_text');
		display_labels.push('Key Switch');
		display_params.push('blast_armed_text');
		display_labels.push('Ready Status');
		display_params.push('partial_blast_lfs_text');
		display_labels.push('Detonator Status');
		display_params.push('mains_text');
		display_labels.push('Mains');
		display_params.push('DC_supply_voltage_status_text');
		display_labels.push('36V');
		display_params.push('cable_fault_text');
		display_labels.push('Cable fault');
		display_params.push('earth_leakage_text');
		display_labels.push('Earth Leakage');
		display_params.push('isolation_status_text');
		display_labels.push('Isolation Relay');
		display_params.push('window_id');
		display_labels.push('Detonators connected');
    }
	

    for (var i = 0; i < display_params.length; i++) {
	infoWindowlbls[i].text = display_labels[i];
	if (display_params[i] in node && node[display_params[i]])
	    infoWindowtxts[i].text = node[display_params[i]];
    }

}

function hideInfoWindow(){
	infoWindow.visible = false;
}

function getNodeDataById(id) {
    // might need to optimize this if things are getting slow
    for (var i = 0, len = nodes.length; i < len; i++) {
	if (nodes[i]['Node']['id'] === id)
	    return nodes[i]['Node'];
    }
}

function updateNodes(){
	if (typeof nodes === 'undefined')
		return;
	nodes.forEach(node =>{
		if(node.Node.id in nodeVisuals)
			updateNodeVisual(nodeVisuals[node.Node.id], node.Node);
		else
			createNodeVisual(node.Node);
	});
	
}

function updateNodeVisual(nodeVisual, node){
	if(node.key_switch_status)
		nodeVisual.keySwitch.alpha = 1;
	else
		nodeVisual.keySwitch.alpha = 0;
	
	if(node.communication_status)
		nodeVisual.alpha = 1;
	else
		nodeVisual.alpha = 0.5;
}

var o_camera;
var cameraDrag = 5;
var cameraAccel = 1;
var camVelX = 0;
var camVelY = 0;
var camMaxSpeed = 80;

function update_camera_movement() {
    camVelX = clamp(camVelX, camMaxSpeed, -camMaxSpeed);
    camVelY = clamp(camVelY, camMaxSpeed, -camMaxSpeed);

    game.camera.x += camVelX;
    game.camera.y += camVelY;

    //Set Camera Velocity X Drag
    if (camVelX > cameraDrag) {
	camVelX -= cameraDrag;
    } else if (camVelX < -cameraDrag) {
	camVelX += cameraDrag;
    } else {
	camVelX = 0;
    }

    //Set Camera Velocity Y Drag
    if (camVelY > cameraDrag) {
	camVelY -= cameraDrag;
    } else if (camVelY < -cameraDrag) {
	camVelY += cameraDrag;
    } else {
	camVelY = 0;
    }
}

function clamp(val, max, min) {
    var value = val;

    if (value > max)
	value = max;
    else if (value < min)
	value = min;

    return value;
}

function drag_camera() {

    pointer = game.input.mousePointer;

    if (!pointer.timeDown) {
	return;
    }
    if (pointer.isDown && !pointer.targetObject && game.input.mouse.button == 0) {

	if (o_camera) {
	    camVelX = (o_camera.x - pointer.position.x) * cameraAccel;
	    camVelY = (o_camera.y - pointer.position.y) * cameraAccel;
	}
	o_camera = pointer.position.clone();
    }

    if (pointer.isUp) {
	o_camera = null;
    }
}

function update_camera() {
    // mouse drag camera
    drag_camera();
    update_camera_movement();

    // keyboard camera
    if (cursors.up.isDown)
    {
	game.camera.y -= cam_cursor_movement_speed;
    }
    else if (cursors.down.isDown)
    {
	game.camera.y += cam_cursor_movement_speed;
    }

    if (cursors.left.isDown)
    {
	game.camera.x -= cam_cursor_movement_speed;
    }
    else if (cursors.right.isDown)
    {
	game.camera.x += cam_cursor_movement_speed;
    }
}

function focusCameraOnNode(sprite, pointer){
	cameraX = sprite.x - 200;
	cameraY = sprite.y - 200;
}




var warninglist = [];

$(document).ready(function() {

    var warningDelaySec = Number($('#cnfWarningDismissDelay').val());

    if (warningDelaySec > 1) {
	// show warnings
	setInterval(function() {
	    showWarnings(); // user paging is not reset on reload
	}, warningDelaySec * 1000);
    }

    function dlgWarningAcknowledge() {

	btnAckn = $('#dlgWarning').dialog('widget').find('.ui-dialog-buttonpane button:eq(0)');
	btnAckn.attr('disabled', true);
	btnAckn.addClass("ui-state-disabled");
	$('#dlgLoadingIndic').show();

	warnId = $("#dlgWarning").data('warnId');

	var posting = $.post("../warnings/acknowledge_warning/", {id: warnId});
	posting.done(function(data) {
	    if (data['success'] !== 1) {
		$("#dlgWarning").dialog("close");
		alert(data['reason']);
	    } else {
		$("#dlgWarning").dialog("close");
	    }

	    // check if there is more warnings in the list
	    if (warninglist.length > 0) {
		warning = warninglist.pop();
		$('#warningMessage').html(warning[1]);
		$("#dlgWarning").data('warnId', warning[0]).dialog("open");
	    }

	});
	posting.fail(function(data) {
	    alert('Problem connecting to server. Please try again.');
	    btnAckn = $('#dlgWarning').dialog('widget').find('.ui-dialog-buttonpane button:eq(0)');
	    btnAckn.attr('disabled', false);
	    btnAckn.removeClass("ui-state-disabled");
	    $('#dlgLoadingIndic').hide();
	});

    }

    function dlgWarningDismiss() {
	$("#dlgWarning").dialog("close");

	// check if there is more warnings in the list
	if (warninglist.length > 0) {
	    warning = warninglist.pop();
	    $('#warningMessage').html(warning[1]);
	    $("#dlgWarning").data('warnId', warning[0]).dialog("open");
	}
    }


    dlgWarning = $("#dlgWarning").dialog({
	autoOpen: false,
	modal: true,
	width: 400,
	buttons: {
	    "Acknowledge": dlgWarningAcknowledge,
	    "Dismiss": dlgWarningDismiss,
	},
	open: function(event, ui) {
	    btnSubmit = $('#dlgWarning').dialog('widget').find('.ui-dialog-buttonpane button:eq(0)');
	    btnSubmit.attr('disabled', false);
	    btnSubmit.removeClass("ui-state-disabled");
	    $('#dlgLoadingIndic').hide();
	}
    });

    showWarnings(); // show on startup
});


function showWarnings() {
    // get all the unacknowledged warnings

    warninglist = [];

    var base_url = $('#cnfRoute').val();
    //alert(base_url);
    var posting = $.post(base_url + "warnings/get_unacknowledged/");
    posting.done(function(data) {
	$(data).each(function(index, elem) {
	    warning = elem['Warning'];
	    //alert('got warning' + warning['message']);	    
	    warninglist.push([warning['id'], warning['message']]);
	});
	if (warninglist.length > 0) {
	    warning = warninglist.pop()
	    $('#warningMessage').html(warning[1]);
	    $("#dlgWarning").data('warnId', warning[0]).dialog("open");
	}
    });

}