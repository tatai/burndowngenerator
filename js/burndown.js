/* 
 * Yet another burndown online generator, http://www.burndowngenerator.com
 * Copyright (C) 2010 Francisco José Naranjo <fran.naranjo@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
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
		if(!$('comment_form')) {
			return false;
		}

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
		if(!$('burndown_form')) {
			return false;
		}

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
		target.getElements('.spinner').each(function(el) {
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
