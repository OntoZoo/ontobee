  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4869243-9']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();


function switch_sparql(){
	var div_sparql=document.getElementById("div_sparql");
	var href_switch_sparql=document.getElementById("href_switch_sparql");
	if (div_sparql.style.display=="none") {
		div_sparql.style.display="";
		href_switch_sparql.innerHTML="Hide SPARQL queries used in this page";
	}
	else {
		div_sparql.style.display="none";
		href_switch_sparql.innerHTML="Show SPARQL queries used in this page";
	}
}


$(function() {
		function split( val ) {
			return val.split( /,\s*/ );
		}
		
		function extractLast( term ) {
			return split( term ).pop();
		}
		
	$( "#keywords" ).autocomplete({
		source: "../getTerm.php?ontology="+$( "#ontology" ).val(),
		minLength: 3,
		select: function( event, ui ) {
			var params = ui.item.id.split( /:::/ );
			window.location = "/browser/rdf.php?o="+$( "#ontology" ).val() + "&iri=" + params.pop();
		}
	});
});
