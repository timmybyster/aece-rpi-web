/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    
    $('.paginated_jquery_table2').dataTable({
        "iDisplayLength": 5,
        "bLengthChange": false,
        /*"aaSorting": [[ 5, "asc" ]]*/  
	"aaSorting" : [],
        "bFilter": false,
	"aoColumnDefs": [
            { "bSortable": false, "aTargets": [0]}
        ] 
    });


    $('.paginated_jquery_table').dataTable({
        "iDisplayLength": 10,
        "bLengthChange": false,
        "aoColumnDefs": [
            { "aaSorting": [ "desc"], "aTargets": [2]}
        ]  
    });
    
    $('.paginated_jquery_table_ajax').dataTable({
	    "bProcessing": true,
	    "bServerSide": true,
	    "sAjaxSource": "index_ajax",
	    "fnServerParams": function (aoData) {
		    aoData.push({"name":"model", "value":"TransporterRate"});
		    aoData.push({"name":"fields", "value": ["Location.id", "TransporterRate.distance", "Plant.id"] });
	    },
	    "sServerMethod": "POST"
    });
    
    
    
    
    $('.inf_scroll').dataTable({
        //"iDisplayLength": 10,
	"scrollY": "400px",
        //"bLengthChange": false,
	//"sScrollY": "500px",
	"dom": "frtiS",
	"deferRender": true,
        "aoColumnDefs": [
            { "aaSorting": [ "desc"], "aTargets": [2]}
        ] 
    });
    
    $('.paginated_jquery_table_specials_admin').dataTable({
        "iDisplayLength": 10,
        "bLengthChange": false,
        "aaSorting" : [],
	"aoColumnDefs": [
            {"bSearchable": false, "aTargets": [-1]}]
    });    

});

