ymaps.ready(init);
if (typeof lat === 'undefined') {
    var lat = 0,
        lon = 0,
        latInputId = null,
        lonInputId = null,
        addressInputId = null;
}
function init() {
    var coords = [lat, lon];
    var center;
    if (coords[0] && coords[1]) {
        center = coords;
    } else {
        center = [55.76, 37.64];
    }
    var myMap = new ymaps.Map("map", {
        center: center,
        zoom: 11
    });

    var point = new ymaps.Placemark(coords, {}, {
        preset: 'islands#dotIcon',
        iconColor: '#735184'
    });
    myMap.geoObjects.add(point);
    getAddress(coords);
    myMap.events.add('click', function (e) {
        var coords = e.get('coords');
        point.geometry.setCoordinates([coords[0], coords[1]]);
        if(latInputId != null) {
            document.getElementById(latInputId).value = coords[0];
        }
        if(lonInputId != null) {
            document.getElementById(lonInputId).value = coords[1];
        }
        getAddress(coords, addressInputId);
    });

    function getAddress(coords, inputElemId = null) {
        point.properties.set('iconCaption', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);
            point.properties
                .set({
                    iconCaption: [
                        firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                        firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                    ].filter(Boolean).join(', '),
                    balloonContent: firstGeoObject.getAddressLine()
                });
            if (inputElemId) {
                document.getElementById(inputElemId).value = firstGeoObject.getAddressLine();
            }
        });
    }
}