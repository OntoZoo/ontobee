/**
 * 
 */
$( function () {
	function split( val ) {
		return val.split( /,\s*/ );
	}
	
	function extractLast( term ) {
		return split( term ).pop();
	}
	
	$( "#keywords" ).autocomplete({
		source: function( request, response ) {
			$.getJSON( "/api/search?ontology=" + $( "#ontology" ).val(), {
				term: extractLast( request.term )
			}, response );
		},								  
		minLength: 3,
		select: function( event, ui ) {
			var params = ui.item.id.split( /:::/ );
			var url = "/ontology/" + params.shift() + "?iri=" + encodeURIComponent( params.shift() );
			window.location = url;
		}
	});
});


