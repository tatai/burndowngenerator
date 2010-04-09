var Burndown = new Class({
	initialize : function() {
		$$('fieldset').each(function(fieldset) {
			var div = fieldset.getElement('div.fold');
			var handle = fieldset.getElement('legend');

			if(div && handle) {
				var slide = new Fx.Slide(div);
				if(div.hasClass('close')) {
					slide.hide();
				}
				handle.addEvent('click', function() {
					slide.toggle()
				});
				handle.setStyle('cursor', 'pointer');
			}
		});
	}
});

window.addEvent('domready', function() {
	new Burndown();
});
