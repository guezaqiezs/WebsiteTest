/*
	Mediabox version 0.7.3 - John Einselen (http://iaian7.com)
	updated 24.09.07

	tested in OS X 10.5 using FireFox 3, Flock 2, Opera 9.6, Safari 3, and Camino 1.5
	tested in Windows Vista using Internet Explorer 7, FireFox 2, Opera 9, and Safari 3
	loads flash, flv, quicktime, wmv, and html content in a Lightbox-style window effect.

	based on Slimbox version 1.4 - Christophe Beyls (http://www.digitalia.be)
			 Slimbox Extended version 1.3.1 - Yukio Arita (http://homepage.mac.com/yukikun/software/slimbox_ex/)
			 Videobox Mod version 0.1 - Faruk Can 'farkob' Bilir (http://www.gobekdeligi.com/videobox/)
			 DM_Moviebox.js - Ductchmonkey (http://lib.dutchmoney.com/)
			(licensed same as originals, MIT-style)

	inspired by the grandaddy of them all, Lightbox v2 - Lokesh Dhakar (http://www.huddletogether.com/projects/lightbox2/)

	distributed under the MIT license
*/

var Mediabox = {
	init: function(options){
		this.options = Object.extend({
			resizeDuration: 240,
			resizeTransition: Fx.Transitions.sineInOut,
			overlayOpacity: 0.8,		// Overlay opacity, 0-1
			topDistance: 15,			// Divisor of the total visible window height, higher number = higher Mediabox placement on screen
										// If you wish to change this to an absolute pixel value, scroll down to lines 128 and 129 and swap the commenting slashes
			initialWidth: 360,
			initialHeight: 360,
			defaultWidth: 640,			// Default width (px)
			defaultHeight: 360,			// Default height(px)
			animateCaption: true,		// This is not smooth animation in IE 6 with XML prolog.
										// If your site is XHTML strict with XML prolog, disable this option.
		// Mediaplayer settings and options
			playerpath: 'http://iaian7.com/js/mediaplayer.swf',	// Path to the mediaplayer.swf or flvplayer.swf file
			backcolor:  '0x777777',		// Base color for the controller, color name / hex value (0x000000)
			frontcolor: '0x000000',		// Text and button color for the controller, color name / hex value (0x000000)
			lightcolor: '0x000000',		// Rollover color for the controller, color name / hex value (0x000000)
			fullscreen: 'true',			// Display fullscreen button
			autostart: 'true',			// Automatically plays the video on load
		// Quicktime options (QT plugin used for partial WMV support as well)
			autoplay: 'true',			// Automatically play movie, true / false
			bgcolor: 'black',			// Background color, name / hex value
			controller: 'true',			// Show controller, true / false
		// Flickr options
			fkBGcolor: '#000000',		// Background colour option
			fkFullscreen: 'true',		// Enable fullscreen button
		// Revver options
			revverID: '187866',			// Revver affiliate ID
			revverFullscreen: 'true',	// Fullscreen option
			revverBack: '#000000',		// Background colour
			revverFront: '#ffffff',		// Foreground colour
			revverGrad: '#000000',		// Gradation colour
		// Seesmic options
			ssFullscreen: 'true',		// Fullscreen option for Seesmic
		// Youtube options
			ytAutoplay: '1',			// Auto play, 0=false, 1=true
		// Veoh options
			vhAutoplay: '1',			// Enable autoplay, 0=false 1=true
			vhFullscreen: 'true',		// Enable fullscreen
		// Vimeo options
			vmFullscreen: '1',			// Fullscreen option, 0=false, 1=true
			vmTitle: '1',				// Show video title
			vmByline: '1',				// Show byline
			vmPortrait: '1',			// Show author portrait
			vmColor: '5ca0b5'			// Custom controller colours, hex value minus the #
		}, options || {});

		if(window.ie6 && document.compatMode=="BackCompat"){ this.options.animateCaption = false; }	// IE 6 - XML prolog problem

		this.anchors = [];
		$each(document.links, function(el){
			if (el.rel && el.rel.test(/^mediabox/i)){
				el.onclick = this.click.pass(el, this);
				this.anchors.push(el);
			}
		}, this);
		this.eventKeyDown = this.keyboardListener.bindAsEventListener(this);
		this.eventPosition = this.position.bind(this);
		this.overlay = new Element('div').setProperty('id', 'lbOverlay').injectInside(document.body);
		this.center = new Element('div').setProperty('id', 'lbCenter').setStyles({width: this.options.initialWidth+'px', height: this.options.initialHeight+'px', marginLeft: '-'+(this.options.initialWidth/2)+'px', display: 'none'}).injectInside(document.body);
		this.canvas = new Element('div').setProperty('id', 'lbImage').injectInside(this.center);
		this.bottomContainer = new Element('div').setProperty('id', 'lbBottomContainer').setStyle('display', 'none').injectInside(document.body);
		this.bottom = new Element('div').setProperty('id', 'lbBottom').injectInside(this.bottomContainer);
		new Element('a').setProperties({id: 'lbCloseLink', href: '#'}).injectInside(this.bottom).onclick = this.overlay.onclick = this.close.bind(this);
		this.caption = new Element('div', {'id': 'lbCaption'}).injectInside(this.bottom);
		new Element('div').setStyle('clear', 'both').injectInside(this.bottom);

		/* Build effects */
		var nextEffect = this.nextEffect.bind(this);
		this.fx = {
			overlay: this.overlay.effect('opacity', {duration: 500}).hide(),
			center: this.center.effects({duration: this.options.resizeDuration, transition: this.options.resizeTransition, onComplete: nextEffect}),
			content: this.canvas.effect('opacity', {duration: 500, onComplete: nextEffect}),
			bottom: this.bottomContainer.effect('height', {duration: 400, onComplete: nextEffect})
		};
	},

	click: function(link){
		return this.open(link.href, link.title, link.rel);
	},

	open: function(url, title, rel){
		this.href = url;
		this.title = title;
		this.rel = rel;
		this.position();
		this.setup(true);
		var wh = (window.getHeight() == 0) ? window.getScrollHeight() : window.getHeight();
		var st = document.body.scrollTop  || document.documentElement.scrollTop;
		this.top = st + (wh / this.options.topDistance);
//		this.top = 100;	// this is the code needed for an absolute pixel value, instead of proportional positioning
		this.center.setStyles({top: this.top+'px', display: ''});
		this.fx.overlay.start(this.options.overlayOpacity);
		this.center.className = 'lbLoading';
		return this.loadVideo(url);
	},

	position: function(){
		this.overlay.setStyles({'top': window.getScrollTop()+'px', 'height': window.getHeight()+'px'});
	},

	setup: function(open){
		var aDim = this.rel.match(/[0-9]+/g);													// videobox rel settings
		this.contentsWidth = (aDim && (aDim[0] > 0)) ? aDim[0] : this.options.defaultWidth;		// videobox rel settings
		this.contentsHeight = (aDim && (aDim[1] > 0)) ? aDim[1] : this.options.defaultHeight;	// videobox rel settings

		var elements = $A(document.getElementsByTagName('object'));								// hide page content
		elements.extend(document.getElementsByTagName(window.ie ? 'select' : 'embed'));
		elements.each(function(el){
			if (open) el.lbBackupStyle = el.style.visibility;
			el.style.visibility = open ? 'hidden' : el.lbBackupStyle;
		});

		var fn = open ? 'addEvent' : 'removeEvent';
		window[fn]('scroll', this.eventPosition)[fn]('resize', this.eventPosition);
		document[fn]('keydown', this.eventKeyDown);
		this.step = 0;
	},

	keyboardListener: function(event){
		switch (event.keyCode){
			case 27: case 88: case 67: this.close(); break;
		}
	},

	loadVideo: function(url){
		this.step = 1;

// DailyMotion
		if (url.match(/dailymotion\.com/i)) {
			this.type = 'flash';
			this.object = new SWFObject(url, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// Flickr
		} else if (url.match(/flickr\.com/i)) {
			this.type = 'flashobj';
			var videoId = url.split('/');
			this.videoID = videoId[5];
			this.object = '<object type="application/x-shockwave-flash" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" data="http://www.flickr.com/apps/video/stewart.swf?v=1.173" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"> <param name="flashvars" value="intl_lang=en-us&amp;photo_secret=a8e6cdca81&amp;photo_id='+this.videoID+'"></param> <param name="movie" value="http://www.flickr.com/apps/video/stewart.swf?v=1.173"></param> <param name="bgcolor" value="'+this.options.fkBGcolor+'"></param> <param name="allowFullScreen" value="'+this.options.fkFullscreen+'"></param><embed type="application/x-shockwave-flash" src="http://www.flickr.com/apps/video/stewart.swf?v=1.173" bgcolor="'+this.options.fkBGcolor+'" allowfullscreen="'+this.options.fkFullscreen+'" flashvars="intl_lang=en-us&amp;photo_secret=a8e6cdca81&amp;photo_id='+this.videoID+'" height="'+this.contentsHeight+'" width="'+this.contentsWidth+'"></embed></object>';
// Google Video
		} else if (url.match(/google\.com\/videoplay/i)) {
			this.type = 'flash';
			var videoId = url.split('=');
			this.videoID = videoId[1];
			this.object = new SWFObject("http://video.google.com/googleplayer.swf?docId="+this.videoID+"&autoplay=1&hl=en", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// Metacafe
		} else if (url.match(/metacafe\.com\/watch/i)) {
			this.type = 'flash';
			var videoId = url.split('/');
			this.videoID = videoId[4];
			this.object = new SWFObject("http://www.metacafe.com/fplayer/"+this.videoID+"/.swf", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// MyspaceTV
		} else if (url.match(/myspacetv\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('=');
			this.videoID = videoId[2];
			this.object = new SWFObject("http://lads.myspace.com/videos/vplayer.swf?m="+this.videoID+"&v=2&type=video", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';
//			this.object = '<embed src="http://lads.myspace.com/videos/vplayer.swf" flashvars="m='+this.videoID+'&v=2&type=video" type="application/x-shockwave-flash" allowFullScreen="true" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" bgcolor="#FFFFFF" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
// Revver
		} else if (url.match(/revver\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('/');
			this.videoID = videoId[4];
			this.object = new SWFObject("http://flash.revver.com/player/1.0/player.swf?mediaId="+this.videoID+"&affiliateId="+this.options.revverID+"&allowFullScreen="+this.options.revverFullscreen+"&backColor="+this.options.revverBack+"&frontColor="+this.options.revverFront+"&gradColor="+this.options.revverGrad+"&shareUrl=revver", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';
//			this.object = '<object width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" data="http://flash.revver.com/player/1.0/player.swf?mediaId='+this.videoID+'&affiliateId='+this.options.revverID+'" type="application/x-shockwave-flash" id="revvervideoa17743d6aebf486ece24053f35e1aa23"><param name="Movie" value="http://flash.revver.com/player/1.0/player.swf?mediaId='+this.videoID+'&affiliateId='+this.options.revverID+'"></param><param name="FlashVars" value="allowFullScreen='+this.options.revverFullscreen+'&backColor=#000000&frontColor=#ffffff&gradColor=#000000&shareUrl=revver"></param><param name="AllowFullScreen" value="'+this.options.revverFullscreen+'"></param><param name="AllowScriptAccess" value="always"></param><embed type="application/x-shockwave-flash" src="http://flash.revver.com/player/1.0/player.swf?mediaId='+this.videoID+'&affiliateId='+this.options.revverID+'" pluginspage="http://www.macromedia.com/go/getflashplayer" allowScriptAccess="always" flashvars="allowFullScreen='+this.options.revverFullscreen+'&backColor=#000000&frontColor=#ffffff&gradColor=#000000&shareUrl=revver" allowfullscreen="'+this.options.revverFullscreen+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'"></embed></object>';
// Seesmic
		} else if (url.match(/seesmic\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('/');
			this.videoID = videoId[5];
			this.object = new SWFObject("http://seesmic.com/Standalone.swf?video="+this.videoID, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen',this.options.ssFullscreen);
//			this.type = 'flashobj';
//			this.object = '<object width="'+this.contentsWidth+'" height="'+this.contentsHeight+'"><param name="movie" value="http://seesmic.com/Standalone.swf?video='+this.videoID+'"></param><param name="allowFullScreen" value="'+this.options.ssFullscreen+'" /><embed src="http://seesmic.com/Standalone.swf?video='+this.videoID+'" type="application/x-shockwave-flash" allowFullScreen="'+this.options.ssFullscreen+'" wmode="transparent" allowScriptAccess="sameDomain" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'"></embed></object>';
// YouTube
		} else if (url.match(/youtube\.com\/watch/i)) {
			this.type = 'flash';
			var videoId = url.split('=');
			this.videoID = videoId[1];
			this.object = new SWFObject("http://www.youtube.com/v/"+this.videoID+"&autoplay=1", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// Veoh
		} else if (url.match(/veoh\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('videos/');
			this.videoID = videoId[1];
			this.object = new SWFObject("http://www.veoh.com/videodetails2.swf?permalinkId="+this.videoID+"&id=2907158&player=videodetailsembedded&videoAutoPlay="+this.options.vhAutoplay, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';
//			this.object = '<embed src="http://www.veoh.com/videodetails2.swf?permalinkId='+this.videoID+'&id=2907158&player=videodetailsembedded&videoAutoPlay='+this.options.vhAutoplay+'" allowFullScreen="'+this.options.vhFullscreen+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" bgcolor="#FFFFFF" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
// Viddler
		} else if (url.match(/viddler\.com/i)) {
			var videoId = url.split('/');
			this.videoId1 = videoId[4];
			this.videoId2 = videoId[6];
			this.videoID = "viddler_"+this.videoId1+"_"+this.videoId2;
//			this.type = 'flash';
//			this.object = new SWFObject("http://www.veoh.com/videodetails2.swf?permalinkId="+this.videoID+"&id=2907158&player=videodetailsembedded&videoAutoPlay="+this.options.vhAutoplay, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
//			this.object.addParam('allowscriptaccess','always');
//			this.object.addParam('allowfullscreen','true');
			this.type = 'flashobj';
			this.object = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" id="'+this.videoID+'"><param name="movie" value="http://www.viddler.com/player/e5398221/" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="'+this.options.vdFullscreen+'" /><embed src="http://www.viddler.com/player/e5398221/" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="'+this.options.vdFullscreen+'" name="'+this.videoID+'" ></embed></object>';
// Vimeo
		} else if (url.match(/vimeo\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('/');
			this.videoID = videoId[3];
			this.object = new SWFObject("http://www.vimeo.com/moogaloop.swf?clip_id="+this.videoID+"&amp;server=www.vimeo.com&amp;fullscreen=1&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=5ca0b5", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';
//			this.object = '<object id="mediabox" type="application/x-shockwave-flash" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" data="http://www.vimeo.com/moogaloop.swf?clip_id='+this.videoID+'&amp;server=www.vimeo.com&amp;fullscreen='+this.options.vmFullscreen+'&amp;show_title='+this.options.vmTitle+'&amp;show_byline='+this.options.vmByline+'&amp;show_portrait='+this.options.vmPortrait+'&amp;color=5ca0b5"><param name="quality" value="best" /><param name="allowfullscreen" value="true" /><param name="scale" value="showAll" /><param name="movie" value="http://www.vimeo.com/moogaloop.swf?clip_id='+this.videoID+'&amp;server=www.vimeo.com&amp;fullscreen='+this.options.vmFullscreen+'&amp;show_title='+this.options.vmTitle+'&amp;show_byline='+this.options.vmByline+'&amp;show_portrait='+this.options.vmPortrait+'&amp;color='+this.options.vmColor+'" /></object>';
// 12seconds
		} else if (url.match(/12seconds\.tv/i)) {
			var videoId = url.split('/');
			this.videoID = videoId[5];
//			this.type = 'flash';
//			this.object = new SWFObject("http://embed.12seconds.tv/players/remotePlayer.swf", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
//			this.object.addParam('FlashVars', 'vid='+this.videoID+'');
//			this.object.addParam('allowscriptaccess','always');
//			this.object.addParam('allowfullscreen','true');
			this.type = 'flashobj';
			this.object = '<object type="application/x-shockwave-flash" data="http://embed.12seconds.tv/players/remotePlayer.swf" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" ><param name="movie" value="http://embed.12seconds.tv/players/remotePlayer.swf" /><param name="FlashVars" value="vid='+this.videoID+'"/><embed src="http://embed.12seconds.tv/players/remotePlayer.swf" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" flashvars="vid='+this.videoID+'"></embed></object>';
// Flash .SWF
		} else if (url.match(/\.swf/i)) {
			this.type = 'flash';
			this.object = new SWFObject(url, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// Flash .FLV
		} else if (url.match(/\.flv/i)) {
			this.type = 'flash';
			this.object = new SWFObject(this.options.playerpath+"?file="+url+"&autostart="+this.options.autostart+"&displayheight="+this.contentsHeight+"&allowfullscreen="+this.options.fullscreen+"&usefullscreen="+this.options.fullscreen+"&backcolor="+this.options.backcolor+"&frontcolor="+this.options.frontcolor+"&lightcolor="+this.options.lightcolor, "flvvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';
//			this.object = '<embed src="'+this.options.playerpath+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" allowscriptaccess="always" allowfullscreen="'+this.options.fullscreen+'" flashvars="height='+this.contentsHeight+'&width='+this.contentsWidth+'&file='+url+'$usefullscreen='+this.options.fullscreen+'"/>';
// Quicktime .MOV
		} else if (url.match(/\.mov/i)) {
			this.type = 'qt';
			if (this.options.controller=='true') {this.contentsHeight = (this.contentsHeight*1)+16};
			if (navigator.plugins && navigator.plugins.length) {
				this.object = '<object id="mediabox" standby="loading quicktime..." type="video/quicktime" codebase="http://www.apple.com/qtactivex/qtplugin.cab" data="'+url+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'"><param name="src" value="'+url+'" /><param name="scale" value="aspect" /><param name="controller" value="'+this.options.controller+'" /><param name="autoplay" value="'+this.options.autoplay+'" /><param name="bgcolor" value="'+this.options.bgcolor+'" /><param name="enablejavascript" value="true" /></object>';
			} else {
				this.object = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" standby="loading quicktime..." codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" id="mediabox"><param name="src" value="'+url+'" /><param name="scale" value="aspect" /><param name="controller" value="'+this.options.controller+'" /><param name="autoplay" value="'+this.options.autoplay+'" /><param name="bgcolor" value="'+this.options.bgcolor+'" /><param name="enablejavascript" value="true" /></object>';
			}
// Windows Media .WMV
		} else if (url.match(/\.wmv/i)) {
			this.type = 'wmv';
			if (this.options.controller=='true') {this.contentsHeight = (this.contentsHeight*1)+16};
			if (navigator.plugins && navigator.plugins.length) {
				this.object = '<object id="mediabox" standby="loading windows media..." type="video/x-ms-wmv" data="'+url+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" /><param name="src" value="'+url+'" /><param name="autoStart" value="'+this.options.autoplay+'" /></object>';
			} else {
				this.object = '<object id="mediabox" standby="loading windows media..." classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" type="video/x-ms-wmv" data="'+url+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" /><param name="filename" value="'+url+'" /><param name="showcontrols" value="'+this.options.controller+'"><param name="autoStart" value="'+this.options.autoplay+'" /><param name="stretchToFit" value="true" /></object>';
			}
// DIVX Media .AVI	// Courtesy of Jetstream Jetwave
//		} else if (url.match(/\.avi/i)) {
//			this.type = 'qt';
//			if (this.options.controller=='true') {this.contentsHeight = (this.contentsHeight*1)+16};
//			if (navigator.plugins && navigator.plugins.length) {
//				this.object = '<object codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab" height="'+this.contentsHeight+'" width="'+this.contentsWidth+'" classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616"><param name="autoplay" value="false"><param name="src" value="'+url+'" /><param name="custommode" value="Stage6" /><param name="showpostplaybackad" value="false" /><embed type="video/divx" src="'+url+'" pluginspage="http://go.divx.com/plugin/download/" showpostplaybackad="false" custommode="Stage6" autoplay="false" height="'+this.contentsHeight+'" width="'+this.contentsWidth+'" /></object>';
//			}
// Inline element
		} else if (url.match(/\#mb_/i)) {
			this.type = 'element';
			var Id = url.split('#');
			this.element = Id[1];
			this.object = $(this.element).innerHTML;
// iFrame content
		} else {
			this.type = 'iframe';
			this.iframeId = "lbFrame_"+new Date().getTime();	// Safari would not update iframe content that had a static id.
			this.object = new Element('iframe').setProperties({id: this.iframeId, width: this.contentsWidth, height: this.contentsHeight, frameBorder:0, scrolling:'auto', src:url});
		}

		this.nextEffect();
		return false;
	},

	nextEffect: function(url){
		switch (this.step++){
		case 1:
			this.canvas.style.width = this.bottom.style.width = this.contentsWidth+'px';
			this.canvas.style.height = this.contentsHeight+'px';
			this.caption.innerHTML = this.title;

			if (this.center.clientHeight != this.canvas.offsetHeight){
				this.fx.center.start({height: this.canvas.offsetHeight, width: this.canvas.offsetWidth, marginLeft: -this.canvas.offsetWidth/2});
				break;
			} else if (this.center.clientWidth != this.canvas.offsetWidth){
				this.fx.center.start({height: this.canvas.offsetHeight, width: this.canvas.offsetWidth, marginLeft: -this.canvas.offsetWidth/2});
				break;
			}
			this.step++;

		case 2:
			this.bottomContainer.setStyles({top: (this.top + this.center.clientHeight)+'px', height:'0px', marginLeft: this.center.style.marginLeft, width:this.center.style.width, display: ''});
			this.fx.content.start(1);
			this.step++;

		case 3:
			if (this.type == 'flash'){
				this.object.write(this.canvas);
			} else if (this.type == 'iframe'){
				this.object.injectInside(this.canvas)
			} else {
				this.canvas.setHTML(this.object);
			}
			this.currentObject = document.getElementById('mediabox');
			this.center.className = '';
			break;
			this.step++;

		case 4:
			if (this.options.animateCaption){
				this.fx.bottom.start(0,this.bottom.offsetHeight);
				break;
			}
			this.bottomContainer.style.height = (this.bottom.offsetHeight)+'px';

		case 5:
			this.step = 0;
		}
	},

	close: function(){
			if (this.type == 'qt' && window.webkit) {
				this.currentObject.Stop();	// safari needs to call Stop() to remove the object's audio stream...
			}
			if (navigator.plugins && navigator.plugins.length) {
				this.canvas.setHTML('');
			} else {
				if (window.ie6) {
					this.canvas.innerHTML = '';
				} else {
					this.canvas.innerHTML = '';
				}
			}
			this.currentObject = null;
			this.currentObject = Class.empty;
			this.type = false;

		if (this.step < 0) return;
		this.step = -1;

		for (var f in this.fx) this.fx[f].stop();
		this.center.style.display = this.bottomContainer.style.display = 'none';
		this.fx.overlay.chain(this.setup.pass(false, this)).start(0);
		return false;
	}
};

window.addEvent('domready', Mediabox.init.bind(Mediabox));