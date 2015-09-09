/*
 *  $Id: bootstrap.js,v 1.24 2009/01/06 22:16:23 source Exp $
 *
 *  This file is part of the OpenLink Software Ajax Toolkit (OAT) project.
 *
 *  Copyright (C) 2005-2009 OpenLink Software
 *
 *  See LICENSE file for details.
 */

/* handles dynamic library loading */

OAT.Loader.Dependencies = { /* dependency tree */
	ajax:"crypto",
	ajax2:"xml",
	anchor:"win",
	calendar:["drag","notify"],
	color:"drag",
	combobox:"instant",
	combobutton:"instant",
	combolist:"instant",
	connection:"crypto",
	datasource:["jsobj","json","xml","connection","dstransport","ajax2"],
	dav:["grid","tree","toolbar","ajax2","xml","dialog"],
	declarative:"json",
	dereference:"ajax2",
	dialog:["win","dimmer"],
	dimmer:"win",
	dock:["animation","ghostdrag","resize"],
	form:["ajax2","dialog","datasource","formobject","crypto"],
	formobject:["drag","resize","datasource","tab","window"],
	fresnel:"xml",
	ghostdrag:"animation",
	graph:"canvas",
	graphsidebar:"tree",
	graphsvg:["svg","graphsidebar","rdf","dereference"],
	grid:["instant","anchor"],
	linechart:"svg",
	macwin:["drag","resize","simplefx"],
	map:["window","rectwin","layers","roundwin"],
	menu:"animation",
	mswin:["drag","resize"],
	notify:"animation",
	panelbar:"animation",
	piechart:"svg",
	pivot:["ghostdrag","statistics","instant","barchart"],
	quickedit:"instant",
	rdf:"xml",
	rdfbrowser:["rdfstore","tree","anchor","rdftabs","tab","dav","notify"],
	rdfmini:["rdfstore","rdftabs","notify"],
	rdfstore:["rdf","dereference","n3"],
	rectwin:["drag","resize"],
	roundwin:["drag","resize","simplefx"],
	rssreader:"xml",
	schema:["xml"],
	simplefx:"animation",
	slidebar:"animation",
	soap:"ajax2",
	sparkline:"linechart",
	svgsparql:["svg","ghostdrag","geometry"],
	tab:"layers",
	timeline:["slider","tlscale","resize"],
	tree:"ghostdrag",
	webclip:"webclipbinding",
	win:["drag","resize","layers"],
	ws:["xml","soap","ajax2","schema","connection"],
	xmla:["soap","xml","connection"]
}

OAT.Loader.Files = { /* only those whose names differ */
	gmaps:"customGoogleLoader.js",
	ymaps:"customYahooLoader.js",
	openlayers:"OpenLayers.js"
}

OAT.LoaderTMP = { /* second part of loader */
	loadedLibs:[], /* libraries ready to be used */
	loadingLibs:[], /* libraries marked for inclusion */
	loadCallbacks:[], /* features & callbacks to be executed */
	
	loadFeatures:function(features,callback) { /* load all these features and execute callback */
		var allNames = OAT.Loader.makeDep(features); /* dependencies */
		/* distinct values */
		var distinct = {};
		for (var i=0;i<allNames.length;i++) if (!(allNames[i] in distinct)) { distinct[allNames[i]] = 1; }
		var loadList = []; /* list of libraries needed to include */
		for (var name in distinct) { 
			var index = OAT.Loader.loadedLibs.find(name); /* detect whether lib was already included */
			if (index == -1) { loadList.push(name);	}
		}
		
		OAT.Loader.loadCallbacks.push([loadList,callback]); /* all needed, not yet loaded, libs */
		var cpy = [];
		for (var i=0;i<loadList.length;i++) { cpy.push(loadList[i]); }
		for (var i=0;i<cpy.length;i++) { 
			var name = cpy[i];
			var index = OAT.Loader.loadingLibs.find(name);
			if (index == -1) { 
				var fileName = name+".js";
				if (name in OAT.Loader.Files) { fileName = OAT.Loader.Files[name]; }
				OAT.Loader.loadingLibs.push(name);
				OAT.Loader.include(fileName); 
			} /* include only if not in loadingLibs list */
		}
		OAT.Loader.checkLoading();
	},
	
	featureLoaded:function(name) { /* called by libraries when they are loaded */
		OAT.Loader.loadedLibs.push(name); /* add to list of loaded */
		var index = OAT.Loader.loadingLibs.find(name); 
		OAT.Loader.loadingLibs.splice(index,1); /* remove from list of being loaded */
		for (var i=0;i<OAT.Loader.loadCallbacks.length;i++) {
			var list = OAT.Loader.loadCallbacks[i][0];
			var index = list.find(name);
			if (index != -1) { list.splice(index,1); }
		}
		OAT.Loader.checkLoading();
	},
	
	checkLoading:function() { /* check list of loaded libs against TODO list with callbacks */
		var done = []; /* indexes */
		var toExecute = [];
		for (var i=0;i<OAT.Loader.loadCallbacks.length;i++) { /* check all lists for completion */
			var list = OAT.Loader.loadCallbacks[i][0];
			if (!list.length) { /* nothing to be loaded -> execute and mark for removal */
				var ok = false;
				/* check for windows - special delivery */
				if (OAT.Loader.loadedLibs.find("window") != -1) { /* include default window */
					var obj = {
						1:"mswin",
						2:"macwin",
						3:"roundwin",
						4:"rectwin"
					}
					var name = obj[OAT.WindowType()];
					if (OAT.Loader.loadedLibs.find(name) == -1) { /* not yet loaded! */
						var deps = OAT.Loader.makeDep(name);
						for (var j=0;j<deps.length;j++) { /* postpone until all necessary are loaded */
							if (OAT.Loader.loadedLibs.find(deps[j]) == -1) { list.push(deps[j]); }
						}
						if (OAT.Loader.loadingLibs.find(name) == -1) { /* not scheduled! */
							OAT.Loader.loadFeatures(name,false);
						}
					} else { ok = true; }
				} else { ok = true; }
				if (ok) {
					toExecute.push(OAT.Loader.loadCallbacks[i][1]);
					done.push(i);
				}
			} /* if all prerequisites satisfied */
		} /* for all pending callbacks */
		
		/* remove all executed */
		for (var i=done.length-1;i>=0;i--) {
			var index = done[i];
			OAT.Loader.loadCallbacks.splice(index,1);
		}
		for (var i=0;i<toExecute.length;i++) { if (toExecute[i]) { toExecute[i](); } }
	},
	
	startInit:function() { /* check if everything is ready */
		/* to be called when all initial libs are loaded. waits until 'onload' occurs and then continues */
		var ref = function() {
			if (!OAT.Loader.loadOccurred) { 
				setTimeout(ref,200);
				return;
			}	
			
			if (typeof(window._init) == "function") { window._init(); } /* if _init is specified, execute */
			if (OAT.Declarative) { OAT.Declarative.execute(); } /* declarative markup */
			OAT.MSG.send(OAT,OAT.MSG.OAT_LOAD,{});
			if (typeof(window.init) == "function") { window.init(); } /* pass control to userspace */
		}
		ref();
	},

	makeDep:function(features) {
		/* create list of needed libs for this featureset */
		var arr = (typeof(features) == "object" ? features : [features]);
		var result = [];
		for (var i=0;i<arr.length;i++) {
			var f = arr[i];
			if (f != "dom") { result.push(f); } /* historical remains */
			if (f in OAT.Loader.Dependencies) { /* if has dependencies */
				var value = OAT.Loader.Dependencies[f];
				var v = (typeof(value) == "object" ? value : [value]);
				for (var j=0;j<v.length;j++) {
					result.append(OAT.Loader.makeDep(v[j]));
				}
			}
		}
		return result;
	},
	
	start:function() {
		/* initial set of libraries */
		var fl = (window.featureList ? window.featureList : []);
		/* go */
		OAT.Loader.loadFeatures(fl,OAT.Loader.startInit);
	}
}
for (var p in OAT.LoaderTMP) { OAT.Loader[p] = OAT.LoaderTMP[p]; } /* mix to OAT.Loader  */
OAT.LoaderTMP = null;
OAT.Loader.start();
