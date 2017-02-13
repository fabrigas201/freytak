{*strip*}
{extends "../main.tpl"}
{block name="content"}

<div class="breadcrumbs hiddenMobile">
	<div class="container">
		<ul class="breadcrumbs__wrap">
			{foreach from=$breadcrumbs item=item key=key}
				<li class="breadcrumbs__item">{$item}</li>
			{/foreach}
		</ul>
	</div>
</div>
<div class="content">
	<div class="container container_inner">
		<div class="container__row">
			<div class="container__col text">
				<h2 class="title title_big">
					<span class="title__text arno">{__('our_contact_details')}</span>
				</h2>
				<div class="contacts">
					<div class="contacts__item">
						<img src="{get_url('assets/images/contacts/map.png')}" alt="" class="contacts__ico">
						<span class="contacts__label">{__('adres')}</span>
						<span class="contacts__value">{__('adres_text')}</span>
						<span class="clear"></span>
					</div>
					<a href="mailto:office@freytakandsons.com" class="contacts__item contacts__item_link">
						<img src="{get_url('assets/images/contacts/mail.png')}" alt="" class="contacts__ico">
						<span class="contacts__label">{__('kemail')}</span>
						<span class="contacts__value">office@freytakandsons.com</span>
						<span class="clear"></span>
					</a>
					<a href="tel:+74952762766" class="contacts__item contacts__item_link">
						<img src="{get_url('assets/images/contacts/phone.png')}" alt="" class="contacts__ico">						
						<span class="contacts__label">{__('phone')}</span>
						<span class="contacts__value">8 495 276-276-6</span>
						<span class="clear"></span>
					</a>	
					<div class="contacts__item">
						<img src="{get_url('assets/images/contacts/vcard.png')}" alt="" class="contacts__ico">						
						<span class="contacts__label">{__('for_media')}</span>
						<span class="contacts__value">
							<a href="{get_url('upload/Files/vcard/kn.vcf')}" style="color:black;">{__('contact1')} (vCard)</a> <br>
							<a href="{get_url('upload/Files/vcard/gm.vcf')}" style="color:black;">{__('contact2')} (vCard)</a> <br>
							<a href="{get_url('upload/Files/vcard/eg.vcf')}" style="color:black;">{__('contact3')} (vCard)</a>
						</span>
						<span class="clear"></span>
					</div>	
				</div>
				
				<div class="map">
					<div class="map__object" id="map"></div>
				</div>

				<h2 class="title title_big">
					<span class="title__text arno">{__('how_to_find_us')}</span>
				</h2>
				{if isset($result -> descr)}
				<div class="contacts__info">
					{$result -> descr}
				</div>
				{/if}
				{if isset($result -> descrfull)}
				<div class="contacts__info">
					{$result -> descrfull}
				</div>
				{/if}
			</div>
			<div class="container__col menu menu_notSpace">
				{include file=$folders}
				{include file=$projects}
				{include file=$menu_calendar}
				{include file=$menu_calendar_future}
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
{if isset( $result->data.mapApi) && $result->data.mapApi==yandex}
	<!-- Yandex map -->
	<script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
	<script type="text/javascript">
		
	ymaps.ready(function () {
		var loc = [{$result->data.mapX}, {$result->data.mapY}],
			z = {$result->data.mapZoom};
		var myMap = new ymaps.Map('map', {
				center: loc,
				zoom: z
			}, {
				searchControlProvider: 'yandex#search'
			}),
			myPlacemark = new ymaps.Placemark(loc, {
				hintContent: '',
				balloonContent: ''
			}, {
				iconLayout: 'default#image',
				iconImageHref: '{get_url("assets/images/contacts/map/balloon.png")}',
				iconImageSize: [350, 113],
				// Смещение левого верхнего угла иконки относительно
				// её "ножки" (точки привязки).
				iconImageOffset: [-51, -91]
			});

		myMap.geoObjects.add(myPlacemark);
	})
	</script>
{else if  isset( $result->data.mapApi) && $result->data.mapApi==google}
	<!-- Google map  -->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>  
	<script type="text/javascript">
		
		var map, myLatlng, myZoom, marker;
		myLatlng = new google.maps.LatLng({$result->data.mapX}, {$result->data.mapY});
		myZoom = {$result->data.mapZoom};
		function initialize() {
			var mapOptions = {
				zoom: myZoom,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: myLatlng,
				scrollwheel: false
			};
			map = new google.maps.Map(document.getElementById("map"),mapOptions);
			marker = new google.maps.Marker({
				map:map,
				draggable:true,
				position: myLatlng
			});
			google.maps.event.addDomListener(window, "resize", function() {
				map.setCenter(myLatlng);
			});
		}
		google.maps.event.addDomListener(window, "load", initialize);
		
	</script>
{/if}
{/block}
{*/strip*}