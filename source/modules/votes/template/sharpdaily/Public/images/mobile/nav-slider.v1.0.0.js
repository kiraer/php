function get_object_position (obj) {
    var curleft = 0;
      var curtop = 0;
      if (obj.offsetParent) {
            do {
                  curleft += obj.offsetLeft;
                  curtop += obj.offsetTop;
            } while (obj = obj.offsetParent);
      }
      return [curleft,curtop];
}

var NavSlider = {
		navNode	  	: document.getElementById('nav-content'),
		sliderNode  : document.getElementById('nav-slider-menu'),
		moreBtn  	: document.getElementById('nav-more-btn'),
		selected	: document.getElementById('nav-selected-item'),
		listWidth	: 82,
		btnWidth	: 66,
		sliderHeigh	: 40,
		isOpen		: false,
		initSlider: function() {
			if(this.navNode) {
				for(i=0; i<this.navNode.children.length; i++) {
					if(this.navNode.children[i].id === this.selected.value) {
						this.navNode.children[i].className = 'selected';
						this.navNode.insertBefore(this.navNode.children[i],this.navNode.children[0]);
					}
				}
				this.navNode.style.display = 'block';
				this.windowChanged();
			}
		},
		windowChanged : function() {
			var iWidth = parseInt( (document.body.clientWidth - this.btnWidth) / this.listWidth );
			if( iWidth < this.navNode.children.length && this.navNode.children.length > 1 ) {
			//if window thinner than navgator put navNode into slider
				for(i=this.navNode.children.length; i>iWidth; i--) {
					this.sliderNode.insertBefore(this.navNode.children[i-1],this.sliderNode.children[0]);
				}
			} else if( iWidth > this.navNode.children.length ) {
				var plusCount = iWidth-this.navNode.children.length;
				for(i=0; i<plusCount; i++) {
					if(this.sliderNode.children[0] && this.sliderNode.children[0].id != 'slider-anchor'){
						this.navNode.appendChild(this.sliderNode.children[0]);
					}
				}
			} 
			//else {document.getElementById('body-weight').innerHTML = document.body.clientWidth;}
			//if there is no sliderNode then hidden 'more' button
			if(this.sliderNode.children[1]) {
				this.moreBtn.style.display = 'block';
			} else {
				this.moreBtn.style.display = 'none';
			}
		},
		sliderVisi : function() {
			if(this.isOpen) {
				this.sliderNode.style.display = 'none';
				this.isOpen = false;
			} else {
				this.sliderNode.style.display = 'block';
				var pos = get_object_position(this.navNode);
				this.sliderNode.style.top = (pos[1]+27)+"px";
				this.isOpen = true;
			}
		}
	};
	NavSlider.initSlider();