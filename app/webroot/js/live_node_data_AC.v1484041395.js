var game;
var LSPrevCamXstr = "prevCamX";
var LSPrevCamYstr = "prevCamY";
var nodes;
var allNodeData; // contains all the node data as received from the server
var nodeVisualgroup;
var nodeVisuals = []; // array of sprites representing all the nodes. Each node might have some children to indicate blast status and key switch status
var lines = [];
var infoWindow;
var temperatureGraph;
var infoWindowlbls = {};
var infoWindowtxts = {};
var infoWindowNumlbls = 15;
var lineGraphics;
var cursors;
var cam_cursor_movement_speed = 15;
var log = new Log(Log.DEBUG, Log.consoleLogger);
var nodeUpdatePeriod = 1000; // default every 5000 (5 seconds)
var changedNodeUpdateOptimization = false; //only update nodes that were changed on server
var curNodeHiglightingMode = "none"; // current highlighting mode. May have any of the values of "node_highlight" select
var currentlyDragging = false;
var debug = false;

// need to wait for dom to initialize so that we can get the live_div div
$(document).ready(function() {

    log.debug('starting');
    
    if ($('#cnfCakeDebugMode').val() > 0)
	debug = true;
    log.debug(debug);

    heigth = (window.innerHeight * window.devicePixelRatio - 50);
    $('#live_div').height(heigth);

    game = new Phaser.Game($('#live_div').width(), $('#live_div').height(), Phaser.CANVAS, 'live_div', {preload: preload, create: create, update: update, render: render});

    // store user settings on window exit
    $(window).unload(function() {
	// Each search input
	var xpos = game.camera.x;
	var ypos = game.camera.y;
	localStorage.setItem(LSPrevCamXstr, xpos);
	localStorage.setItem(LSPrevCamYstr, ypos);
    });

    // update function
    setInterval(function() {
	//alert('hello!');
	updateData(); // user paging is not reset on reload
    }, nodeUpdatePeriod);

    $('#node_highlight').change(function() {
	//alert($(this).text());	
	curNodeHiglightingMode = $(this).val();
	highlightNodes(curNodeHiglightingMode);
    });

    highlightNodes(1);

});

// Phaser funcs -----------------------------------------------------------------------------------

function preload() {
    
    game.load.spritesheet('infowindow', $('#cnfRoute').val() + '/img/infoWindow.png', 300, 300);
	game.load.spritesheet('temperaturegraph', $('#cnfRoute').val() + '/img/temperatureGraph.png', 300, 300);
    
    // images configured by user
    game.load.image('mine_map', $('#cnfBackgroundImg').val());
    game.load.image('bsc1', $('#cnfBc1Img').val());
    game.load.image('isc1', $('#cnfIsc1Img').val());
    game.load.image('ib651', $('#cnfIb651Img').val());
	game.load.image('t001', $('#cnfT001Img').val());
    game.load.image('keySwitchArmed', $('#cnfKeySwitchArmedImg').val());
    game.load.image('keySwitchDisarmed', $('#cnfKeySwitchDisarmedImg').val());
    game.load.image('detonatorConnected', $('#cnfDetonatorConnImg').val());
    game.load.image('detonatorNotConnected', $('#cnfDetonatorNotConnImg').val());
	game.load.image('faultDisplay', $('#cnfFaultDisplayImg').val());
	game.load.image('warning', $('#cnfWarningImg').val());

}


function create() {

    getAllNodeDataFromServerAndFocusNode();

    createKeyboardInputs();

    game.world.setBounds(-1500, -1500, 1000, 1000);
    game.stage.backgroundColor = '#FFFFFF';
    game.camera.x = -100;
    game.camera.y = -100;


    create_mine_map();
    createScreenText();

    // user input
    cursors = game.input.keyboard.createCursorKeys();


    // infoWindow setup	
    infoWindow = game.add.sprite(game.world.centerX, game.world.centerY, 'infowindow');
    infoWindow.alpha = 0.8;
    infoWindow.visible = false;
    infoWindow.anchor.set(0);
    var style = {font: "14px Arial", fill: "#eeeeee", wordWrap: true, wordWrapWidth: infoWindow.width, align: "center"};
    xOffset = 150;
    yOffset = 25;
    for (var i = 0; i < infoWindowNumlbls; i++) {
	infoWindowlbls[i] = game.add.text(20, 20 + yOffset * i, "", style);
	infoWindowtxts[i] = game.add.text(xOffset, 20 + yOffset * i, "", style);
	infoWindow.addChild(infoWindowlbls[i]);
	infoWindow.addChild(infoWindowtxts[i]);
    }
	
	//temperatureGraph setup
	temperatureGraph = game.add.sprite(game.world.centerX, game.world.centerY, 'temperaturegraph');
	infoWindow.alpha = 0.8;
    infoWindow.visible = false;
    infoWindow.anchor.set(0);
	var style = {font: "14px Arial", fill: "#eeeeee", wordWrap: true, wordWrapWidth: infoWindow.width, align: "center"};
	
    // Lines shown behind images
    lineGraphics = game.add.graphics(0, 0);


    game.stage.disableVisibilityChange = true;
    game.canvas.oncontextmenu = function(e) {
	e.preventDefault();
    }

    cursors = game.input.keyboard.createCursorKeys();

    loadUserSettings();
}

function update() {

    update_camera();

    // if we are dragging a node, then update all the lines.
    // TODO: Maybe optimize this to only update dragged node?
    if (currentlyDragging)
	updateLines();
}

function render() {
    //game.debug.cameraInfo(game.camera, 32, 32);
}

// Utility funcs -----------------------------------------------------------------------------------

function getNodeDataById(id) {
    // might need to optimize this if things are getting slow
    for (var i = 0, len = allNodeData.length; i < len; i++) {
	if (allNodeData[i]['Node']['id'] === id)
	    return allNodeData[i]['Node'];
    }
}

/* Checks if any of the immediate children of a node is armed. Immidiate being the direct children, not any grand-children etc
 * 
 * @returns true if none are armed, false otherwise
 */
function checkImmediateChildrenAllNotArmed(db_id) {

    // get all the nodes with parent_id = db_id and if any are armed then return false
    for (index in allNodeData) {

	node = allNodeData[index]['Node'];
	if ((node['parent_id'] === db_id) && (node['key_switch_status_text'] === "ARMED"))
	    return false;
    }

    return true;
}

function getClosestVisualNodeSibling(db_id) {

}

//game.physics.arcade.distanceBetween
function nodeVisualsdistanceSquared(id1, id2) {
    return (nodeVisuals[id1].x - nodeVisuals[id2].x) * (nodeVisuals[id1].x - nodeVisuals[id2].x) + (nodeVisuals[id1].y - nodeVisuals[id2].y) * (nodeVisuals[id1].y - nodeVisuals[id2].y);
}

// get unique array
// pasted from http://stackoverflow.com/questions/11688692/most-elegant-way-to-create-a-list-of-unique-items-in-javascript
// Solution by Eugene Naydenov
function unique(arr) {
    var u = {}, a = [];
    for (var i = 0, l = arr.length; i < l; ++i) {
	if (!u.hasOwnProperty(arr[i])) {
	    a.push(arr[i]);
	    u[arr[i]] = 1;
	}
    }
    return a;
}

// Info Window ----------------------------------------------------------------------------------------------

function showInfoWindow(sprite, pointer) {

    if (game.input.mouse.button == 0)
	return;

    newx = Number(sprite.x) + 50;
    infoWindow.x = newx;
    infoWindow.y = sprite.y;
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

    display_params = ['type', 'serial', 'comment'];
    display_labels = ['Type', 'Serial', 'Comment'];

    for (var i = 0; i < infoWindowNumlbls; i++) {
	infoWindowlbls[i].text = "";
	infoWindowtxts[i].text = "";
    }
	

    if (node['type'] === 'IBC-1') { //IBC1
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
	display_params.push('communication_status_text');
	display_labels.push('COMM Status');
	display_params.push('key_switch_status_text');
	display_labels.push('Key Switch');
	display_params.push('detonator_status_text');
	display_labels.push('Detonator status');
	display_params.push('booster_fired_lfs_text');
	display_labels.push('Booster Fired');
    }
	
	if (node['type'] === 'T-001') { //T-001
	display_params.push('temperature');
	display_labels.push('Temperature(°C)');
    }

    if (debug) {
	//display_params.push('id');
	//display_labels.push('id (DEBUG)');
    }

    for (var i = 0; i < display_params.length; i++) {
	infoWindowlbls[i].text = display_labels[i];
	if (display_params[i] in node && node[display_params[i]])
	    infoWindowtxts[i].text = node[display_params[i]];
    }

}

function hideInfoWindow(sprite, pointer) {
    infoWindow.visible = false;
}

function showTemperatureGraph(sprite, pointer) {

	newx = Number(sprite.x) + 50;
    temperatureGraph.x = newx;
    temperatureGraph.y = sprite.y;
    temperatureGraph.visible = true;
    temperatureGraph.bringToTop();
	
	cam = game.camera;
    if ((Number(temperatureGraph.x) + temperatureGraph.width) > (cam.x + cam.width))
	temperatureGraph.x -= temperatureGraph.width + 100;
    if ((Number(temperatureGraph.y) + temperatureGraph.height) > (cam.y + cam.height))
	temperatureGraph.y -= temperatureGraph.height;
	 
 }
 
 function hideTemperatureGraph(sprite, pointer) {
    temperatureGraph.visible = false;
}

// Node Highlighting ----------------------------------------------------------------------------------------------

// Highligth nodes according to options
// TODO: what to do if data has changed: need to update highlighting!!!
/*  options:
 armed_IB651_detonator_connected
 armed_IB651_detonator_notconnected
 disarmed_IB651_detonator_connected
 disarmed_IB651_detonator_not_connected
 armed_ISC1
 disarmed_ISC1
 comm_lost
 pre_blast_check
 voltages_low
 none
 */
/*
 * For pre blast check:
 IB651 with Detonator Connected, Key-Switch Disarmed	
 ISC-1 Armed with zero IB651s Armed
 ISC-1 Disarmed with one or more IB651s Armed
 */
 
 
 
function highlightNodes(option) {

    //nodeVisuals[db_id].tint = Phaser.Color.toRGBA(0,255,0,0); // red highlight
    //nodeVisuals[db_id].tint = Phaser.Color.toRGBA(0,0,255,255); //cyan
    // nodeVisuals[db_id].blendMode = PIXI.blendModes.ADD; // ghost effect

    ok_col = Phaser.Color.toRGBA(0, 0, 255, 0);
    hl_col = Phaser.Color.toRGBA(0, 0, 255, 0);
    bad_col = Phaser.Color.toRGBA(0, 255, 0, 0);
    neutral_col = Phaser.Color.toRGBA(255, 255, 255, 255);
    use_ghost_effect = false;

    // reset all
    for (db_id in nodeVisuals) {
	nodeVisuals[db_id].tint = neutral_col;
    }

    // apply highlight
    for (db_id in nodeVisuals) {
	nodeData = getNodeDataById(db_id);

	switch (option) {
	    case "armed_IB651_detonator_connected":
		if ((nodeData['type'] === 'IB651') && (nodeData['detonator_status_text'] === "CONNECTED") && (nodeData['key_switch_status_text'] === "ARMED"))
		    nodeVisuals[db_id].tint = hl_col;
		break;
	    case "armed_IB651_detonator_notconnected":
		if ((nodeData['type'] === 'IB651') && (nodeData['key_switch_status_text'] === "ARMED"))
		    nodeVisuals[db_id].tint = hl_col;
		break;
	    case "IB651_detonator_connected":
		if ((nodeData['type'] === 'IB651') && (nodeData['detonator_status_text'] === "CONNECTED"))
		    nodeVisuals[db_id].tint = hl_col;
		break;
	    case "armed_ISC1":
		if ((nodeData['type'] === 'ISC-1') && (nodeData['isc_key_switch_status_text'] === "ON"))
		    nodeVisuals[db_id].tint = hl_col;
		break;
	    case "disarmed_ISC1":
		if ((nodeData['type'] === 'ISC-1') && (nodeData['isc_key_switch_status_text'] === "ISOLATED"))
		    nodeVisuals[db_id].tint = hl_col;
		break;
		case "cable_fault":
		if ( (nodeData['cable_fault_text'] === "Fault Detected"))
		    nodeVisuals[db_id].tint = bad_col;
		break;
		case "earth_leakage":
		if ( (nodeData['earth_leakage_text'] === "Fault Detected"))
		    nodeVisuals[db_id].tint = bad_col;
		break;
		case "booster_fired":
		if ((nodeData['type'] === 'IB651') && (nodeData['booster_fired_lfs_text'] === "FIRED"))
		    nodeVisuals[db_id].tint = hl_col;
		break;
		case "booster_did_not_fire":
		if ((nodeData['type'] === 'IB651') && (nodeData['booster_fired_lfs_text'] !== "FIRED"))
		    nodeVisuals[db_id].tint = bad_col;
		break;
	    case "none":
		break;
	}
    }
}

function removeByValue(array, val) {
    var index = array.indexOf(val);
    if (index > -1) {
	array.splice(index, 1);
    }
}

// Render  ----------------------------------------------------------------------------------------------------------------------

/*
 * Get closest node id, given list of node id's
 */
function getClosestNode(db_id, list) {
    if (list.length === 1)
	return list[0];
    if (list.length < 1)
	return false;
    min_dist = nodeVisualsdistanceSquared(db_id, list[0]);
    closest_node_id = nodeVisuals[list[0]].db_id;
    for (var i = 1; i < list.length; i++) {
		dist = nodeVisualsdistanceSquared(db_id, list[i]);
		if (dist < min_dist) {
			min_dist = dist;
			closest_node_id = list[i];
		}
    }
    return closest_node_id;
}

/*
 * Draws a chain of lines from the parent to the passed children
 */
function drawChain(parent_db_id, children) {
	var length_temp = children.length;
    if (children.length === 0)
	return;
    if (children.length === 1) {
	lineGraphics.moveTo(nodeVisuals[parent_db_id].x, nodeVisuals[parent_db_id].y);
	lineGraphics.lineTo(nodeVisuals[children[0]].x, nodeVisuals[children[0]].y);
	return;
    }

    chain = [];
    // get the first node in the chain: this is the closest node to the parent
    if (length_temp > 0) {
	closestchild_id = getClosestNode(parent_db_id, children);
	chain.push(closestchild_id);
	removeByValue(children, closestchild_id);
    }
    // get a ordered list of all the other children nodes
    for (var i = 0; i < length_temp; i++) {
	nextclosest = getClosestNode(chain[i], children);
	chain.push(nextclosest);
	removeByValue(children, nextclosest);
    }
    // draw line from parent down the chain
    lineGraphics.moveTo(nodeVisuals[parent_db_id].x, nodeVisuals[parent_db_id].y);
    for (var i = 0; i < length_temp; i++)
	lineGraphics.lineTo(nodeVisuals[chain[i]].x, nodeVisuals[chain[i]].y);
}

/*
 * Given a parent id, draw all the chidren lines
 */
function upateLinesForParent(parent_db_id) {
    // get all the children for this node
    ib651_children_ids = [];
    isc1_children_ids = [];
    for (db_id in nodeVisuals) {
	if (nodeVisuals[db_id].parent_id === parent_db_id) {
	    if (db_id == parent_db_id)
		continue;
	    if (nodeVisuals[db_id].type_id === 1)
		isc1_children_ids.push(db_id);
	    else if (nodeVisuals[db_id].type_id === 2)
		ib651_children_ids.push(db_id);
	}
    }

    drawChain(parent_db_id, isc1_children_ids);
    drawChain(parent_db_id, ib651_children_ids);

}

// Update the lines between the nodes:
//  ISC-1 and IB651 nodes should be connected (with graphical lines) in a serial fashion so that each child is 
// connected to the next closest child. ISC-1 and IB651 child nodes will make up 2 unique daisy chains.
function updateLines() {

    //log.debug("updateLines()");

    lineGraphics.clear();
    lineGraphics.lineStyle(1, 0x222222);

    parents = [];
    for (db_id in nodeVisuals)
	parents.push(nodeVisuals[db_id].parent_id);
    parents_unique = unique(parents);

    for (id in parents_unique)
	upateLinesForParent(parents_unique[id]);
}

// Initialize ------------------------------------------------------------------------------------------------------------------

function createNodeVisuals() {

    mayMoveNodes = $('#cnfMayMoveNodes').val();

    //for (var i = 0, len = allNodedata.length; i< len; i++){
    //allNodedata.forEach(function(elem){
    $(allNodeData).each(function(index, elem) {

	//node = elem['Node'];
	node = elem['Node'];
	//node = elem;
	img = 'bsc1';
	if (node['type'] === 'ISC-1')
	    img = 'isc1';
	if (node['type'] === 'IB651')
	    img = 'ib651';
	if (node['type'] === 'T-001')
	    img = 't001';
	nodeVisual = game.add.sprite(node['x'], node['y'], img, 0);

	nodeVisual.anchor.set(0.5);
	nodeVisual.inputEnabled = true;
	if (mayMoveNodes == true)
	    nodeVisual.input.enableDrag(true);
	nodeVisual.input.useHandCursor = true;
	nodeVisual.events.onInputOver.add(showInfoWindow, this);
	nodeVisual.events.onInputOut.add(hideInfoWindow, this);
	nodeVisual.events.onInputOut.add(hideTemperatureGraph, this);
	if (node['type'] === 'T-001')
		nodeVisual.events.onInputOver.add(showTemperatureGraph, this);	
	nodeVisual.events.onDragStop.add(saveNodePosToServer, this);
	nodeVisual.events.onDragStart.add(hideInfoWindow, this);
	nodeVisual.events.onDragStop.add(setDraggingStop, this);
	nodeVisual.events.onDragStart.add(setDraggingStart, this);
	nodeVisual.scale.set(0.5, 0.5);
	nodeVisual.db_id = node['id'];
	nodeVisual.parent_id = parseInt(node['parent_id']);
	nodeVisual.type_id = parseInt(node['type_id']);
	nodeVisuals[node['id']] = nodeVisual;

	// key switch status
	keySwitchStatusArmed = game.add.sprite(nodeVisual.width + 16, -nodeVisual.height, 'keySwitchArmed', 0);
	keySwitchStatusDisarmed = game.add.sprite(nodeVisual.width + 16, -nodeVisual.height, 'keySwitchDisarmed', 0);
	faultDisplay = game.add.sprite(-100, -100, 'faultDisplay', 0);
	warning = game.add.sprite(-98, -98, 'warning', 0);

	nodeVisual.addChild(faultDisplay);
	nodeVisual.addChild(warning);
	nodeVisual.addChild(keySwitchStatusArmed);
	nodeVisual.addChild(keySwitchStatusDisarmed);

	// detonator status
	if (node['type'] === 'IB651') {
	    detonatorStatusConnected = game.add.sprite(nodeVisual.width + 16, 0, 'detonatorConnected', 0);
	    detonatorStatusNotConnected = game.add.sprite(nodeVisual.width + 16, 0, 'detonatorNotConnected', 0);
	    nodeVisual.addChild(detonatorStatusConnected);
	    nodeVisual.addChild(detonatorStatusNotConnected);
	}
      text1 = game.add.text(nodeVisual.width + 16,-nodeVisual.height,"",0);
      text2 = game.add.text(0,85,node['serial'],0);
      text3 = game.add.text(0,85,node['serial'],0);
      text4 = game.add.text(-50,60,node['comment'],0);
      text5 = game.add.text(-50,85,"SN:",0);

      nodeVisual.addChild(text1);
      nodeVisual.addChild(text2);
      nodeVisual.addChild(text3);
      nodeVisual.addChild(text4);
      nodeVisual.addChild(text5);

    });

    updateLines();
    updateNodeVisuals();
}

function setDraggingStart(sprite, pointer) {
    currentlyDragging = true;
}

function setDraggingStop(sprite, pointer) {
    currentlyDragging = false;
}

// Updates specific node's status
// COMM Status - if off, lower alpha
// Key Switch status
// Detonator status
function updateSpecificNodeVisual(db_id) {
    nodeData = getNodeDataById(db_id);

    // comm status
    if (nodeData['communication_status_text'] === "ON")
	nodeVisuals[db_id].alpha = 1;
    else
	nodeVisuals[db_id].alpha = 0.5;

    // key switch status
    keySwArmed = nodeVisuals[db_id].getChildAt(2);
    keySwDisarmed = nodeVisuals[db_id].getChildAt(3);
	faultDisplay =  nodeVisuals[db_id].getChildAt(0);
	warning =  nodeVisuals[db_id].getChildAt(1);
    keySwArmed.alpha = 0;
    keySwDisarmed.alpha = 0;
	faultDisplay.alpha = 0;
	warning.alpha = 0;
    if(nodeData['type_id'] != 1){
		if (nodeData['key_switch_status_text'] === "ARMED")
		keySwArmed.alpha = 1;
		else
		keySwDisarmed.alpha = 1;
	}
	else{
		if (nodeData['key_switch_status_text'] === "ARMED")
		keySwDisarmed.alpha = 1;
		else
		keySwArmed.alpha = 1;
	}
	
	if(nodeData['cable_fault_text'] == 'Fault Detected' || nodeData['earth_leakage_text'] == 'Fault Detected'){
		faultDisplay.alpha = 0.7;
		warning.alpha = 0.4;
	}
	else{
		faultDisplay.alpha = 0;
		warning.alpha = 0;
	}
    // detonator status: specifically Centralized Blasting Box
    if (nodeData['type'] === 'IB651') {
	detStatConn = nodeVisuals[db_id].getChildAt(2);
	detStatNotConn = nodeVisuals[db_id].getChildAt(3);
	detStatConn.alpha = 0;
	detStatNotConn.alpha = 0;
	if (nodeData['detonator_status_text'] === 'CONNECTED')
	    detStatConn.alpha = 1;
	else
	    detStatNotConn.alpha = 1;
    }

    // highlighted
    // nodeVisual[db_id].tint = Phaser.Color.toRGBA(250,0,0,100);
}

// some of the parameters should be visually indicated on the Nodes
function updateNodeVisuals() {
    for (db_id in nodeVisuals) {
	updateSpecificNodeVisual(db_id);
    }
}

// Do ajax call to server to get all the node data
function getAllNodeDataFromServerAndFocusNode() {
    //do ajax call and get data
    var posting = $.post($('#cnfRoute').val() + "/nodes/get_all_data/");
    posting.done(function(data) {
	allNodeData = data;
	createNodeVisuals();
	
	// focus on a specific node if it is configured
	// passed through /nodes/live/node_id    
	if ($('#cnfFocusOnNode').val() !== ''){
	    node_id = Number($('#cnfFocusOnNode').val());
	    if (node_id !== 0){
		game.camera.focusOn(nodeVisuals[node_id]);
		nodeVisuals[node_id].tint = Phaser.Color.toRGBA(0, 0, 0, 255);
	    }
	}
    });
}

// this variable is used to prevent some visual jumping of nodes when it is moved around
// for example the node is moved, the message is sent to the server, in the mean time the update message is retrieved from 
// the server with the old position
var invalidateNextUpdate = false;

function saveNodePosToServer(sprite, pointer) {

    invalidateNextUpdate = true;
    var posting = $.post($('#cnfRoute').val() + "nodes/update_position/", {id: sprite.db_id, x: sprite.x, y: sprite.y});
    posting.done(function(data) {
	if (data['success'] !== 1) {
	    alert(data['reason']);
	}
	invalidateNextUpdate = false;
    });
    posting.fail(function(data) {
	invalidateNextUpdate = false;
    });
    updateLines();
}

function updateNodes() {

    if (typeof allNodeData === 'undefined') {
	return;
    }

    nodes_moved = false;

    for (index in allNodeData) {

	if (invalidateNextUpdate === true) {
	    //log.debug('invalidateNextUpdate = true, skipping!');
	    break;
	}

	node = allNodeData[index]['Node'];
	if (node['id'] in nodeVisuals) { // visual node exists
	    nv = nodeVisuals[node['id']];
	    if (nv.input.isDragged) {
		//log.debug('got dragged node!');
		continue;
	    }
	    nv.x = node['x'];
	    nv.y = node['y'];
	    updateSpecificNodeVisual(node['id']);
	    nodes_moved = true;
	} else { //visual node does not exist yet
		
	img = 'bsc1';
	if (node['type'] === 'ISC-1')
	    img = 'isc1';
	if (node['type'] === 'IB651')
	    img = 'ib651';
	if (node['type'] === 'T-001')
	    img = 't001';
	nodeVisual = game.add.sprite(node['x'], node['y'], img, 0);

	nodeVisual.anchor.set(0.5);
	nodeVisual.inputEnabled = true;
	if (mayMoveNodes == true)
	    nodeVisual.input.enableDrag(true);
	nodeVisual.input.useHandCursor = true;
	nodeVisual.events.onInputOver.add(showInfoWindow, this);
	nodeVisual.events.onInputOut.add(hideInfoWindow, this);
	if (node['type'] === 'T-001')
		nodeVisual.events.onInputOver.add(showTemperatureGraph, this);
	nodeVisual.events.onInputOut.add(hideTemperatureGraph, this);
	nodeVisual.events.onDragStop.add(saveNodePosToServer, this);
	nodeVisual.events.onDragStart.add(hideInfoWindow, this);
	nodeVisual.events.onDragStop.add(setDraggingStop, this);
	nodeVisual.events.onDragStart.add(setDraggingStart, this);
	nodeVisual.scale.set(0.25, 0.25);
	nodeVisual.db_id = node['id'];
	nodeVisual.parent_id = parseInt(node['parent_id']);
	nodeVisual.type_id = parseInt(node['type_id']);
	nodeVisuals[node['id']] = nodeVisual;

	// key switch status
	keySwitchStatusArmed = game.add.sprite(nodeVisual.width + 16, -nodeVisual.height, 'keySwitchArmed', 0);
	keySwitchStatusDisarmed = game.add.sprite(nodeVisual.width + 16, -nodeVisual.height, 'keySwitchDisarmed', 0);
	faultDisplay = game.add.sprite(-100, -100, 'faultDisplay', 0);
	warning = game.add.sprite(-98, -98, 'warning', 0);

	nodeVisual.addChild(faultDisplay);
	nodeVisual.addChild(warning);
	nodeVisual.addChild(keySwitchStatusArmed);
	nodeVisual.addChild(keySwitchStatusDisarmed);

	// detonator status
	if (node['type'] === 'IB651') {
	    detonatorStatusConnected = game.add.sprite(nodeVisual.width + 16, 0, 'detonatorConnected', 0);
	    detonatorStatusNotConnected = game.add.sprite(nodeVisual.width + 16, 0, 'detonatorNotConnected', 0);
	    nodeVisual.addChild(detonatorStatusConnected);
	    nodeVisual.addChild(detonatorStatusNotConnected);
	}
      text1 = game.add.text(nodeVisual.width + 16,-nodeVisual.height,"",0);
      text2 = game.add.text(0,85,node['serial'],0);
      text3 = game.add.text(0,85,node['serial'],0);
      text4 = game.add.text(-50,60,node['comment'],0);
      text5 = game.add.text(-50,85,"SN:",0);

      nodeVisual.addChild(text1);
      nodeVisual.addChild(text2);
      nodeVisual.addChild(text3);
      nodeVisual.addChild(text4);
      nodeVisual.addChild(text5);

	}//aa

    }

    // update higlighting. Some parameters might have changed after the previous update
    highlightNodes(curNodeHiglightingMode);

    //if (nodes_moved)
	//updateLines();
}

// update all the data
// we might be more optimal and only update that which is necessary, but this will be done on an internal network, so network speed will
// not be an issue. The code necessary to implement this will also be a lot more prone to bugs - so lets only optimize if it is neccessary 
function updateData() {
    //do ajax call and get data
    if (invalidateNextUpdate === true)
	return;

    if (currentlyDragging) {
	return;
    }

    if (changedNodeUpdateOptimization == false) {
	var posting2 = $.post($('#cnfRoute').val() + "nodes/get_all_data/");
	posting2.done(function(data) {
	    allNodeData = data;
	    updateNodes();
	});
	return;
    }

    //get the last update time
    var last_modif_time = new Date(0);
    for (index in allNodeData) {
	node = allNodeData[index]['Node'];
	var t = node['modified'].split(/[- :]/);
	// todo: currently subtracts 2 hours by default - find out how to disabled
	modif_time = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
	if (last_modif_time < modif_time)
	    last_modif_time = modif_time;
    }

    //last_modif_time = last_modif_time.toISOString().slice(0, 19).replace('T', ' ');
    // get server time, and also subtract 1 second to make sure we don't miss any records that were updated the same second but
    // not sent during the last update
    last_modif_time.setTime(last_modif_time.getTime() - (last_modif_time.getTimezoneOffset() * 60000) - 1000);
    last_modif_time = last_modif_time.toISOString().slice(0, 19).replace('T', ' ');

    var posting2 = $.post($('#cnfRoute').val() + "nodes/get_all_data/", {updated_after: last_modif_time});
    posting2.done(function(data) {
	for (index in data) {
	    node = data[index]['Node'];
	    for (i in allNodeData)
		if (allNodeData[i]['Node']['id'] === node['id']) {
		    allNodeData[i]['Node'] = node;
		    break;
		}
	}
	updateNodes();
    });
}

var nodeLinesVisible = true;
function toggleNodeLinesVisibility() {
    nodeLinesVisible = !nodeLinesVisible;
    if (nodeLinesVisible)
	updateLines();
    else
	lineGraphics.clear();
}

function createKeyboardInputs() {
    var keyL = game.input.keyboard.addKey(Phaser.Keyboard.L);
    keyL.onDown.add(toggleNodeLinesVisibility, this);
}

function createScreenText() {
    var style2 = {font: "14px Arial", fill: "#222222", wordWrap: true, wordWrapWidth: 500, align: "left"};
    var screentext = game.add.group();

    textheight = 20;
    lkey = game.add.text(0, 0, "", style2);
    lmouse = game.add.text(0, textheight * 1, "", style2);
    rmouse = game.add.text(0, textheight * 2, "", style2);
    screentext.add(lkey);
    screentext.add(lmouse);
    screentext.add(rmouse);
    screentext.x = 20;
    screentext.y = game.height - 100;
    screentext.fixedToCamera = true;
}

function create_mine_map() {
    mine_map = game.add.sprite(0, 0, 'mine_map');
    mine_map.anchor.set(0.5);
    mine_map.scale.x = $('#cnfBackgroundImgSizeMultiply').val()*2;
    ;
    mine_map.scale.y = $('#cnfBackgroundImgSizeMultiply').val()*2;
    ;
    cntrvalue = $('#cnfBackgroundImgContrast').val();

    col = Phaser.Color.getWebRGB(Phaser.Color.createColor(cntrvalue, cntrvalue, cntrvalue));
    mine_map.tint = Phaser.Color.toRGBA(cntrvalue, cntrvalue, cntrvalue, cntrvalue);
    //log.debug(col);
    //mine_map.tint = 0x333333;
}


// do user defined stuff like loading camera position from localStorage
function loadUserSettings() {
    if (('localStorage' in window) && window['localStorage'] !== null) {
	if (LSPrevCamXstr in localStorage) {
	    var xpos = localStorage.getItem(LSPrevCamXstr);
	    game.camera.x = Number(xpos);

	}
	if (LSPrevCamYstr in localStorage) {
	    var ypos = localStorage.getItem(LSPrevCamYstr);
	    game.camera.y = Number(ypos);
	}
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