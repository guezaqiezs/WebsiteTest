var multiSelect = new Class({
	imageLocations:"style/",
	hideDelay:500,
	options: {
		onChange:function(dropDownList,selectedArray){
			
		}
	},

	initialize: function(element, options){
		this.setOptions(options);
		this.container = new Element('div',{'class':'multiSelectContainer',
			'styles':{
				'width':element.offsetWidth+'px','height':22+'px','font-size':element.getStyle('font-size'),'margin':element.getStyle('margin'),'color':element.getStyle('color')},
			
			'events':{
				'mouseenter':function(){
					$clear(this.timer);
					this.button.addClass('hover');
					this.container.addClass('hover');
				}.bind(this),
				'mouseleave':function(){
					this.button.removeClass('hover');
					this.container.removeClass('hover');
					$clear(this.timer);
					this.timer = this.hide.delay(this.hideDelay, this);
				}.bind(this)
			}
		});
		this.value = new Element('p',{'events':{'click':this.toggle.bind(this)}}).injectInside(this.container);
		this.value.setHTML("Select Multiple");
		this.button = new Element('a',{'events':{'click':this.toggle.bind(this)}}).injectInside(this.container);
		this.list = new Element('ul').injectInside(this.container);
		this.ddoptions = [];
		element.getChildren().each(function(el,i){
			var li = new Element('li').setHTML('<a href="#">'+el.innerHTML+'</a>').injectInside(this.list);
			if(!el.innerHTML.match("Select Multiple")){
				$E('a',li).addEvent('click',function(e){
					if(e)e=new Event(e).stop();
					e.target.hasClass('active')?e.target.removeClass('active'):e.target.addClass('active');
					this.updateSelected();
				}.bind(this));
				el.selected?$E('a',li).addClass('active'):false;
			}else{
				$E('a',li).addEvent('click',function(e){
					if(e)e=new Event(e).stop();
				});
			}
			this.ddoptions.push(el.innerHTML);
		},this);
		element.setStyle('display','none');
		this.container.injectBefore(element);
		this.updateSelected();
	},
	updateSelected: function(){
		this.selected = [];
		$ES('a',this.list).each(function(el){
			el.hasClass('active')?this.selected.push(el.innerHTML.trim()):false;
		},this);
		this.value.setHTML(this.selected.length<1?"Select Multiple":this.selected.join(';'));
		this.fireEvent('onChange', [this.container,this.selected]);
	},
	toggle:	function(e){
		if(e)e=new Event(e).stop();
		$clear(this.timer);
		this.list.getStyle('display')=='none'?this.show():this.hide();
	},
	hide: function(){
		this.list.setStyle('display','none');
		this.container.setStyle('z-index','2');
	},
	show: function(){
		this.list.setStyle('display','block');
		this.container.setStyle('z-index','40');
	},
	clear: function(){
		$ES('a',this.list).each(function(el){
			el.removeClass('active');
		},this);
		this.updateSelected();
	}
});

multiSelect.implement(new Events, new Options);

Element.extend({
	multiSelect: function(options) {
		return new multiSelect(this, options);
	}
});