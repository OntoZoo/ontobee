/**
 * 
 */

$( function() {
	var timer;
	
	$( 'a.term' ).hover( function( event ) {
		var term = $( this );
		if ( timer ) {
			clearTimeout( timer );
			timer = null
		}
		timer = setTimeout( function() {
			var url = term.attr( 'href' );
			url = url.replace( /\/ontology\//, "/api/infobox/" );
			$.getJSON( url, function( response ) {
				data = $.parseJSON( response );
				html = '<div id="infobox">';
				html += '<h3>' + data.type + ':' + data.label + '</h3>';
				html += '<p>\t\tDefinition: ' + data.definition + '</p>';
				if ( data.deprecate ) {
					html += '<span>\t\tDeprecated</span>';
				}
				html += '</div>';
				console.log(html);
				box = $( html );
		        box.css( {top: event.clientY, left: event.clientX } );
				term.append( box );
			});
			
		}, 1000 )
	}, function() {
		clearTimeout( timer );
		timer = null;
		$( '#infobox' ).remove();
  });
});