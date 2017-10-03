var gameHeight = window.innerHeight * window.devicePixelRatio;
var gameWidth = window.innerWidth * window.devicePixelRatio;

var cameraX = 0;
var cameraY = 0;
var cameraUpdate = 0;

var game;

var nodes = [];

var infoWindow;
var nodeVisuals = [];
var infoWindowlbls = {};
var infoWindowtxts = {};
var infoWindowNumlbls = 15;

var cursors;
var cam_cursor_movement_speed = 7;

$(document).ready(function() {

    game = new Phaser.Game(gameWidth, gameHeight, Phaser.CANVAS, 'live_div', {preload: preload, create: create, update: update, render: render});
	setInterval(updateData, 2000);
	
});

function preload(){
	game.stage.backgroundColor = "#000000";
	game.stage.tint = "#111111";
	game.load.spritesheet('infowindow', $('#cnfRoute').val() + '/img/infoWindow.png', 300, 300);
	game.load.image('ccb', $('#cnfRoute').val() + '/img/ccb.png');
	game.load.image('cbb', $('#cnfRoute').val() + '/img/cbb.png');
	game.load.image('keySwitch', $('#cnfRoute').val() + '/img/keySwitch.png');
	game.load.image('led', $('#cnfRoute').val() + '/img/led.png');
}

function create(){
	getAllNodeDataFromServer();
    createInfoWindow();
	
	cursors = game.input.keyboard.createCursorKeys();
	game.world.setBounds(-10000, -10000, 20000, 20000);
}

function update(){
	update_camera();
	if(cameraUpdate == 1){
		if(game.camera.y < cameraY - 50)
			game.camera.y += cam_cursor_movement_speed;
		if(game.camera.y > cameraY + 50)
			game.camera.y -= cam_cursor_movement_speed;
		if(game.camera.x < cameraX - 50)
			game.camera.x += cam_cursor_movement_speed;
		if(game.camera.x > cameraX + 50)
			game.camera.x -= cam_cursor_movement_speed;
		
		if(game.camera.y < cameraY)
			game.camera.y += 1;
		if(game.camera.y > cameraY)
			game.camera.y -= 1;
		if(game.camera.x < cameraX)
			game.camera.x += 1;
		if(game.camera.x > cameraX)
			game.camera.x -= 1;
		
		if(game.camera.y == cameraY && game.camera.x == cameraX)
			cameraUpdate = 0;
	}
}

function updateData(){
	var posting2 = $.post($('#cnfRoute').val() + "nodes/get_all_data/");
	posting2.done(function(data) {
	    nodes = data;
		updateNodes();
	});
}

function render(){
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
	
	nodeVisual.scale.set(0.4, 0.4);
	nodeVisual.db_id = node.id;
	
	nodeVisual.inputEnabled = true;
	nodeVisual.input.useHandCursor = true;
	nodeVisual.events.onInputOver.add(showInfoWindow, this);
	nodeVisual.events.onInputOut.add(hideInfoWindow, this);
	nodeVisual.events.onInputDown.add(clickEvent, this);
	
	nodeVisual.click = 0;
	
	text_col = Phaser.Color.toRGBA(255, 255, 255, 255);
	
	text1 = "SN: " + node.serial;
	serialText = game.add.text(0, 2*nodeVisual.height + 20, text1, 0);
	serialText.tint = text_col;
	nodeVisual.addChild(serialText);
	nodeVisual.serialText = serialText;
	
	text2 = node.comment;
	locationText = game.add.text(-20, 2*nodeVisual.height + 45, text2, 0);
	locationText.tint = text_col;
	nodeVisual.addChild(locationText);
	nodeVisual.locationText = locationText;
	
	nodeVisuals[node.id] = nodeVisual;
}

function createCcb(node){
	var ccb = game.add.sprite(parseInt(node.x), parseInt(node.y), 'ccb');
	return ccb;
	
}

function createCbb(node){
	var cbb = game.add.sprite(parseInt(node.x), parseInt(node.y), 'cbb');
	var led = game.add.sprite(cbb.width + 20, cbb.height - 40, 'led');
	cbb.state = "ok";
	
	bad_col = Phaser.Color.toRGBA(0, 255, 0, 0);
	neutral_col = Phaser.Color.toRGBA(100, 20, 20, 20);
	hl_col = Phaser.Color.toRGBA(0, 50, 100, 255);
	ok_col = Phaser.Color.toRGBA(0, 0, 255, 0);
	setInterval(function(){
		setTimeout(function(){
			led.tint = neutral_col;
			led.alpha = 0.2;
		},100);
		led.alpha = 0.8;
		switch(cbb.state){
			case "ok" :
				led.tint = ok_col;
				break;
			case "fault" :
				led.tint = bad_col;
				break;
				
			case "fired" :
				led.tint = hl_col;
				break;
		}
	},1000);
	cbb.addChild(led);
	cbb.led = led;
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
    display_labels = ['Type', 'Location'];

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
		display_params.push('dets_length');
		display_labels.push('Detonators connected');
    }
	

    for (var i = 0; i < display_params.length; i++) {
	infoWindowlbls[i].text = display_labels[i];
	if (display_params[i] in node && node[display_params[i]])
	    infoWindowtxts[i].text = node[display_params[i]];
    }

}

function hideInfoWindow(sprite, pointer){
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
	bad_col = Phaser.Color.toRGBA(0, 255, 0, 0);
	neutral_col = Phaser.Color.toRGBA(255, 255, 255, 255);
	
	if(node.key_switch_status == 1)
		nodeVisual.keySwitch.alpha = 1;
	else
		nodeVisual.keySwitch.alpha = 0.2;
	
	if(node.communication_status == 1)
		nodeVisual.alpha = 1;
	else
		nodeVisual.alpha = 0.5;
	
	if(node.earth_leakage == 1 || node.cable_fault == 1){
		if(nodeVisual.flashInterval == null){
			nodeVisual.flashInterval = setInterval(function(){
			if(nodeVisual.tint === bad_col)
				nodeVisual.tint = neutral_col;
			else
				nodeVisual.tint = bad_col;
			},500);
		}
	}
	else{
		if(nodeVisual.flashInterval != null){
			clearInterval(nodeVisual.flashInterval);
			nodeVisual.flashInterval = null;
			nodeVisual.tint = neutral_col;
		}
	}
	
	if(parseInt(node.type_id) == 3){
		if(node.partial_blast_lfs == 1)
			nodeVisual.state = "fault";
		else if(node.booster_fired_lfs == 1)
			nodeVisual.state = "fired";
		else
			nodeVisual.state = "ok";
	}
	
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

function clickEvent(sprite, pointer){
	if(sprite.click == 0){
		sprite.click = 1;
		focusCameraOnNode(sprite, pointer);
	}
	else if(sprite.click == 1){
		sprite.click = 0;
		goToDetonators(sprite, pointer);
	}
}

function focusCameraOnNode(sprite, pointer){
	cameraUpdate = 1;
	cameraX = sprite.x - 600;
	cameraY = sprite.y - 200;
}

function goToDetonators(sprite, pointer){
	console.log("detonators");
	path = "detslive/" + sprite.db_id;
	open(path, "_blank", "channelmode=yes,fullscreen=yes,titlebar=no,status=no,menubar=no,toolbar=no,scrollbars=no");
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