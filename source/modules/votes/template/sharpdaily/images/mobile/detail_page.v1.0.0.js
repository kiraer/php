function addLoadEvent(func) { 
	var oldonload = window.onload; 
	if (typeof window.onload != 'function') { 
		window.onload = func; 
	} else { 
		window.onload = function() { 
			if (oldonload) { 
				oldonload(); 
			} 
			func(); 
		} 
	} 
}

var slidePanel = {
	element : null,
	eleContent : null,
	elementId : "slidePanel",
	timer : null,
	state : "start",
	
	panelHeight : 50,
	slideUpSpeed : 15,
	slideDownSpeed : 15,
	waitTime : 5,
	
	init : function(elementId) {
		if (typeof elementId != "undefined")		this.elementId = elementId;

		this.element = document.getElementById(this.elementId);
		this.eleContent = document.getElementById(this.elementId + "_content");
		this.state = "start";
	},
	
	startSliding : function() {
		if (!this.element || !this.eleContent)
			return;

		this.element.style.height = this.panelHeight + "px";
		this.eleContent.style.bottom = -this.panelHeight + "px";
		this.element.style.display = "block";
		this.eleContent.style.display = "block";

		this.state = "up";
		this.timer = setInterval("slidePanel.slideUp()", this.slideUpSpeed);
	},
	
	finishWait : function() {
		this.state = "down";
		clearInterval(this.timer);
		this.timer = setInterval("slidePanel.slideDown()", this.slideDownSpeed);
	},
	
	slideUp : function() {
		var newBottom = parseInt(this.eleContent.style.bottom.replace("px", "")) + 1;
		if (newBottom >= 0) {
			newBottom = 0;
			this.state = "wait";
			clearInterval(this.timer);
			this.timer = setInterval("slidePanel.finishWait()", this.waitTime * 1000);
		}
			
		this.eleContent.style.bottom = newBottom + "px";
	},
	
	slideDown : function() {
		var newBottom = parseInt(this.eleContent.style.bottom.replace("px", "")) - 1;
		if (newBottom <= -this.panelHeight) {
			newBottom = this.panelHeight;
			this.state = "end";
			clearInterval(this.timer);
			this.element.style.display = "none";
		}
			
		this.eleContent.style.bottom = newBottom + "px";
	}
}

function startSlidePanel() {
	slidePanel.init("scrollAd");
	setAdAttributes();
	slidePanel.startSliding();
}
function setAdAttributes() {
	var scrollAd = document.getElementById("scrollAd_content");
	for (var i=0; i<scrollAd.children.length; i++) {
		if (scrollAd.children[i].tagName.toUpperCase() == "A") {
			for (var j=0; j<scrollAd.children[i].children.length; j++) {
				if (scrollAd.children[i].children[j].tagName.toUpperCase() == "IMG") {
					try {
						var img = scrollAd.children[i].children[j];
						var image = new Image();
						image.src = img.src;
						var oriWidth = image.width;
						var oriHeight = image.height;
						slidePanel.panelHeight = oriHeight + 30;
						
					} catch(e) {
						slidePanel.panelHeight = 50;
					}
					
					var time = img.getAttribute("alt");
					if (time != null)
						slidePanel.waitTime = time;
					break;
				}
			}
		}
	}
}
addLoadEvent(startSlidePanel);

function videoTracker(art_id, av_id) {
	var xhr;
	try { xhr = new XMLHttpRequest(); }
	catch(e) {
		try { xhr = new ActiveXObject('Msxml2.XMLHTTP'); }
		catch(e) {
			try { xhr = new ActiveXObject('Microsoft.XMLHTTP'); }
			catch(e) {
				xhr = null;
			}
		}
	}
	
	if (!xhr)
		return;

	var msg = "ajax.php@action=videoTrack&art_id="+art_id+"&av_id="+av_id;
	xhr.open("GET", msg, true); 
	xhr.send(null);
}
