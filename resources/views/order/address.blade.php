
<style>
    .yandex_maps{
        height: 100%;
        width: 100%;
    }
</style>
<div class="yandex_maps" id="map"></div>
<script src="https://api-maps.yandex.ru/2.1/?apikey=ваш API-ключ&lang=ru_RU"></script>
<script>
    let latitude = "{{$latitude}}"
    let longitude = "{{$longitude}}"
    let center = [latitude, longitude]
    function init() {
        let map = new ymaps.Map('map', {
            center: center,
            zoom: 17
        });

        let placemark = new ymaps.Placemark(center, {}, {});

        map.controls.remove('geolocationControl'); // удаляем геолокацию
        map.controls.remove('searchControl'); // удаляем поиск
        map.controls.remove('trafficControl'); // удаляем контроль трафика
        // map.controls.remove('typeSelector'); // удаляем тип
        map.controls.remove('fullscreenControl'); // удаляем кнопку перехода в полноэкранный режим
        // map.controls.remove('zoomControl'); // удаляем контрол зуммирования
        map.controls.remove('rulerControl'); // удаляем контрол правил
        // map.behaviors.disable(['scrollZoom']); // отключаем скролл карты (опционально)

        map.geoObjects.add(placemark);
    }
    ymaps.ready(init);

</script>
