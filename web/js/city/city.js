/* global myModal, myVar, PubSub, ymaps */

$(document).ready(function () {
    $Maps = [];
    var c1 = 51.6550, c2 = 39.1952;
    let lat = $('#city-gps_lat').val();
    let lng = $('#city-gps_lng').val();
    if (lat !== '' && lng !== '') {
        c1 = lat;
        c2 = lng;
    }
    ymaps.ready(function () {
        $Maps['yandex_map'] = new ymaps.Map('yandex_map', {"center": [c1, c2], "zoom": 10, "behaviors": ["default", "scrollZoom"], "type": "yandex#map", "controls": []}, {"minZoom": 1, "maxZoom": 20});
        var search = new ymaps.control.SearchControl({options: {size: "small"}});
        $Maps['yandex_map'].controls
                .add(new ymaps.control.ZoomControl({options: {size: "small"}}))

                .add(search)
                ;

        var myPlacemark = new ymaps.Placemark([c1, c2], {}, {
            draggable: true,
        });
        $Maps['yandex_map'].geoObjects.add(myPlacemark);
        myPlacemark.events.add("dragend", function (e) {
            var coords = this.geometry.getCoordinates();
            $('#city-gps_lat').val(coords[0]);
            $('#city-gps_lng').val(coords[1]);
        }, myPlacemark);
        ;
    });
});



