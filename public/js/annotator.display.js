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
				"limiters":["-",",","."]
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
		if ( $( this ).hasClass( 'selected' ) ) {
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
		$( this ).toggleClass( 'selected' );
		
	})
})