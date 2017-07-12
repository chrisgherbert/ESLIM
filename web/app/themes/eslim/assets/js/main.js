/**
 * MAIN JAVASCRIPT FILE
 * Other javascript files concatenated via Gulp
 * User devvars.js and gulpvars.js files to modify Gulp variables
 */

(function($, global) {

	"use strict";

	var website = {

		init: function() {
			this.homeSlider();
			this.mobileMenu();
			this.ui();
		},

		homeSlider: function() {

			$('.js-home-slides').owlCarousel({
				items: 1,
				nav: true,
				dots: false,
				loop: true,
				autoplay: true,
				autoplayTimout: 5000,
				animateOut: 'fadeOut',
				navText: ['<i class="fa fa-chevron-left fa-fw"></i>','<i class="fa fa-chevron-right fa-fw"></i>']
			});

		},

		mobileMenu: function() {

			var transitionEnd = "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend";
			var $drawer = $("#mobileDrawer");
			var $menu = $("#mobileMenu");
			var $trigger = $("#mobileTrigger");
			var $close = $("#mobileMenuClose");
			var settings = { activeClass: "js-active" };

			$trigger.on("click", toggleMenu);

			function toggleMenu(e) {
				e.preventDefault();
				if (!$drawer.hasClass(settings.activeClass)){
					openMenu();
				} else {
					closeMenu();
				}
			}

			function openMenu() {
				$drawer.addClass(settings.activeClass);
				$menu
					.attr({
						"aria-hidden" : "false",
						"tabindex": "-1"
					})
					.on(transitionEnd, function() {
						$(document).on("keydown", onKeyDown);
						$drawer.on("click", onDrawerClick);
						$close.on("click", onCloseClick);
					});
			}

			function closeMenu() {
				$drawer.removeClass(settings.activeClass);
				$menu
					.removeAttr("tabindex")
					.attr("aria-hidden", "true")
					.on(transitionEnd, function() {
						$(document).off("keydown", onKeyDown);
						$drawer.off("click", onDrawerClick);
						$close.off("click", onCloseClick);
					});
			}

			function onDrawerClick(e) {
				if (e.target !== this) {
					return;
				}
				closeMenu();
			}

			function onCloseClick(e) {
				e.preventDefault();
				closeMenu();
			}

			function onKeyDown(e) {
				if (e.keyCode === 27) {
					closeMenu();
				}
			}

		},

		ui: function() {

			/* Smooth scrolling */
			$("a[href^='#']").smoothScroll();

		}

	};

	jQuery(document).ready(function($) {

		website.init();

	});

})(jQuery, window);