'use strict';
//var Modernizr;

require.config({
	baseUrl: 'scripts/components',
	paths: {
		jquery:     'jquery',
		domready:   'domReady',
		modernizr:   'modernizr',
	},
	shim: {
		'isotope.js': ['jquery'],
	},
	//urlArgs: 'bust=' +  (new Date()).getTime()
});

require(['jquery', 'modernizr'], function() {

	// Globals variables
	var body = jQuery('body');

	// Page Loader
	var loadingText = jQuery('.loading-text'),
		loadingSwitch = jQuery('body').data('preloader');

	if(loadingText.length && loadingSwitch === 'on') {
		var markText = loadingText.text(),
			wrappedText = '';

		for (var i = 0; i < markText.length; i++) {
			wrappedText += '<span>'+markText.charAt(i)+'</span>';
		}
		loadingText.html(wrappedText);

		require(['velocity'], function() {
			var spanText = loadingText.find('span'),
				increment = 0;

			spanText.each(function(i) {
				spanText.eq(i).velocity({opacity: 1},
					{
						duration: 100,
						delay: 100*i,
						begin: function() {
							loadingText.css({opacity: 1});
						},
						complete: function() { 
							increment++;
							spanText.eq(i).velocity({opacity: 0}, {duration: 100});
							if(increment === spanText.length) {
								jQuery('.intro-container').velocity({opacity: 1}, {
									visibility: 'visible',
									duration: 200,
									complete: function() {
										body.removeAttr('data-preloader').addClass('dom-loaded');										
									}
								});							
							}
						}
					});
			});
		});
	} else {
		body.removeAttr('data-preloader').addClass('dom-loaded');
	}


	require(['browser', 'domready'], function(browser) {

		// Smooth scroll
		if(!(browser.msie && browser.version < 10)) {
			var smoothOn = jQuery('#home').data('smooth-scroll');
			if(smoothOn === 'on') {
				require(['smooth-scroll']);	
			}	
		}
		

		// Viewport actions
		function viewportAction(selector, actions) {
			var count = 0;

			function checkViewport() {
				if(count === 0){
					var el = selector.getBoundingClientRect();
					var scrl = jQuery(window).height() - (el.bottom - el.height);

					if( scrl > 0  && scrl <= scrl+el.height && count === 0) {
						actions();
						count++;
					}
				}
			}

			if(!(browser.msie && browser.version < 10)) {
				checkViewport();
				jQuery(window).scroll(function() {
					checkViewport();
				});
			} else {
				actions();
			}
		}


		// Sections Parallax effect
		var container = jQuery('[data-box-img]');
		if(container.length) {

			if(container.data('box-img') !== 'undefined') {
				container.each(function(index) {

					var boxImg = container.eq(index),
						boxImgData = boxImg.data('box-img'),
						parallaxBox = boxImg.find('.box-img > span');

					//boxImg.prepend('<div class="box-img"><span style="background-image: url("' + boxImgData + '")"></span></div>');
					parallaxBox.css({
						'background-image': 'url("' + boxImgData + '")'
					});

					function scrollEffect() {
						var elCont = container[index],
							el = elCont.getBoundingClientRect(),
							wHeight = jQuery(window).height(),
							scrl =  wHeight-(el.bottom - el.height),
							scrollBox = boxImg.children('.box-img'),
							condition = wHeight+el.height,
							progressCoef = scrl/condition;

						if( scrl > 0  && scrl < condition) {
							scrollBox.css({
								transform: 'translateY('+(progressCoef* 100)+'px)'
							});

							if(el.bottom <= el.height) {
								scrollBox.css({
									opacity: el.bottom/el.height+0.3,
								});
							}

						}
					}

					if(!(browser.msie && browser.version < 10)) {
						scrollEffect();
						jQuery(window).scroll(function() {
							scrollEffect();
						});						
					}


					//jQuery('.box-img').fadeIn(200);
				});
			}
		}

		// Fit Videos iFrames
		var videoIframe = jQuery('iframe[src^="//player.vimeo.com"], iframe[src^="//www.youtube.com"]');
		if(videoIframe.length) {
			require(['fitvids'], function() {
				jQuery('body').fitVids({ customSelector: 'iframe[src^="//player.vimeo.com"], iframe[src^="//www.youtube.com"]'});			
			});
		}

		// Progress Bar
		var progressBar = jQuery('div[data-progress-bar]');
		if(progressBar.length) {
			require(['progress-bar', 'velocity'], function() {
				progressBar.each(function(i) {
					viewportAction(progressBar[i], function() {
						var progressItem = progressBar.eq(i);
						var progressData = progressItem.data('progress-bar');
						progressData.shownQuery = '.show-progress';
						progressData.numQuery = '.value-progress';
						progressItem.NumberProgressBar(progressData);
					});
				});
			});
		}

		// Count up
		var factItems = jQuery('[data-count-up]');
		if(factItems.length) {
			require(['countup', 'velocity'], function() {			

				factItems.each(function(i) {
					var factSingle = factItems.eq(i),
						factSingleData = factItems.eq(i).data('count-up'),
						//factSingleParent = factSingle.parents('li'),
						options = {
							useEasing : true, 
							useGrouping : true, 
							separator : '', 
							decimal : '',
							prefix : '',
							suffix : '' 
						};
					viewportAction(factItems[i], function() {
						var fact = new countUp(factSingle, 0, factSingleData, 0, 2+(i/2), options);
						fact.start(function() {
							//factSingleParent.velocity({ scale: 1.2 }, { loop: 1 });
						});
					});			

				});
			});
		}

		// Liquid effect
		// if(jQuery('[data-liquid]').length) {
		// 	require(['parallax'], function() {
		// 		var liquidArea = jQuery('[data-liquid]');

		// 		liquidArea.each(function(i) {
		// 			liquidArea.eq(i).parallax();
		// 		});
		// 	});
		// }

		// Device Slider
		var devSlider = jQuery('[data-device-slider]');
		if(devSlider.length) {
			require(['velocity', 'device-slider'], function() {
				viewportAction(devSlider[0], function() {
					$('body').deviceSlider({
						autoplay: true
					});				
				});
			});
		}

		// Sudo Slider
		var sudoEl = jQuery('[data-sudo-slider]');
		if(sudoEl.length) {
			require(['sudo-slider'], function() {
				var	options = {
						initCallback: function() {sudoEl.fadeIn(200);},
						speed: 500,
						prevNext: false,
						afterAnimation: function() {
							if(jQuery('.big-tabs').length) {bigTabAfter();}
							if(jQuery('.testimonials-loop').length) {testimonialsAfter();}
						}
					};

				sudoEl.each(function(i) {
					var testSudo = sudoEl.eq(i);
					var sudoData  = testSudo.data('sudo-slider');
					jQuery.extend(sudoData, options);
					testSudo.sudoSlider(sudoData);
				});

				function bigTabAfter() {
					var tabItem = jQuery('.big-tabs li'),
						activeTab = tabItem.eq(2),
						tabContent = jQuery('.big-tabs-content > ul > li'),
						activetabContent = tabContent.eq(2);
					tabItem.removeClass('active-big-tab');
					activeTab.addClass('active-big-tab');
					tabContent.removeClass('active-big-tab-content');
					activetabContent.addClass('active-big-tab-content');
				}

				function testimonialsAfter() {
					var testimonialItem = jQuery('.testimonials-loop > ul > li'),
						activetestimonialItem = testimonialItem.eq(0);
					testimonialItem.removeClass('active-testimonials');
					activetestimonialItem.addClass('active-testimonials');
				}

				// Default actions

				jQuery('.big-tabs-content > ul > li').eq(0).addClass('active-big-tab-content');
				jQuery('.testimonials-loop > ul > li').eq(0).addClass('active-testimonials');

			});
		}

		// Lazzy images
		var lazzyImg = jQuery('img[data-src]');
		if(lazzyImg.length) {
			require(['velocity'], function() {
				lazzyImg.each(function(i) {
					var imgSing = lazzyImg.eq(i),
						imgSrc = imgSing.data('src');
						imgSing.attr('src', imgSrc).velocity({ opacity: 0 }, { duration: 0});
					viewportAction(lazzyImg[i], function() {
						imgSing.velocity('fadeIn', { duration: 400, delay: 200+(i*20) });
					});
				});
			});
		}

		// Zoom Image
		var zoomImg = jQuery('.zoom-img');
		if(zoomImg.length) {
			require(['strip']);
		}

		// Portfolio Masonry
		var portfolioMason = jQuery('[data-masonry]');
		if(portfolioMason.length) {

			require(['isotope', 'bridget', 'imagesloaded'], function(Isotope) {
				portfolioMason.each(function(i) {
					var folio = portfolioMason.eq(i),
						masonrytarget = folio.data('masonry') || 'li';						
					jQuery.bridget( 'isotope', Isotope );

					setTimeout(function() {
						folio.imagesLoaded(function() {
							folio.isotope({
								itemSelector: masonrytarget,
								getSortData: {
								    name: '.portfolio-front h6 b',
								    pubDate: '[data-portfolio-date]',
								    type: '[data-portfolio-type]'
							 	}
							});					
						});
					}, 500);
					
					// Portfolio filters
					var portfolioFilters = jQuery('.portfolio-filters > ul > li');
					if(portfolioFilters.length) {
						portfolioFilters.on('click', 'input', function() {
							var filterValue = this.value;
							folio.isotope({filter: filterValue});
						});

					}

					// Portfolio Sort
					var portfolioSort = jQuery('.sort-by > ul');
					if(portfolioSort.length) {
						portfolioSort.on('click', 'input', function() {
							var sortValue = this.value;
							folio.isotope({sortBy: sortValue});
						});
					}

				});
			});
		}

		// Form helper
		var form = jQuery('form');
		if(form.length) {
			var formInput = form.find('input[type=text], input[type=email],input[type=search], textarea'),
				hasContent = function(input, val) {
					if(val !== '') {
						input.addClass('has-content');
					} else {
						input.removeClass('has-content');
					}
				};

				formInput.each(function() {
					var input = $(this); 
					var currentVal = input.val();
					hasContent(input, currentVal);
				});

			formInput.blur(function() {
				var input = $(this); 
				var currentVal = input.val();
				hasContent(input, currentVal);
			});
		}

		// Vector map
		var vectorMap = jQuery('.vector-map');
		if(vectorMap.length) {
			require(['raphael', 'mapael', 'map-world'], function() {
				vectorMap.mapael({
					map : {
						name : 'world_countries',
						defaultArea: {
							attrs : {
								fill : '#ededed',
								stroke: 'transparent'
							},
							attrsHover: {
								fill : '#cfcfcf'
							}
						}
					},
					plots: {
						'branch' : {
							type : 'square',
							size : 15,
							latitude : 40.7033121, 
							longitude: -73.979681,
							attrs : {
								fill : '#ee1d24',
								transform: 'r45'
							},
							tooltip: {
								content : '<i class="icon-316 font-2x"></i><p>Envato, 909 queen street newyork ny 33026</p> <p>1 234 567890 info@domain.com</p>',
							},
							text : {
									content : 'Branch office',
									attrs : {
										fill : '#000000'
									},
									attrsHover : {
										fill :  '#ee1d24'
									}
							}
						},

						'head' : {
							type : 'square',
							size : 15,
							latitude : -37.8602828, 
							longitude: 145.079616,
							attrs : {
								fill : '#ee1d24',
								transform: 'r45'
							},
							tooltip: {content : '<i class="icon-316 font-2x"></i><p>Envato, 121 king street melbourne vic 3000</p> <p>1 234 567890 info@domain.com</p>'},
							text : {
									content : 'Head office',
									attrs : {
										fill : '#000000'
									},
									attrsHover : {
										fill :  '#ee1d24'
									}
							}
						}
					}
				});
			});
		}

		// Blog sharing
		var blogSharing = jQuery('.blog-sharing');
		if(blogSharing.length) {
			var shareBox = jQuery('.post-share'),
				closeButton = jQuery('.close-box');
			blogSharing.on('click', function(e) {
				e.preventDefault();
				var index = blogSharing.index(this);
				shareBox.removeClass('opened-sharing');
				shareBox.eq(index).addClass('opened-sharing');
			});

			closeButton.on('click', function(e) {
				e.preventDefault();
				var box = jQuery(this).parent();
					box.removeClass('opened-sharing');
			});
		}

		// Prgrogress round
		var progressRound = jQuery('[data-knob]');
		if(progressRound.length) {
			require(['knob', 'velocity'], function() {
				var progressItems = progressRound.find('input'),
					config = {
						thickness: '.1',
						lineCap: 'butt',
						fgColor: '#dddddd',
						bgColor: 'transparent',
						readOnly: true,
						displayInput: true,
						font: 'inherit',
						fontWeight: '300',
						step: 1,
						format: function(c) {
							return c + '%';
						}
					};

				progressItems.each(function(i) {
					var currentItem = progressItems.eq(i),
						currentVal = currentItem.val(),
						customColor = progressRound.eq(i).data('knob'),
						p = 0;

						if(typeof(customColor) === 'string' && customColor.indexOf('#') === 0) {
							config.fgColor = customColor;
						} else {
							config.fgColor = '#dddddd';
						}


					progressRound.eq(i).velocity({opacity: 1});

					viewportAction(progressItems[i], function() {
						var proggressAnimation = setInterval(function(){
							if(p<=currentVal) {
								currentItem.val(p).trigger('change');
								p++;
							} else {
                          		clearInterval(proggressAnimation);
                          		p = 0;
							}
                        }, 50);
					});

					progressItems.eq(i).knob(config);
				});

			});
		}

		// Sticky menu
		var stickyMenu = jQuery('[data-sticky]');
		if(stickyMenu.length) {
			var lastScroll = 0;

			jQuery(window).scroll(function() {
				var el =stickyMenu[0].getBoundingClientRect(),
					st = jQuery(this).scrollTop(),
					mainNav = jQuery('.main-nav');
				if(!mainNav.hasClass('mobile-active')) {
					if(el.bottom-el.height < 0) {
						stickyMenu.height(el.height);
						stickyMenu.addClass('is-sticky');
						if(st < lastScroll) {
							stickyMenu.addClass('scrolled-up');
						} else {
							stickyMenu.removeClass('scrolled-up');
						}
					} else {
						stickyMenu.height('auto');
						stickyMenu.removeClass('is-sticky');
					}
				}
				
				lastScroll = st;				
			});
		}

		// Menu one page
		var menuScroll = jQuery('[data-menu-scroll]');
		if(menuScroll.length) {
			var menuScrollTarget = jQuery('.main-nav ul a'),
				toTop = 0;

			var toTopHtml = '<a href="#home" class="to-top-link"></a>';
			var footerContainer = jQuery('.footer-copyright');

			jQuery(window).scroll(function() {
				toTop = jQuery(this).scrollTop();
			});

			menuScrollTarget.on('click', function(e) {
				var currentTarget = jQuery(this).attr('href');

				if(currentTarget.indexOf('#') === 0) {
					e.preventDefault();
					if(jQuery(currentTarget).length) {
						require(['velocity'], function() {

							var containerScroll;

							if(browser.chrome) {
								containerScroll = jQuery('body');
							} else {
								containerScroll = jQuery('html');
							}
							jQuery(currentTarget).velocity('scroll', { container: containerScroll, duration: 800, offset: -toTop });
						});
					}
				}

			});

			footerContainer.append(toTopHtml);
			jQuery('.to-top-link').on('click', function(e) {
				e.preventDefault();

				require(['velocity'], function() {

					var containerScroll;

					if(browser.chrome) {
						containerScroll = jQuery('body');
					} else {
						containerScroll = jQuery('html');
					}
					jQuery('#home').velocity('scroll', { container: containerScroll, duration: 800, offset: -toTop });
				});

			});
			
		}

		// Mobile menu
		var mobileSwitcher = jQuery('.responsive-menu');
		if(mobileSwitcher.length) {
			var menuSticky = jQuery('[data-sticky]');

			jQuery(window).resize(function() {
				if(jQuery(this).width() > 991) {
					jQuery('.main-nav').removeClass('mobile-active');
					jQuery('.responsive-menu i').removeClass('icon-120').addClass('icon-333');
				}
			});

			mobileSwitcher.on('click', function(e) {
				e.preventDefault();
				var parent = jQuery(this).parent(),
					icon = jQuery(this).find('i');

				if(menuSticky.hasClass('is-sticky')) {
					menuSticky.removeClass('is-sticky');
				}

				if(icon.hasClass('icon-333')) {
					icon.removeClass('icon-333').addClass('icon-120');
				} else {
					icon.removeClass('icon-120').addClass('icon-333');
				}

				parent.toggleClass('mobile-active');
			});
		}

		// Dribbble feed
		var dribbbleFeed = jQuery('[data-dribbble]');
		if(dribbbleFeed.length) {
			require(['dribbble'], function() {
				var dribbbleAuthor = dribbbleFeed.data('dribbble') || '';
				dribbbleFeed.dribbbleFeed({
					username: dribbbleAuthor
				});
			});
		}

	});

});