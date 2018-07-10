/**
 * 
 */

$( function() {
	$( 'tr.highlight' ).hover( function( event ) {
		var term = $( this ).find( ".term" ).text();
		console.log( term );
		$( "p.querytext" ).mark( term, {
			"separateWordSearch":false,
			"accuracy":{
				"value":"exactly",
				"limiters":["-",",","."]
			}
			});
	}, function() {
		$( "p.querytext" ).unmark();
  });
});