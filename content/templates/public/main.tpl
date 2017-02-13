<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>{$mainTitle} — {$title}</title>
	<meta name="keywords" content="{$metaK}">
	<meta name="description" content="{$metaD}">
	<link rel="shortcut icon" href="{get_url('i/favicon.ico')}" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	{style('assets/css/reset.css')}
	{style('assets/css/fonts.css')}
	{style('assets/css/owl.carousel.css')}
	{style('assets/css/magnific-popup.css')}
	{style('assets/css/highslide/highslide.css')}
	{style('assets/css/style.css')}
	{style('assets/css/adaptive.css')}

	<!-- Yandex.Metrika counter -->
	<script type="text/javascript">
		(function (d, w, c) {
			(w[c] = w[c] || []).push(function() {
				try {
					w.yaCounter41419949 = new Ya.Metrika({
						id:41419949,
						clickmap:true,
						trackLinks:true,
						accurateTrackBounce:true,
						webvisor:true
					});
				} catch(e) { }
			});

			var n = d.getElementsByTagName("script")[0],
				s = d.createElement("script"),
				f = function () { n.parentNode.insertBefore(s, n); };
			s.type = "text/javascript";
			s.async = true;
			s.src = "https://mc.yandex.ru/metrika/watch.js";

			if (w.opera == "[object Opera]") {
				d.addEventListener("DOMContentLoaded", f, false);
			} else { f(); }
		})(document, window, "yandex_metrika_callbacks");
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/41419949" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->

</head>
<body class="news-page">
{if isset($page) && $page =='index'}
	{include file=$header_index}
{else}
	{include file=$header_inner}
{/if}

{block name="content"}{/block}
<footer class="footer">
	<div class="container container_footer">
		<div class="container__row">
			<div class="container__col folder hiddenMobile{if $smarty.server.REQUEST_URI|strpos:contact} folder_opened{/if}">
				<div class="form__wrap">
					<form action="{get_url(config('lang.weblang'), 'contact/send')}" method="POST" class="form form_footer">
						<div class="form__border">
							<div class="folder__title arno">{__('write_to_us')}</div>
							<div class="form__line for-text">
								<div class="form__field">
									<input name="remaller_check_email" type="text">
								</div>
							</div>

							<div class="form__line">
								<div class="form__field">
									<input name="email" type="text" placeholder="{__('email')}">
								</div>
							</div>
							<div class="form__line">
								<div class="form__field">
									<input name="name" type="text" placeholder="{__('how_to_address_you')}">
								</div>
							</div>
							<div class="form__line">
								<div class="form__field">
									<textarea name="body" placeholder="{__('text_of_the_letter')}"></textarea>
								</div>
							</div>
						</div>
						<label class="form__submit linkTriangle">
							<input type="submit" value="{__('send_a_letter')}" class="form__submitInput">
						</label>
					</form>
				</div>
			</div>
			<div class="container__col folder hiddenMobile">
				<div class="folder__title arno">{__('menu')}</div>
				<ul class="nav nav_footer">
					{foreach from=$menuFooter item=item}
					<li class="nav__item">
						{if $item -> isIndex == 1}
							<a href="{get_url(config('lang.weblang'))}" class="nav__link">{$item -> title}</a>
						{else}
							<a href="{get_url(config('lang.weblang'), $item -> typeMenu, $item -> alias)}" class="nav__link">{$item -> title}</a>
						{/if}
					</li>
					{foreachelse}
					<li class="nav__item">
						<a href="{get_url(config('lang.weblang'))}" class="nav__link">{__('home')}</a>
					</li>
					{/foreach}
				</ul>

			</div>
			<div class="clear none clear_folder"></div>
			<div class="container__col folder contacts">
				<div class="folder__title arno">
					{__('contacts')}
					<a href="" class="title__link title__link_insert linkTriangle">{__('write_a_letter')}</a>
				</div>
{*				<div class="contacts__item"> *}
				<a href="{get_url(config('lang.weblang'),'contact/kontakty')}" class="contacts__item contacts__item_link">
					<img src="{get_url('assets/images/contacts/map.png')}" alt="" class="contacts__ico">
					<span class="contacts__value">{__('adres_text')}</span>
				</a>
{*				</div> *}
				<a href="mailto:office@freytakandsons.com" class="contacts__item contacts__item_link">
					<img src="{get_url('assets/images/contacts/mail.png')}" alt="" class="contacts__ico">
					<span class="contacts__value">office@freytakandsons.com</span>
				</a>
				<a href="tel:+74952762766" class="contacts__item contacts__item_link">
					<img src="{get_url('assets/images/contacts/phone.png')}" alt="" class="contacts__ico">
					<span class="contacts__value">8 495 276-276-6</span>
				</a>
			</div>

			<div class="container__col folder share hiddenMobile">
				<div class="folder__title arno">{__('share_information')}</div>
				<a href="https://www.facebook.com/sharer/sharer.php?u={$smarty.server.SERVER_NAME}{$smarty.server.REQUEST_URI}" class="share__link" target="_blank">
					<img src="{get_url('assets/images/share/fb.png')}" alt="" class="share__img">
				</a>
				<a href="https://twitter.com/home?status={$smarty.server.SERVER_NAME}{$smarty.server.REQUEST_URI}" class="share__link" target="_blank">
					<img src="{get_url('assets/images/share/tw.png')}" alt="" class="share__img">
				</a>

				<a href="https://www.linkedin.com/shareArticle?mini=true&url={$smarty.server.SERVER_NAME}{$smarty.server.REQUEST_URI}&title={$title}&summary={$smarty.server.REQUEST_URI}&source=Quoted" class="share__link" target="_blank">
					<img src="{get_url('assets/images/share/in.png')}" alt="" class="share__img">
				</a>
				<a href="mailto:eg@freytakandsons.com" class="share__link">
					<img src="{get_url('assets/images/share/email.png')}" alt="" class="share__img">
				</a>
				<a href="" class="share__link js-print">
					<img src="{get_url('assets/images/share/print.png')}" alt="" class="share__img">
				</a>
			</div>
			<div class="clear"></div>
		</div>
		<div class="footer__copyright arno container_footer">
			<div class="footer__copyrightLine">
				<span class="footer__copyrightText">© {date('Y')} &nbsp;&nbsp;{__('all_rights_reserved')}</span>
			</div>
		</div>
		<a href="" class="footer__logo hiddenMobile"></a>
	</div>
	<div class="mobileNext">
		<a href="" class="mobileNext__text arno">{__('back_to_top')}</a>
	</div>
</footer>

	<div id="calendar" class="popup mfp-hide calendar">
		<div class="popup__wrap">
			<div class="container">
				<div class="container__col calendar__actions">
					<div class="title">
						<span class="title__text arno">{__('events_calendar')}</span>
					</div>

					<div class="calendar__title arno">{__('recent_events')}</div>
					{if $events|count}
						{foreach $events as $event}
						{if (int)strtotime($event -> eventDate|date_format:"%Y-%m-%d") <= (int)strtotime($smarty.now|date_format:"%Y-%m-%d") }
						<a href="{get_url(config('lang.weblang'), 'item', $event -> alias)}" class="post post_calendar post_date calendar__notificate calendar__notificate_last">
							<span class="post__date">{$event -> eventDate|date_format:"%d/%m/%Y"}</span>
							<span class="post__title">{$event -> title}</span>
							<span class="post__content">{$event -> descr} <span class="linkTriangle"><span class="linkTriangle__text">{__('more')}</span></span></span>
						</a>
						{/if}
						{/foreach}
					{/if}
					<div class="calendar__push"></div>

					<div class="calendar__title arno">{__('upcoming_events')}</div>
					{if $events|count}
						{foreach $events as $event}
						{if (int)strtotime($event -> eventDate|date_format:"%Y-%m-%d") >= (int)strtotime($smarty.now|date_format:"%Y-%m-%d") }
						<a href="{get_url(config('lang.weblang'), 'item', $event -> alias)}" class="post post_calendar post_date calendar__notificate calendar__notificate_future">
							<span class="post__date">{$event -> eventDate|date_format:"%d/%m/%Y"}</span>
							<span class="post__title">{$event -> title}</span>
							<span class="post__content">{$event -> descr}</span>
						</a>
						{/if}
						{/foreach}
					{/if}
				</div>
				<div class="container__col calendar__block">
					<div id="calendar-modal-block">
					</div>

					<div class="calendar__help">
						<p>
							<a href="{get_url(config('lang.weblang'), 'events')}" class="linkTriangle">{__('subscribe_to_events')}</a>
						</p>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>

	<div class="menu__open">
		<span></span>
		<span></span>
		<span></span>
	</div>

	<div class="mobile mfp-hide" id="mobile">
		<div class="mobile__close"></div>
		<div class="mobile__content">
			<div class="mobile__contacts">
				<div class="mobile__contact">office@freytakandsons.com</div>
				<div class="mobile__contact">8 495 276-276-6</div>
			</div>

			<ul class="nav arno">
				{foreach from=$menu item=item}
				{if $item -> pid == 0 &&  $item -> isHidden == 0}
				<li class="nav__item">
					{if $item -> isIndex == 1}
						<a href="{get_url(config('lang.weblang'))}" class="nav__link">{$item -> title}</a>
					{else}
						<a href="{get_url(config('lang.weblang'), $item -> typeMenu, $item -> alias)}" class="nav__link">{$item -> title}</a>
					{/if}
				</li>
				{/if}
				{foreachelse}
				<li class="nav__item">
					<a href="{get_url(config('lang.weblang'))}" class="nav__link">{__('home')}</a>
				</li>
				{/foreach}
			</ul>

			<div class="share">
				<a href="https://www.facebook.com/sharer/sharer.php?u={$smarty.server.SERVER_NAME}{$smarty.server.REQUEST_URI}" class="share__link" target="_blank">
					<img src="{get_url('assets/images/share/fb_mobile.png')}" alt="" class="share__img">
				</a>
				<a href="https://twitter.com/home?status={$smarty.server.SERVER_NAME}{$smarty.server.REQUEST_URI}" class="share__link" target="_blank">
					<img src="{get_url('assets/images/share/tw_mobile.png')}" alt="" class="share__img">
				</a>
				<a href="https://www.linkedin.com/shareArticle?mini=true&url={$smarty.server.SERVER_NAME}{$smarty.server.REQUEST_URI}&title={$title}&summary={$smarty.server.REQUEST_URI}&source=Quoted" class="share__link" target="_blank">
					<img src="{get_url('assets/images/share/in_mobile.png')}" alt="" class="share__img">
				</a>
				<a href="mailto:eg@freytakandsons.com" class="share__link">
					<img src="{get_url('assets/images/share/email_mobile.png')}" alt="" class="share__img">
				</a>
			</div>
		</div>
	</div>

	<div class="search__mobile">
		<div class="search__mobileClose"></div>
		{include file=$search}
	</div>

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
	<script type="text/javascript" src="{get_url('assets/js/magnific-popup.min.js')}"></script>
	<script type="text/javascript" src="{get_url('assets/js/owl.carousel.min.js')}"></script>
	<script type="text/javascript" src="{get_url('assets/js/highslide-with-gallery.min.js')}"></script>
	<script type="text/javascript" src="{get_url('assets/js/zabuto_calendar.js')}"></script>
	<script type="text/javascript" src="{get_url('assets/js/script.js')}"></script>

	{literal}
	<script type="text/javascript">
    $(document).ready(function () {
        $("#events-calendar,#calendar-modal-block").zabuto_calendar({
			data:[
		{/literal}
			{if $events|count}
				{strip}
				{foreach $events as $event}
					{
						"date":"{$event -> eventDate|date_format:"%Y-%m-%d"}",
						"badge":true,
						"title":"{addslashes($event -> title)}",

						{if (int)strtotime($event -> eventDate|date_format:"%Y-%m-%d") < (int)strtotime($smarty.now|date_format:"%Y-%m-%d") }
						"classname":"calendar__notificate_last"
						{else}
						"classname":"calendar__notificate_future"
						{/if}
					},
				{/foreach}
				{/strip}
			{/if}
		{literal}
			],
			language: "ru",


			action: function () {
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
            },
		});
    });
	</script>
	{/literal}


<script type="text/javascript">
$(function(){
	$('.form_footer').on('submit', function(e){

        e.preventDefault();
        var errors = '';
        var element = $(this);
        var data = {
            name:element.find('input[name="name"]').val(),
            email:element.find('input[name="email"]').val(),
            body:element.find('textarea[name="body"]').val(),
			remaller_check_email:element.find('input[name="remaller_check_email"]').val(),
        };

        $.ajax({
            type: 'POST',
            url: element.attr('action'),
            data: data,
            dataType: 'json',
            beforeSend:function(result){},
            success: function (result) {

				if (result.errors) {
					$.each(result.errors, function(key,value) {
						errors+=value+'\r';
					});

					alert(errors);
					return false;
				}

				if (result.success) {
				   alert(result.success);
                } else {
                    alert('error');
                    return(false);
                }
				$(element)[0].reset();
            },
			error: function(xhr, ajaxOptions, thrownError) {

				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
        });
    });

	$('.form_page').on('submit', function(e){

        e.preventDefault();
        var errors = '';
        var element = $(this);
        var data = {
            name:element.find('input[name="name"]').val(),
            email:element.find('input[name="email"]').val(),
            phone:element.find('input[name="phone"]').val(),
			url:element.find('input[name="url"]').val(),
			remaller_check:element.find('input[name="remaller_check"]').val(),
        };

        $.ajax({
            type: 'POST',
            url: element.attr('action'),
            data: data,
            dataType: 'json',
            beforeSend:function(result){},
            success: function (result) {

				if (result.errors) {

					$.each(result.errors, function(key,value) {
						errors+=value+'\r';
					});

					alert(errors);
					return false;
				}

				if (result.success) {
				   alert(result.success);
                } else {
                    alert('error');
                    return(false);
                }
            },
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
        });
    });
})
</script>
<script type="text/javascript">
$(function(){

	$menu = $('#rm-top-menu > li').has('ul.nav__second');

	$.each($menu, function( index, value ) {
		$(this).find(' > a').attr('href',  $(this).find('li a').attr('href'));
	});
})
</script>
{strip}
<style>
.for-text{
	display:none;
}
</style>
{/strip}
</body>
</html>
