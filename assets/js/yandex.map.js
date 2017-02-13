ymaps.ready(function () {
    var loc = [55.750261, 37.598447], 
        z = 15;
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
            iconImageHref: 'img/contacts/map/balloon.png',
            iconImageSize: [350, 113],
            // Смещение левого верхнего угла иконки относительно
            // её "ножки" (точки привязки).
            iconImageOffset: [-51, -91]
        });

    myMap.geoObjects.add(myPlacemark);
})