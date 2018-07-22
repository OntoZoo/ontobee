/**
 * 
 */

$( function() {
	$( 'tr.highlight' ).hover( function( event ) {
		var term = $( this ).find( ".term" ).text();
		$( "p.querytext" ).mark( term, {
			"element":"mark",
			"className":'hover',
			"separateWordSearch":false,
			"accuracy":{
				"value":"exactly",
				"limiters":["-",",",".","/","(",")"]
			}
			});
	}, function() {
		var term = $( this ).find( ".term" ).text();
		$( "p.querytext" ).unmark({
			"element":"mark",
			"className":'hover'
		});
  });
});

$( function() {
	$( 'tr.highlight' ).click( function( event ) {
		var term = $( this ).find( ".term" ).text();
		var id = $( this ).find( ".term" ).attr('id');
		if ( $( this ).find( ".term" ).hasClass( 'selected' ) ) {
			$( "p.querytext" ).unmark({
				"element":"mark",
				"className":'click-'+id
			});
			$( this ).find( ".term" ).parent().css( 'background-color', 'white');
		} else {
			$( "p.querytext" ).mark( term, {
				"element":"mark",	
				"className":'click-'+id,
				"separateWordSearch":false,
				"accuracy":{
					"value":"exactly",
					"limiters":["-",",","."]
				}
				});
			$( this ).find( ".term" ).parent().css( 'background-color', 'coral');
		};
		$( this ).find( ".term" ).toggleClass( 'selected' );
	})
})

$( function() {
	$( 'button.exportHTML' ).click( function( event ) {
		var $clone = $( "p.querytext" ).clone();
		$clone.unmark();
		
		$( "a.term" ).each(function( i ) {
			var term = $( this ).text();
			var id = $( this ).attr( 'id' );
			var url = $( this ).parent().prev().find( "a" ).attr( 'href' );
			console.log( url );
			$clone.mark( term, {
				"each":function( node ) {
					$( node ).wrapInner( '<a target="_blank" href="' + url + '"></a>');
					console.log( $( node ) );
				}
			});
		})
		
		var html = $clone.html() + '</br></br>' + $( "div.result_table" ).html();
		console.log( html );
		
		var link = document.createElement('a');
	    link.setAttribute('download', "export.html");
	    link.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(html));
	    link.click(); 
	})
})