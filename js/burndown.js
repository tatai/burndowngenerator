var Burndown = new Class({
	initialize : function() {
		this.doFolding();
		this.addBurndownFormEvents();
		this.addCommentFormEvents();
		this.addLightbox();

		this.preloadImages();
	},

	preloadImages : function() {
		new Element('img', {
			'src' : '/imgs/ico.loading.gif'
		});
	},

	doFolding : function() {
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
	},

	addCommentFormEvents : function() {
		$('comment_form').addEvent('submit', function(e, form) {
			e.stop();

			this.disableSubmit(form);
			this.addSpinner(form);

			this.cleanNotices();

			new Request.JSON({
				'url' : form.get('action'),
				'method' : 'post'
			}).addEvent('success', this.checkCommentErrors.bind(this)).send(form.toQueryString());
		}.bindWithEvent(this, $('comment_form')));
	},

	addBurndownFormEvents : function() {
		$('burndown_form').addEvent('submit', function(e, form) {
			this.addSpinner(form);
		}.bindWithEvent(this, $('burndown_form')));
	},

	addLightbox : function() {
		new multiBox('mb', {
			'overlay' : new overlay()
		}); 
	},

	cleanNotices : function() {
		$('comment_form').getElements('.notice').each(function(el) {
			el.dispose();
		});
	},

	cleanErrors : function() {
		$('comment_form').getElements('.error').each(function(el) {
			el.dispose();
		});
	},

	checkCommentErrors : function(json) {
		this.cleanErrors();
		this.enableSubmit($('comment_form'));
		this.removeSpinner($('comment_form'));
		if(json.ok) {
			var notice = new Element('span', {
				'class' : 'notice',
				'text' : 'Your comment has been sent! Thanks!'
			}).setStyles({
				'opacity' : 0
			}).injectTop($('comment_form').getElement('div.fold'));

			new Fx.Tween(notice, {
				'duration' : 'long',
				'link' : 'chain'
			}).addEvent('chainComplete', function() {
				this.cleanNotices();
			}.bind(this))
				.start('opacity', 0, 1)
				.start('opacity', 1, 1)
				.start('opacity', 1, 1)
				.start('opacity', 1, 1)
				.start('opacity', 1, 1)
				.start('opacity', 1, 0);
		}
		else {
			json.errors.each(function(error) {
				var target = $('INFO_' + error.field);
				if(target) {
					new Element('span', {
						'class' : 'error',
						'text' : error.text
					}).injectInside(target.getParent());
				}
			});
		}
	},

	addSpinner : function(target_form) {
		new Element('img', {
			'src' : '/imgs/ico.loading.gif',
			'class' : 'spinner'
		}).setStyles({
			'margin-left' : '0.5em'
		}).injectAfter(target_form.getElement('input[type="submit"]'));
	},

	removeSpinner : function(target) {
		target_form.getElements('.spinner').each(function(el) {
			el.dispose();
		});
	},

	enableSubmit : function(target) {
		target.getElement('input[type="submit"]').disabled = false;
	},

	disableSubmit : function(target) {
		target.getElement('input[type="submit"]').disabled = true;
	}
});

window.addEvent('domready', function() {
	new Burndown();
});
