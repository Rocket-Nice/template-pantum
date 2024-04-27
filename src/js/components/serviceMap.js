export default function initYM() {
    // Проверяем, что текущий URL содержит подстроку "service-centers"
    if (window.location.href.includes("service-centers")) {
        // Подключаемся к Yandex Maps API и выполняем код, когда API готово
        ymaps?.ready(function () {
            let map = null; // Переменная для хранения объекта карты
            let placemarks = []; // Массив для хранения меток на карте

            // Функция для добавления точек на карту
            function addCustomPoints(customPoints) {
                // Удаляем существующие метки с карты
                placemarks.forEach(function (placemark) {
                    map.geoObjects.remove(placemark);
                });
                placemarks = []; // Очищаем массив меток

                // Проходимся по переданным точкам и создаем для каждой метки
                customPoints.forEach(function (customPoint) {
                    // Создаем метку с указанными координатами и содержимым балуна
                    let placemark = new ymaps.Placemark(customPoint.coords, {
                        balloonContentHeader: '<h2 style="font-size: 22px; margin-bottom: 10px; font-family: Montserrat; font-weight: 700;">' + customPoint.popup.header + "</h2>",
                        balloonContentBody: '<p class="service-center__address" style="color: #999; font-size: 16px;">' + customPoint.popup.body + "</p>",
                        balloonContentFooter: '<p class="service-center__phone" style="color: #999; font-size: 16px;">' + customPoint.popup.description + "</p>",
                        hintContent: customPoint.popup.header // Устанавливаем заголовок в качестве содержимого подсказки
                    });

                    // Добавляем обработчик клика на метку
                    placemark.events.add('click', function (e) {
                        let target = e.get('target');
                        // Центрируем карту и открываем балун при клике на метку
                        map.setCenter(target.geometry.getCoordinates(), 13.5);
                        // map.setZoom(15); // Устанавливаем зум при клике на точку
                        map.balloon.open(target.geometry.getCoordinates(), {
                            contentHeader: customPoint.popup.header,
                            contentBody: customPoint.popup.body,
                            contentFooter: customPoint.popup.description,
                            closeButton: true,
                        });
                    });

                    // Добавляем обработчики событий для подсказки
                    placemark.events.add('mouseenter', function (e) {
                        let target = e.get('target');
                        target.properties.set('hintContent', customPoint.popup.header); // Установка заголовка в качестве контента подсказки
                        map.hint.open(target.geometry.getCoordinates(), target.properties.get('hintContent'));
                    });

                    placemark.events.add('mouseleave', function () {
                        map.hint.close();
                    });

                    placemarks.push(placemark); // Добавляем метку в массив меток
                });


                // Создаем кластеризатор и добавляем в него метки
                let clusterer = new ymaps.Clusterer({
                    clusterIconLayout: 'default#pieChart',
                    clusterIconPieChartRadius: 25,
                    clusterIconPieChartCoreRadius: 15,
                    clusterIconColor: '#ff0000'
                });

                clusterer.add(placemarks);
                map.geoObjects.add(clusterer);

                // Обработчик клика на кластер
                clusterer.events.add('click', function (e) {
                    let cluster = e.get('target');
                    if (cluster.features && cluster.features.length > 1) {
                        // let clusterPoints = cluster.features;
                        // let clusterContent = '<ul class="cluster-list">';
                        // clusterPoints.forEach(function (feature, index) {
                        //     clusterContent += '<li class="cluster-item" data-index="' + index + '">' + feature.properties.get('balloonContentHeader') + '</li>';
                        // });
                        // clusterContent += '</ul>';
                        map.balloon.open(cluster.geometry.getCoordinates(), clusterContent);
                    }
                });

                // Обработчик клика на элемент списка в кластере
                // document.addEventListener('click', function (e) {
                //     if (e.target.classList.contains('cluster-item')) {
                //         let index = e.target.getAttribute('data-index');
                //         let clusterPoints = clusterer.getGeoObjects()[index].properties.get('geoObjects');
                //         let coords = clusterPoints[0].geometry.getCoordinates();
                //         map.setCenter(coords, 13.5);
                //         map.setZoom(15); // Устанавливаем зум при клике на элемент кластера
                //     }
                // });
            }

            // Первоначальная инициализация карты
            map = new ymaps.Map("map", {
                center: [55.76, 37.64], // Начальные координаты центра карты
                zoom: 3, // Начальное значение зума
            });

            // Закрытие попапа при клике на пустую область карты
            map.events.add("click", function (e) {
                map.balloon.close();
            });

            // Получение координат из списка и добавление их на карту
            let customPoints = [];
            document.querySelectorAll('.service-center__item').forEach(function (item) {

                let coords = item.getAttribute('data-coords').split(',').map(parseFloat);
                let titleElement = item.querySelector('.service-center__title');
                // Предполагается, что есть текст до и после <span>, поэтому мы складываем их значения.
                // Это может потребовать корректировки в зависимости от конкретной структуры.
                let titleText = "";
                if (titleElement.childNodes.length > 1) {
                    titleText = Array.from(titleElement.childNodes).reduce((acc, node) => {
                        if (node.nodeType === Node.TEXT_NODE) {
                            acc += node.nodeValue.trim();
                        }
                        return acc;
                    }, "");
                } else {
                    titleText = titleElement.innerText.trim(); // Используется innerText, если у элемента нет дочерних текстовых узлов кроме самого текста.
                }
                customPoints.push({
                    coords: coords,
                    popup: {
                        header: titleText, // Используется titleText напрямую, так как это уже строка.
                        body: item.querySelector('.service-center__address').innerText,
                        description: item.querySelector('.service-center__phone').innerText,
                    }
                });

                // Обработчик клика на элемент списка

                item.addEventListener('click', function () {
                    let coords = this.getAttribute('data-coords').split(',').map(parseFloat);
                    console.log(coords);
                    map.setCenter(coords, 13.5);
                    // map.setZoom(15); // Устанавливаем зум при клике на элемент списка
                });
            });
            addCustomPoints(customPoints); // Добавляем на карту собранные точки
        });
    }
}
