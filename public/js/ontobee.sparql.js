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
