/**
 * 
 */

$(function() {
	function split( val ) {
		return val.split( /,\s*/ );
	}
	
	function extractLast( term ) {
		return split( term ).pop();
	}
	
	$( "#keywords" ).autocomplete({
		source: function( request, response ) {
			console.log("autocomplete");
			$.getJSON( "/search/?ontology=" + $( "#ontology" ).val(), {
				term: extractLast( request.term )
			}, response );
		},								  
		minLength: 3,
		select: function( event, ui ) {
			var params = ui.item.id.split( /:::/ );
			window.location = "/ontology/" + params.shift() + "?iri=" + params.shift();
		}
	});
});

function switch_deprecate() {
	var div_deprecate = document.getElementById( "div_deprecate" );
	var href_switch_deprecate = document.getElementById( "href_switch_deprecate" );
	if ( div_deprecate.style.display == "none" ) {
		div_deprecate.style.display = "";
		href_switch_deprecate.innerHTML = "Hide Deprecated Terms";
	} else {
		div_deprecate.style.display = "none";
		href_switch_deprecate.innerHTML = "Show Deprecated Terms";
	}
}