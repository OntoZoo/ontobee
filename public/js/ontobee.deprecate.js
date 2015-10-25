/**
 * 
 */

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