/**
 * 
 */

$( document ).ready( function() {
	var moretext = "Read More";
    var lesstext = "Read Less";
	$( ".more-link" ).click( function() {
		if ( $( this ).hasClass( "less" ) ) {
			$( this ).removeClass( "less" );
			    $( this ).html( moretext );
			} else {
			    $( this ).addClass( "less" );
			    $( this ).html( lesstext );
		}
		$( this ).parent().children( ".more-skip" ).toggle();
		$( this ).prev().toggle();
		return false;
	} );
	
	$( "#list-max" ).change( function() {
		var url = window.location.href.replace( /\&max\=[0-9]+/g, "" );
		window.location = url + "&max=" + $( this ).val();
	} );
});