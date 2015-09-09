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

function eg1() {
	document.getElementById( "query" ).value = "#This is the first example:\n#Aim: To find all class-containing ontology graphs\n#Max Rows by default: 10 (this can be changed)\n\nprefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>\nprefix owl: <http://www.w3.org/2002/07/owl#>\nSELECT distinct ?graph_uri\nWHERE {\nGRAPH ?graph_uri\n{ ?s rdf:type owl:Class }\n}";
}

function eg2() {
	document.getElementById( "query" ).value = "#Example 2: \n#To find all subclasses of an ontology term\n\nPREFIX obo-term: <http://purl.obolibrary.org/obo/>\nSELECT DISTINCT ?x ?label\nfrom <http://purl.obolibrary.org/obo/merged/OAE>\nWHERE\n{\n?x rdfs:subClassOf obo-term:OAE_0000001.\n?x rdfs:label  ?label.\n}\n";
}

function eg3() {
	document.getElementById( "query" ).value = "#Example 3: \n#To find the number of all class terms of an ontology\n#To find all object properties, use owl:ObjectProperty instead of owl:Class\n#To find all Datatype properties, use owl:AnnotationProperty instead of owl:Class\n#To find all Annotation properties, use owl:DatatypeProperty instead of owl:Class\n\nSELECT count(?s) as ?VO_class_count\nFROM <http://purl.obolibrary.org/obo/merged/VO>\nWHERE\n{\n?s a owl:Class .\n?s rdfs:label ?label .\n\nFILTER regex( ?s, \"VO_\" )\n}\n";
}

function eg4() {
	document.getElementById( "query" ).value = "#Example 4: \n#To retrieve the definitions of all classes that have definitions in an ontology\n#The OBO IAO ontology annotation terms IAO_0000115 (\"definition\") and IAO_0000117 (\"author\") are used. \n\nPREFIX obo-term: <http://purl.obolibrary.org/obo/>\nSELECT ?s ?label ?definition ?author\nFROM <http://purl.obolibrary.org/obo/merged/VO>\n{\n?s a owl:Class .\n?s rdfs:label ?label .\n?s obo-term:IAO_0000115 ?definition .\n?s obo-term:IAO_0000117 ?author .\n}\n";
}

function eg5() {
	document.getElementById( "query" ).value = "#Example 5: \n#To retrieve general annotations of an ontology\n\nPREFIX owl: <http://www.w3.org/2002/07/owl#>\nSELECT DISTINCT ?p ?o \nFROM <http://purl.obolibrary.org/obo/merged/OAE>\nWHERE\n{\n?s a owl:Ontology.\n?s ?p ?o .\n}\n";
}

function eg6() {
	document.getElementById( "query" ).value = "#Example 6: \n#To find the number of genes under human tRNA gene type (OGG_2010009606).\n\nPREFIX obo-term: <http://purl.obolibrary.org/obo/>\nSELECT count(DISTINCT ?x) as ?count\nfrom <http://purl.obolibrary.org/obo/merged/OGG>\nWHERE\n{\n?x rdfs:subClassOf obo-term:OGG_2010009606.\n?x a owl:Class.\n}\n";
}

function eg7() {
	document.getElementById( "query" ).value = "#Example 7: \n#To find out how many mouse genes involving in mitochondrial DNA repair(GO_0043504).\n\nPREFIX obo-term: <http://purl.obolibrary.org/obo/>\nSELECT count(DISTINCT ?s)\nfrom <http://purl.obolibrary.org/obo/merged/OGG-Mm>\nfrom <http://purl.obolibrary.org/obo/merged/GO>\nWHERE\n{\n  #Note: Get OGG-Mm genes associated with GO_0043504\n   ?s a owl:Class .\n   ?s rdfs:label ?labelogg .\n   ?s obo-term:OGG_0000000029 ?annotation .\n   FILTER regex(?annotation, \"GO_0043504\") .\n}";
}
