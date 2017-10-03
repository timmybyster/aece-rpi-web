var game;
var nodes = [];
var infoWindow;
var nodeVisuals = [];
var infoWindowlbls = {};
var infoWindowtxts = {};
var infoWindowNumlbls = 15;

$(document).ready(function() {

    var gameHeight = window.innerHeight * window.devicePixelRatio;
	var gameWidth = window.innerWidth * window.devicePixelRatio;

    game = new Phaser.Game(gameWidth, gameHeight, Phaser.CANVAS, 'live_div', {preload: preload, create: create, update: update, render: render});
	
	setInterval(function(){
		updateData()
	}, 2000);
	
});

function preload(){
	game.stage.backgroundColor = "#FFFFFF";
	game.load.spritesheet('infowindow', $('#cnfRoute').val() + '/img/infoWindow.png');
	game.load.image('ccb', $('#cnfRoute').val() + '/img/ccb.png');
	game.load.image('cbb', $('#cnfRoute').val() + '/img/cbb.png');
	game.load.image('keySwitch', $('#cnfRoute').val() + '/img/keySwitch.png');
}

function create(){
	getAllNodeDataFromServer();
    createInfoWindow();
}

function update(){
	
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
	
	nodeVisual.scale.set(0.5, 0.5);
	nodeVisual.db_id = node.id;
	
	nodeVisual.inputEnabled = true;
	nodeVisual.events.onInputOver.add(showInfoWindow, this);
	nodeVisual.events.onInputOut.add(hideInfoWindow, this);
	
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
	infoWindow.y -= infoWindow.height;
	//for demo
	
	node = getNodeDataById(sprite.db_id);
	var ip = [(node.serial >> 24) & 255, (node.serial >> 16) & 255, (node.serial >> 8) & 255, (node.serial) & 255];
	var ip_text = ip[0] + "." + ip[1] + "." + ip[2] + "." + ip[3];

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

    if (node['type'] === 'ISC-1') { //ISC1
	display_params.push('serial');
	display_labels.push('Serial');
	display_params.push('communication_status_text');
	display_labels.push('COMM Status');
    display_params.push('isc_key_switch_status_text');
	display_labels.push('Key Switch');	
	display_params.push('cable_fault_text');
	display_labels.push('Cable fault');
	display_params.push('earth_leakage_text');
	display_labels.push('Earth Leakage');
    }

    if (node['type'] === 'IB651') { //IB651
	display_params.push('serial');
	display_labels.push('Serial');
	display_params.push('communication_status_text');
	display_labels.push('COMM Status');
	display_params.push('key_switch_status_text');
	display_labels.push('Key Switch');
	display_params.push('detonator_status_text');
	display_labels.push('Detonator status');
	display_params.push('booster_fired_lfs_text');
	display_labels.push('Booster Fired');
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
    }
	
	if (node['type'] === 'EDD') { //EDD
	//display_params.push('delay');
	//display_labels.push('Delay');
	display_params.push('communication_status_text');
	display_labels.push('COMM Status');
	display_params.push('tagged_text');
	display_labels.push('Tagged Status');
	display_params.push('detonator_status_text');
	display_labels.push('Connection Status');
	display_params.push('booster_fired_lfs_text');
	display_labels.push('Fired');
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
			updateNodeVisual(nodeVisuals.id, node.Node);
		else
			createNodeVisual(node.Node);
	});
	
}

function updateNodeVisual(nodeVisual){
	if(node.key_switch_status)
		nodeVisual.keySwitch.alpha = 1;
	else
		nodeVisual.keySwitch.alpha = 0;
	
	if(node.communication_status)
		nodeVisual.alpha = 1;
	else
		nodeVisual.alpha = 0.5;
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