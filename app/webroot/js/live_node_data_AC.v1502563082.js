var game;
var allNodeData;
var infoWindow;

$(document).ready(function() {

    heigth = (window.innerHeight * window.devicePixelRatio - 50);
    $('#live_div').height(heigth);

    game = new Phaser.Game(800, 800, Phaser.CANVAS, 'live_div', {preload: preload, create: create, update: update, render: render});
	
});

function preload(){
	game.load.spritesheet('infowindow', $('#cnfRoute').val() + '/img/infoWindow.png', 300, 300);
}

function create(){
	getAllNodeDataFromServer();
	infoWindow = game.add.sprite(0, 0, 'infowindow');
}
function update(){
	
}

function render(){
	
}

function getAllNodeDataFromServer() {
    var posting = $.post($('#cnfRoute').val() + "/nodes/get_all_data/");
    posting.done(function(data) {
		allNodeData = data;
    });
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