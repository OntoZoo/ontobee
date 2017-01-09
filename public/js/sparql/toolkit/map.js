/*
 *  $Id: map.js,v 1.17 2009/02/10 17:41:19 source Exp $
 *
 *  This file is part of the OpenLink Software Ajax Toolkit (OAT) project.
 *
 *  Copyright (C) 2005-2009 OpenLink Software
 *
 *  See LICENSE file for details.
 */
/*
	Abstract API atop various mapping engines
	
	var m = new OAT.Map(something, provider, optionsObject, specificOptions)
	m.addTypeControl()
	m.addMapControl()
	m.addTrafficControl()
	m.setMapType(type)
	m.centerAndZoom(lat,lon,zoom)
	m.setZoom(zoom)
	m.getZoom()
	
	marker = m.addMarker(group,lat,lon,file,w,h,callback)
	m.removeMarker(marker)
	m.removeMarkers(group)
	m.openWindow(marker,something)
	m.closeWindow()
	m.optimalPosition(pointArr)
	m.geoCode(addressString,callback)
	m.getPixelFromCoords([lat,lon]);
	m.getCoordsFromPixel([x,y]);
	
*/

OAT.MapData = {
	TYPE_NONE:0,		/* default provider - none */
	TYPE_G:1,		/* google maps */
	TYPE_Y:2,		/* yahoo */
	TYPE_MS:3,		/* msve */
	TYPE_OL:4,		/* openlayers */
	MAP_MAP:1,		/* map type - normal */
	MAP_ORTO:2,		/* satelite */
	MAP_HYB:3,		/* hybrid */
	FIX_NONE:0,		/* do not reposition markers */
	FIX_ROUND1:1,		/* first marker in the center, others on the circle around it */
	FIX_ROUND2:2,		/* circle around empty center */
	FIX_STACK:3		/* stack above first */
}

OAT.Map = function(something, provider, optionsObject, specificOptions) {
	var self = this;
	this.options = {
		fix:OAT.MapData.FIX_NONE,	/* method of reposition of overlapping markers */
		fixDistance:20,			/* new distance after repositioning in px */
		fixEpsilon:15			/* reposition markers closer than fixEpsilon px */ 
	}
	for (var p in optionsObject) { self.options[p] = optionsObject[p]; }
	this.id = 0; /* ms map pins need id */
	this.provider = OAT.MapData.TYPE_NONE;
	this.obj = false;
	this.elm = $(something);
	this.elm.innerHTML = "Map service currently disabled or not available.";
	this.markerArr = [];
	this.layerObj = false;

	this.init = function(provider) {
		OAT.Dom.clear(self.elm);
		switch (provider) { /* create main object */
			case OAT.MapData.TYPE_G: 
				OAT.Dom.clear(self.elm);
				self.obj = new GMap2(self.elm,specificOptions); 
				self.geoCoder = new GClientGeocoder();
			break;
			case OAT.MapData.TYPE_Y: 
				self.obj = new YMap(self.elm,specificOptions); 
				self.geoCodeBuffer = [];
				YEvent.Capture(self.obj,EventsList.onEndGeoCode,function(result){
					var index = -1;
					for (var i=0;i<self.geoCodeBuffer.length;i++) {
						var item = self.geoCodeBuffer[i];
						if (item[0] == result.Address) { index = i; }
					}
					if (index == -1) { return; }
					var cb = self.geoCodeBuffer[index][1];
					self.geoCodeBuffer.splice(index,1);
					if (!result.success) { cb(false); }
					cb([result.GeoPoint.Lat,result.GeoPoint.Lon]);
				});
			break;
			case OAT.MapData.TYPE_MS: 
				self.elm.id = 'our_mapping_element';
				self.obj = new VEMap('our_mapping_element',specificOptions);
					self.obj.LoadMap();
				self.layerObj = new OAT.Layers(100);
			break;
			case OAT.MapData.TYPE_OL: 
				self.obj = new OpenLayers.Map(self.elm,specificOptions);
				var wms = new OpenLayers.Layer.WMS( "OpenLayers WMS", 
					"http://labs.metacarta.com/wms/vmap0", {layers: 'basic'} );
				self.obj.addLayer(wms);
				
				var wms = new OpenLayers.Layer.KaMap("Satellite",
					"http://openlayers.org/world/index.php",{g:"satellite",map:"world"});
				self.obj.addLayer(wms);
				
				self.markersLayer = new OpenLayers.Layer.Markers("Marker Pins");
				self.obj.addLayer(self.markersLayer);		
				
				self.layerObj = new OAT.Layers(100);
			break;
		}
	
		if (self.options.fix != OAT.MapData.FIX_NONE) { /* marker fix */
			switch (provider) { 
				case OAT.MapData.TYPE_G: 
					GEvent.addListener(self.obj,'zoomend',function(){self.fixMarkers();});
				break;
				case OAT.MapData.TYPE_Y: 
					YEvent.Capture(self.obj,EventsList.changeZoom,function(){self.fixMarkers();});
				break;
				case OAT.MapData.TYPE_MS:
					self.obj.AttachEvent("onendzoom",function(){self.fixMarkers();});
				break;
				case OAT.MapData.TYPE_OL:
					self.obj.events.register("move",self.obj,function(){self.fixMarkers();});
				break;
			}
		}

		self.provider = provider;
	}

	this.loadApi = function(provider, callback) {

		OAT.Dom.clear(self.elm);
		div = OAT.Dom.create("div",{float:"left"});

		var img = OAT.Dom.create("img");
		img.src = OAT.Preferences.imagePath + "/Ajax_throbber.gif";
		var txt = OAT.Dom.text(" Map service loading...");

		div.appendChild(img);
		div.appendChild(txt);
		self.elm.appendChild(div);

		OAT.MSG.send(this,OAT.MSG.API_LOADING,{});
		var cb = function() {
			try {
				self.init(provider);
				if (callback) { callback(self); }
			} catch (e) {
				OAT.Dom.clear(self.elm);
				self.elm.innerHTML = "Map service currently disabled or not available.";
				self.provider = OAT.MapData.TYPE_NONE;
			}
			OAT.MSG.send(this,OAT.MSG.API_LOADED,{});
		}
		switch (provider) {
			case OAT.MapData.TYPE_G:
				OAT.Loader.loadFeatures("gmaps",cb);
			break;
			case OAT.MapData.TYPE_Y:
				OAT.Loader.loadFeatures("ymaps",cb);
			break;
			case OAT.MapData.TYPE_MS:
				if(OAT.Browser.isIE) {
					OAT.Loader.loadFeatures("msapi",cb);
				} else {
					OAT.Loader.loadFeatures("atlascompat",function() {OAT.Loader.loadFeatures("msapi",cb);});
				}
			break;
			case OAT.MapData.TYPE_OL:
				OAT.Loader.loadFeatures("openlayers",cb);
			break;
			case OAT.MapData.TYPE_NONE:
				self.elm.innerHTML = "Map service currently disabled or not available.";
				if (callback) { callback(self); }
			break;
			default:
				throw new Error("Wrong map type");
			break;
		}
	}
	/* --- map methods --- */

	this.geoCode = function(addr,callback) {
		
		var cb = function(results) {
			if (!results) { callback(false); return; }
			switch (self.provider) {
				case OAT.MapData.TYPE_G: 
					callback([results.lat(),results.lng()]);
				break;
			} /* switch */
		} /* geocoding results */
		
		switch (self.provider) {
			case OAT.MapData.TYPE_G: 
				self.geoCoder.getLatLng(addr,cb);
			break;
			case OAT.MapData.TYPE_Y: 
				self.geoCodeBuffer.push([addr,callback]);
				self.obj.geoCodeAddress(addr);
			break;
			case OAT.MapData.TYPE_MS: 
				callback(false); /* FIXME: GC support */
			break;
			case OAT.MapData.TYPE_OL: 
				callback(false); /* no GC support */
			break;
			case OAT.MapData.TYPE_NONE:
				callback(false);
			break;
		}
	}
	
	/* repositioning is relative to first marker in group */
	this.newGeoPosition = function(markerGroup,index) {
		/* new position for marker with respect to first marker of his group */
		var marker = markerGroup[index];
		var dx = 0; /* pixel change */
		var dy = 0;
		var dist = self.options.fixDistance;
		switch (self.options.fix) {
			case OAT.MapData.FIX_ROUND1:
				/* first in the middle, skip the repositioning */
				if (index) {
					var ang = 2*Math.PI*index/(markerGroup.length-1);
					dx = dist * Math.cos(ang);
					dy = dist * Math.sin(ang);
				}
			break;
			/* all move */
			case OAT.MapData.FIX_ROUND2:
				var ang = 2*Math.PI*index/markerGroup.length;
				dx = dist * Math.cos(ang);
				dy = dist * Math.sin(ang);
			break;
			case OAT.MapData.FIX_STACK:
				dy = dist * index;
			break;
		}
		var off = self.getNewCoords(marker,[dx,dy]);
		var lat = off[0];
		var lon = off[1];

		switch (self.provider) { 
			case OAT.MapData.TYPE_G: 
				marker.setPoint(new GLatLng(lat,lon));	
			break;
			case OAT.MapData.TYPE_Y: 
				marker.setYGeoPoint(new YGeoPoint(lat,lon)); 
			break;
			case OAT.MapData.TYPE_MS: 
				marker.LatLong.Latitude = lat;
				marker.LatLong.Longitude = lon;
			break;
			case OAT.MapData.TYPE_OL: 
				marker.lonlat.lon = lon;
				marker.lonlat.lat = lat;
			break;
		}
	}
	
	this.getPixelFromCoords = function(geoPosition) {
		switch(self.provider) {
			case OAT.MapData.TYPE_G:
				var ll = new GLatLng(geoPosition[0],geoPosition[1]); 
				var p = self.obj.fromLatLngToDivPixel(ll);
				return [p.x,p.y];
			break;
			case OAT.MapData.TYPE_Y:
				var ll = new YGeoPoint(geoPosition[0],geoPosition[1]);
				var p = self.obj.convertLatLonXY(ll);
				return [p.x,p.y];
			break;
			case OAT.MapData.TYPE_MS:
				var ll = new VELatLong(geoPosition[0],geoPosition[1]);
				var p = self.obj.LatLongToPixel(ll);
				return [p.x,p.y];
			break;
			case OAT.MapData.TYPE_OL:
				var ll = new OpenLayers.LonLat(geoPosition[1],geoPosition[0]);
				var p = self.obj.getPixelFromLonLat(ll);
				return [p.x,p.y];
			break;
		}
	}

	this.getCoordsFromPixel = function(divPosition) {
		switch (self.provider) { 
			case OAT.MapData.TYPE_G: 
				var p = new GPoint(divPosition[0],divPosition[1]);
				var ll = self.obj.fromDivPixelToLatLng(p);
				return [ll.lat(),ll.lng()];
			break;
			case OAT.MapData.TYPE_Y: 
				var p = new YCoordPoint(divPosition[0],divPosition[1]);
				var ll = self.obj.convertXYLatLon(p);
				return [ll.Lat,ll.Lon];
			break;
			case OAT.MapData.TYPE_MS: 
				var z = 1 + self.getZoom();
				var p = new VEPixel(divPosition[0],divPosition[1]);
				var ll = self.obj.PixelToLatLong(p,z);
				return [ll.Latitude,ll.Longitude];
			break;
			case OAT.MapData.TYPE_OL: 
				var p = new OpenLayers.Pixel(divPosition[0],divPosition[1]);
				var ll = self.obj.getLonLatFromPixel(p);
				return [ll.lat,ll.lon];
			break;
		}
	}
	
	this.getNewCoords = function(marker,shiftArray) { /* compute real offsets */
		var p = self.getPixelFromCoords(marker.__coords);
		p[0] += shiftArray[0]; 
		p[1] += shiftArray[1];
		return self.getCoordsFromPixel(p);
	}
	
	this.fixMarkers = function() {
		if (self.markerArr.length < 2) { return; }
		var groups = [];
		/* analyze positions */
		for (var i=0;i<self.markerArr.length;i++) {
			var m = self.markerArr[i];
			var mpos = self.getPixelFromCoords(m.__coords);
			var index = -1;
			for (var j=0;j<groups.length;j++) {
				/* find group for this marker */
				var pivot = groups[j][0];
				var ppos = self.getPixelFromCoords(pivot.__coords); 
				var dx = mpos[0] - ppos[0];
				var dy = mpos[1] - ppos[1];
				var dist = Math.sqrt(dx*dx+dy*dy);
				if (dist <= self.options.fixEpsilon) { index = j; }
			}
			if (index != -1) {
				groups[index].push(m);
			} else {
				groups.push([m]);
			}
		}
		/* create better positions */
		for (var i=0;i<groups.length;i++) {
			var g = groups[i];
			for (var j=0;j<g.length;j++) { /* re-position all markers */
				self.newGeoPosition(g,j);
			}
		} /* for all groups */
		if (self.provider == OAT.MapData.TYPE_OL) { self.obj.layers[2].redraw(); } /* redraw markers layer */
	}
	
	this.addTypeControl = function() {
		switch (self.provider) {
			case OAT.MapData.TYPE_G: self.obj.addControl(new GMapTypeControl()); break;
			case OAT.MapData.TYPE_Y: self.obj.addTypeControl(); break;
			case OAT.MapData.TYPE_OL: self.obj.addControl(new OpenLayers.Control.LayerSwitcher()); break;

		}	
	}
	
	this.addMapControl = function() {
		switch (self.provider) {
			case OAT.MapData.TYPE_G: self.obj.addControl(new GLargeMapControl()); break;
			case OAT.MapData.TYPE_Y: self.obj.addZoomLong(); self.obj.addPanControl(); break;
		}	
	}

	this.addTrafficControl = function() {
		var trafficState = 0;
		switch (self.provider) {
			case OAT.MapData.TYPE_G: 
				if (!window.GTrafficOverlay) { return; }
				var ovr = new GTrafficOverlay(); 
				var btn = OAT.Dom.button("Toggle traffic");
				OAT.Style.apply(btn,{position:"absolute",top:"27px",right:"7px"});
				OAT.Dom.attach(btn,"click",function() {
					trafficState = (trafficState + 1) % 2;
					if (trafficState) { self.obj.addOverlay(ovr); } else { self.obj.removeOverlay(ovr); }
				});
				self.elm.appendChild(btn);
			break;
		}	
	}

	this.setMapType = function(type) {
		switch (self.provider) {
			case OAT.MapData.TYPE_G: 
				switch (type) {
					case OAT.MapData.MAP_MAP: self.obj.setMapType(G_NORMAL_MAP); break;
					case OAT.MapData.MAP_ORTO: self.obj.setMapType(G_SATELLITE_MAP); break;
					case OAT.MapData.MAP_HYB: self.obj.setMapType(G_HYBRID_MAP); break;
				}
			break;
			
			case OAT.MapData.TYPE_Y: 
				switch (type) {
					case OAT.MapData.MAP_MAP: self.obj.setMapType(YAHOO_MAP_REG); break;
					case OAT.MapData.MAP_ORTO: self.obj.setMapType(YAHOO_MAP_SAT); break;
					case OAT.MapData.MAP_HYB: self.obj.setMapType(YAHOO_MAP_HYB); break;
				}
			break;

			case OAT.MapData.TYPE_MS: 
				switch (type) {
					case OAT.MapData.MAP_MAP: self.obj.SetMapStyle(VEMapStyle.Road); break;
					case OAT.MapData.MAP_ORTO: self.obj.SetMapStyle(VEMapStyle.Aerial); break;
					case OAT.MapData.MAP_HYB: self.obj.SetMapStyle(VEMapStyle.Hybrid); break;
				}
			break;
		}	
	}

	this.centerAndZoom = function(lat,lon,zoom) { /* 0 - far, 16 - close */
		switch (self.provider) {
			case OAT.MapData.TYPE_G: self.obj.setCenter(new GLatLng(lat,lon),zoom); break;
			case OAT.MapData.TYPE_Y: self.obj.drawZoomAndCenter(new YGeoPoint(lat,lon),17-zoom); break;
			case OAT.MapData.TYPE_MS: self.obj.SetCenterAndZoom(new VELatLong(lat,lon),zoom+1); break;
			case OAT.MapData.TYPE_OL: self.obj.setCenter(new OpenLayers.LonLat(lon,lat),zoom); break;
		}	
	}
	
	this.setZoom = function(zoom) {
		switch (self.provider) {
			case OAT.MapData.TYPE_G: self.obj.setZoom(zoom); break;
			case OAT.MapData.TYPE_Y: self.obj.setZoomLevel(17-zoom); break;
			case OAT.MapData.TYPE_MS: self.obj.SetZoomLevel(zoom+1); break;
			case OAT.MapData.TYPE_OL: self.obj.zoomTo(zoom); break;
		}	
	}
	
	this.getZoom = function() {
		switch (self.provider) {
			case OAT.MapData.TYPE_G: return self.obj.getZoom(); break;
			case OAT.MapData.TYPE_Y: return 17-self.obj.getZoomLevel(); break;
			case OAT.MapData.TYPE_MS: return self.obj.GetZoomLevel()-1; break;
			case OAT.MapData.TYPE_OL: return self.obj.getZoom(); break;
		}
		return false;
	}
	
	this.optimalPosition = function(pointArr) {
		/* empty markers array would yield incorrect behavior in all apis except google */
		if (!pointArr.length) { return; }

		switch (self.provider) {
			case OAT.MapData.TYPE_G: 
				var bounds = new GLatLngBounds();
				for (var i=0;i<pointArr.length;i++) {
					var point = new GLatLng(pointArr[i][0],pointArr[i][1]);
					bounds.extend(point);
				}
				var clat = (bounds.getNorthEast().lat() + bounds.getSouthWest().lat())/2;
				var clon = (bounds.getNorthEast().lng() + bounds.getSouthWest().lng())/2;
				var autoZoom = self.obj.getBoundsZoomLevel(bounds);
			break;
			
			case OAT.MapData.TYPE_Y: 
				var points = [];
				for (var i=0;i<pointArr.length;i++) {
					var lat = pointArr[i][0];
					var lon = pointArr[i][1];
					var point = new YGeoPoint(lat,lon);
					points.push(point);
				}
				var r = self.obj.getBestZoomAndCenter(points);
				var autoZoom = 17 - r.zoomLevel;
				var clat = r.YGeoPoint.Lat;
				var clon = r.YGeoPoint.Lon;
			break;
			
			case OAT.MapData.TYPE_MS: 
				var points = [];
				for (var i=0;i<pointArr.length;i++) {
					var lat = pointArr[i][0];
					var lon = pointArr[i][1];
					var point = new VELatLong(lat,lon);
					points.push(point);
				}
				self.obj.SetMapView(points);			
				var c = self.obj.GetCenter();
				var clat = c.Latitude;
				var clon = c.Longitude;
				var autoZoom = self.getZoom();
			break;

			case OAT.MapData.TYPE_OL: 
				var points = [];
				var minLat = 180;
				var minLon = 180;
				var maxLat = -180;
				var maxLon = -180;
				for (var i=0;i<pointArr.length;i++) {
					var lat = pointArr[i][0];
					var lon = pointArr[i][1];
					/* resize bounding box */
					if (lat > maxLat) { maxLat = lat; }
					if (lat < minLat) { minLat = lat; }
					if (lon > maxLon) { maxLon = lon; }
					if (lon < minLon) { minLon = lon; }
				}
				var bounds = new OpenLayers.Bounds(minLon,minLat,maxLon,maxLat);
				var c = bounds.getCenterLonLat();
				var clat = c.lat;
				var clon = c.lon;
				var autoZoom = self.obj.getZoomForExtent(bounds);
			break;

		}
		
		self.centerAndZoom(clat,clon,autoZoom);
		self.fixMarkers();
	}
	
	this.openWindow = function(marker,something) {
		var elm = $(something);
		switch (self.provider) {
			case OAT.MapData.TYPE_G:
				marker.openInfoWindow(elm);
			break;
			
			case OAT.MapData.TYPE_Y:
				marker.openSmartWindow(" ");
				var inner = $("ysaeid");
				if (inner) {
					inner.style.position = "relative";
					inner.appendChild(elm);
				} else { alert('Yahoo! Map API change - could not find window to append to.'); }
			break;
			
			case OAT.MapData.TYPE_MS:
				for (var i=0;i<self.markerArr.length;i++) { self.markerArr[i].closeInfoWindow(); }
				var win = new OAT.Window({move:0,close:1,resize:1,width:300,title:"Lookup window"},OAT.WindowData.TYPE_RECT);

				OAT.Dom.attach(win.div,"mousedown",function(event){event.cancelBubble = true;});
				OAT.Dom.attach(win.div,"dblclick",function(event){event.cancelBubble = true;});
				OAT.Dom.attach(win.div,"mousewheel",function(event){event.cancelBubble = true;});
				OAT.Dom.attach(win.div,"scroll",function(event){event.cancelBubble = true;});
				OAT.Dom.attach(win.div,"DOMMouseScroll",function(event){event.cancelBubble = true;});
				
				marker.__win = win;
				win.content.appendChild(elm);
				var dims = OAT.Dom.getWH(elm);
				win.accomodate(elm);
				win.content.style.width = dims[0]+"px";
				win.content.style.height = dims[1]+"px";
				win.onclose = marker.closeInfoWindow;
				
				$(marker.__id).appendChild(marker.__win.div);
				var pos = OAT.Dom.eventPos(event);
				win.anchorTo(0,0);

				/* MSVE doesnt support repositioning markers
				   http://garzilla.net/vemaps/MovePushPin4.aspx
				   moving map to pushpin example
				*/
				
			break;

			case OAT.MapData.TYPE_OL:
				for (var i=0;i<self.markerArr.length;i++) { self.markerArr[i].closeInfoWindow(); }
				/* see documentation for openlayers popup-js.html */
				var win = new OpenLayers.Popup(null,marker.lonlat,new OpenLayers.Size(300,200),"",true,marker.closeInfoWindow);
				win.panMapIfOutOfView = true;
 				marker.__win = win;
				self.obj.addPopup(win,false);
				win.contentDiv.appendChild(elm);
				win.closeDiv.innerHTML = "X";
			break;
		}	
	}
	
	this.closeWindow = function() {
		for (var i=0;i<self.markerArr.length;i++) {
			var marker = self.markerArr[i];
			switch (self.provider) {
				case OAT.MapData.TYPE_G: self.obj.closeInfoWindow(); break;
				case OAT.MapData.TYPE_Y: marker.closeSmartWindow(); break;
				case OAT.MapData.TYPE_MS: marker.closeInfoWindow();	break;
				case OAT.MapData.TYPE_OL: marker.closeInfoWindow();	break;
			} /* switch */
		} /* for all markers */
	}

	/* --- marker methods --- */
	
	this.addMarker = function(group,lat,lon,file,w,h,clickCallback,overCallback) {
		switch (self.provider) {
			case OAT.MapData.TYPE_G: 
				var icon = new GIcon(G_DEFAULT_ICON,file);
				icon.shadow = false;
				icon.printShadow = false;
				icon.printImage = false;
				if (w && h) { icon.iconSize = new GSize(w,h); }
				var marker = new GMarker(new GLatLng(lat,lon),icon);
				self.obj.addOverlay(marker);
				if (clickCallback) { GEvent.addListener(marker,'click',function(event){clickCallback(marker,event);}); }
				if (overCallback) { GEvent.addListener(marker,'mouseover',function(event){overCallback(marker,event);}); }
			break;
			case OAT.MapData.TYPE_Y: 
				var icon = false;
				if (w && h) { var icon = new YImage(file,new YSize(w,h)); }
				var marker = new YMarker(new YGeoPoint(lat,lon),icon);
				self.obj.addOverlay(marker);
				if (clickCallback) { YEvent.Capture(marker,EventsList.MouseClick,function(event){clickCallback(marker,event);}); }
				if (overCallback) { YEvent.Capture(marker,EventsList.MouseOver,function(event){overCallback(marker,event);}); }
			break;
			case OAT.MapData.TYPE_MS:
				self.id++;
				var id = "pin_"+self.id;
				var f = (file ? file : null);
				var marker = new VEPushpin(id,new VELatLong(lat,lon),f,null,null);
				VEPushpin.ShowDetailOnMouseOver = false;
				self.obj.AddPushpin(marker);
				marker.__id = id;
				marker.closeInfoWindow = function() { if (marker.__win) {
						OAT.Dom.unlink(marker.__win.div); 
						marker.__win = false;
					}
				}
				self.layerObj.addLayer(id,"mouseover");
				if (clickCallback) { OAT.Event.attach($(id).firstChild,"click",function(event){clickCallback(marker,event);}); }
				if (overCallback) { OAT.Event.attach($(id).firstChild,"mouseover",function(event){overCallback(marker,event);}); }
			break;
			case OAT.MapData.TYPE_OL: 
				var icon = false;
				if (w && h) { icon = new OpenLayers.Icon(file,new OpenLayers.Size(w,h)); }
			    	var marker = new OpenLayers.Marker( new OpenLayers.LonLat(lon,lat),icon);
			    	self.markersLayer.addMarker(marker);
				marker.closeInfoWindow = function() { if (marker.__win) {
						self.obj.removePopup(marker.__win);	
						marker.__win = false;
					}
				}
				self.layerObj.addLayer(marker.icon.imageDiv,"mouseover");
				if (clickCallback) {
					marker.icon.imageDiv.style.cursor = "pointer";
					OAT.Event.attach(marker.icon.imageDiv,"click",function(event){if (!marker.__win){clickCallback(marker,event);}}); 
				}
				if (overCallback) {
					marker.icon.imageDiv.style.cursor = "pointer";
					OAT.Event.attach(marker.icon.imageDiv,"mouseover",function(event){if (!marker.__win){overCallback(marker,event);}}); 
				}
			break;
		}	

		if (marker) {
			marker.__coords = [lat,lon];
			marker.__group = group;
			self.markerArr.push(marker);
		}
		
		return marker;
	}
	
	this.removeMarker = function(marker) {
		var index = self.markerArr.find(marker);
		self.markerArr.splice(index,1);
		switch (self.provider) {
			case OAT.MapData.TYPE_G: 
				self.obj.removeOverlay(marker);
				self.obj.closeInfoWindow(); 
			break;
			case OAT.MapData.TYPE_Y:
				self.obj.removeOverlay(marker);
				marker.closeSmartWindow(); 
			break;
			case OAT.MapData.TYPE_MS: 
				marker.closeInfoWindow();
				self.layerObj.removeLayer(marker.__id);
				self.obj.DeletePushpin(marker.__id);
			break;
			case OAT.MapData.TYPE_OL: 
				marker.closeInfoWindow();
				self.layerObj.removeLayer(marker);
				self.markersLayer.removeMarker(marker);
			break;
		}	
	}
	
	this.removeMarkers = function(group) {
		var toRemove = [];
		for (var i=0;i<self.markerArr.length;i++) if (self.markerArr[i].__group == group) { toRemove.push(self.markerArr[i]); }
		while (toRemove.length) {
			self.removeMarker(toRemove[0]);
			toRemove.splice(0,1);
		}
	}

} /* OAT.Map() */
OAT.Loader.featureLoaded("map");
