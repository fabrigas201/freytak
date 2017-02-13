$(function () {
	// инициализация слайдера в шапке
	var owl = $('.header__images_slides');
	owl.owlCarousel({
		items: 1,
		loop: true,
		smartSpeed: 2000,
		nav: false,
		dots: true,
		autoplay: true,
		autoplayTimeout: 12000,
		autoplayHoverPause: true,
	});

	// слайдер портретов
	var owl = $('.glory__row');
	owl.owlCarousel({
		items: 4,
		slideBy: 4,
		loop: true,
		smartSpeed: 800,
		nav: false,
		dots: false,
		autoplay: true,
		autoplayTimeout: 10000,
		autoplayHoverPause: true,
	});

	// слайдер изображений в тексте
	var owl = $('.gallery');
	owl.owlCarousel({
		responsive: {
			0: {
				items: 1,
			},
			420: {
				items: 2,
			},
			768: {
				items: 3,
			},
			1230: {
				items: 4,
			},
		},
		slideBy: 1,
		loop: true,
		smartSpeed: 800,
		nav: true,
		navText: [
			'',
			'',
		],
		dots: false,
		autoplay: false,
	});

	$('.mobileNext__text').click(function () {
		$('html, body').stop().animate({
			scrollTop: 0
		}, 500);
		return false;
	});

	$('.js-print').click(function () {
		print();
		return false;
	});

	// старт анимация шапки
	if($('.header__services').length) {

		var service = {
			min: 0,
			max: $('.header__services').offset().top + $('.header__services').height() * 0.68,

		};

		function serviceAnimate (t) {

			if(false) { // показать область применения анимации

				$('body').css('position', 'relative');

				$('body').append('<div style="position: absolute; left:0; top:'+ service.min +'px; width:100%; height:'+ (service.max - service.min) +'px; border-top:1px solid #c00; border-bottom:1px solid #c00; opacity:.5; z-index:1000;"></div>');

			}



			if(service.min <= t && t <= service.max) {

				$('.header__services').addClass('header__services_animate');

			}

		}

		$(window).scroll(function () {

			serviceAnimate($(this).scrollTop());

		});

		serviceAnimate($(window).scrollTop());

	}



	// инициализация попапа календаря

	$('.calendar__month, .calendar__open, .calendar__date').click(function () {

		if(!$(this).closest('.popup').length) {

			if(!$(this).hasClass('calendar__notificate')) {

				$.magnificPopup.open({

					items: {

						src: '#calendar',

						type: 'inline',

					},

					closeMarkup: '<button title="%title%" type="button" class="mfp-close close"><span class="close__text">закрыть</span><span class="close__button"></span></button>',

				});

			}

			else {

				// если нажали на дату с точкой

			}

		}

		return false;

	});



	$('.calendar__nav').click(function () {

		var back = $(this).hasClass('calendar__back'),

			html,

			month;

		$('.calendar__current').fadeOut(300, function () {

			if(back) {

				month = $('.calendar__table_back table').data('month');

			}

			else {

				month = $('.calendar__table_next table').data('month');

			}

			$(this).html(month).fadeIn(300);

		});

		$('.calendar__table_current').fadeOut(500, function () {

			if(back) {

				html = $('.calendar__table_back').html();

			}

			else {

				html = $('.calendar__table_next').html();

			}

			$(this).html(html).fadeIn(500);

		});



	});



	// просмотр событий

	$('.post').click(function () {

		if($(this).find('.post__content').length && !$(this).hasClass('post_active')) {

			$(this).addClass('post_active');

			return false;

		}

	});

	// отключение проходной страницы
	$('.nav__link').each(function () {
		if($(this).html() == 'Юридические услуги' && $(this).parent().children('.nav__second').length == 1) {
			$(this).css('cursor', 'default');
		}
	});
	$('.nav__link').click(function () {
		if($(this).html() == 'Юридические услуги' && $(this).parent().children('.nav__second').length == 1) {
				return false;
		}
	});



	// поиск

	// открытие

	$('.search__open').click(function () {

		$('.search__mobile').stop().fadeIn(500).addClass('search__mobile_open');

	});

	// закрытие

	$('.search__mobileClose').click(function () {

		$('.search__mobile').stop().fadeOut(500).removeClass('search__mobile_open');

	});



	// мобильное меню

	$('.menu__open').click(function () {

		$.magnificPopup.open({

			items: {

				src: '#mobile',

				type: 'inline',

			},

			mainClass: 'menu__opened',

		});

	});



	hs.graphicsDir = '/assets/css/highslide/graphics/'; // не менять

	hs.align = 'center';

	hs.transitions = ['expand', 'crossfade'];

	hs.fadeInOut = true;

	hs.outlineType = 'glossy-dark';

	hs.captionEval = 'this.a.title';

	hs.numberPosition = 'caption';

	hs.dimmingOpacity = 0.66;



	// Add the slideshow providing the controlbar and the thumbstrip

	hs.addSlideshow({
		//slideshowGroup: 'group1',
		interval: 5000,
		repeat: true,
		useControls: true,
		fixedControls: 'fit',
		overlayOptions: {
			position: 'bottom center',
			opacity: .75,
			hideOnMouseOut: false
		},
		thumbstrip: {
			position: 'above',
			mode: 'horizontal',
			relativeTo: 'expander'
		}
	});
	hs.lang.number = "%1 из %2";
	hs.showCredits = false;

	hs.registerOverlay({

		html:'<div class="closebutton close" onclick="return hs.close(this)" title="Закрыть"><span class="close__text">закрыть</span><span class="close__button"></span></div>',

		position:'top right'

	});



	// Make all images animate to the one visible thumbnail

	var miniGalleryOptions1 = {

		thumbnailId: 'thumb1'

	}

});