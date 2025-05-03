@extends('layouts.base')

@section('title', 'Rutas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Menú lateral izquierdo -->
            <div class="col-md-3">
                <div class="list-group rounded shadow-sm glass-effect" id="menu-lateral">
                    <a href="#"
                        class="list-group-item list-group-item-action active d-flex align-items-center bg-info text-white">
                        <i class="fas fa-route me-3"></i> Rutas y Paradas
                    </a>
                    <a href="{{ route('empresa.show', 1) }}"
                        class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-bus me-3"></i> Sobusa
                    </a>
                    <a href="{{ route('empresa.show', 2) }}"
                        class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-bus me-3"></i> Alianza Sodis
                    </a>
                    <a href="{{ route('empresa.show', 3) }}"
                        class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-bus me-3"></i> Coolitoral
                    </a>
                    <a href="{{ route('empresa.show', 4) }}"
                        class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-bus me-3"></i> La Carolina
                    </a>
                </div>
            </div>




            <!-- Contenido principal -->
            <div class="col-md-9">
                <h1 class="titulo-flota text-primary fw-bold mb-4">Rutas y Paradas</h1>

                <!-- Card principal -->
                <div class="card shadow-lg border-0 rounded-4 mb-4 fondo-parallax">
                    <div class="card-body p-5">
                        <h4 class="card-title fw-semibold text-dark mb-4">📍 Planificación de Rutas</h4>

                        <!-- Card para buscar ruta -->
                        <div class="card border-0 shadow-sm rounded-4 bg-light">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold mb-4">🔍 Buscar Ruta Cercana</h5>

                                <!-- Campo para Punto A -->
                                <div class="mb-3">
                                    <label for="puntoA" class="form-label">📌 Punto A (origen):</label>
                                    <input type="text" id="puntoA" class="form-control rounded-pill"
                                        placeholder="Ej: Cra 46 #74-10 o clic en el mapa">
                                </div>

                                <!-- Campo para Punto B -->
                                <div class="mb-3">
                                    <label for="puntoB" class="form-label">🎯 Punto B (destino):</label>
                                    <input type="text" id="puntoB" class="form-control rounded-pill"
                                        placeholder="Ej: Av Circunvalar o clic en el mapa">
                                </div>

                                <!-- Botón de buscar ruta -->
                                <button class="btn btn-gradient rounded-pill px-4 py-2 fw-bold" onclick="buscarRuta()">🚀
                                    Buscar Ruta</button>

                                <!-- Resultado de la búsqueda -->
                                <div id="resultadoRuta" class="mt-4 text-dark"></div>
                            </div>
                        </div>

                    </div>
                </div>




                <!-- Mapa de Barranquilla -->
                <div class= "border-0 rounded-4" id="map" style="height: 500px; width: 100%;"></div>
            </div>

        </div>
    </div>

    <!-- Leaflet CSS y JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([10.96854, -74.78132], 13); // Barranquilla

        let marcadorA, marcadorB;
        let rutasGeojson = [];
        let rutaEnMapa;

        // Mapa base
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            
        }).addTo(map);

        // Selección por clic
        let seleccionando = 'A';
        map.on('click', function (e) {
            if (seleccionando === 'A') {
                if (marcadorA) map.removeLayer(marcadorA);
                marcadorA = L.marker(e.latlng).addTo(map).bindPopup("Punto A").openPopup();
                document.getElementById('puntoA').value = `${e.latlng.lat},${e.latlng.lng}`;
                seleccionando = 'B';
            } else {
                if (marcadorB) map.removeLayer(marcadorB);
                marcadorB = L.marker(e.latlng).addTo(map).bindPopup("Punto B").openPopup();
                document.getElementById('puntoB').value = `${e.latlng.lat},${e.latlng.lng}`;
                seleccionando = 'A';
            }
        });

        // Distancia entre dos puntos
        function distancia(lat1, lon1, lat2, lon2) {
            const R = 6371e3;
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c;
        }

        // Buscar ruta más cercana
        function buscarRuta() {
            const valA = document.getElementById('puntoA').value;
            const valB = document.getElementById('puntoB').value;

            if (!valA || !valB) {
                alert('Por favor, selecciona Punto A y Punto B.');
                return;
            }

            // Conversión de direcciones a coordenadas utilizando Nominatim
            function obtenerCoordenadas(direccion) {
                return fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${direccion}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            return {
                                lat: parseFloat(data[0].lat),
                                lon: parseFloat(data[0].lon)
                            };
                        } else {
                            alert('No se pudo encontrar la dirección.');
                            return null;
                        }
                    });
            }

            Promise.all([obtenerCoordenadas(valA), obtenerCoordenadas(valB)])
                .then(([coordenadasA, coordenadasB]) => {
                    if (!coordenadasA || !coordenadasB) return;

                    const { lat: latA, lon: lonA } = coordenadasA;
                    const { lat: latB, lon: lonB } = coordenadasB;

                    if (marcadorA) map.removeLayer(marcadorA);
                    if (marcadorB) map.removeLayer(marcadorB);

                    // Marcadores de los puntos A y B
                    marcadorA = L.marker([latA, lonA]).addTo(map).bindPopup("Punto A").openPopup();
                    marcadorB = L.marker([latB, lonB]).addTo(map).bindPopup("Punto B").openPopup();

                    // Actualización de los valores en los inputs
                    document.getElementById('puntoA').value = `${latA},${lonA}`;
                    document.getElementById('puntoB').value = `${latB},${lonB}`;

                    let mejorRuta = null;
                    let mejorDistancia = Infinity;

                    rutasGeojson.forEach(ruta => {
                        let distA = Infinity, distB = Infinity;

                        ruta.coords.forEach(coord => {
                            const d1 = distancia(latA, lonA, coord.lat, coord.lng);
                            const d2 = distancia(latB, lonB, coord.lat, coord.lng);
                            if (d1 < distA) distA = d1;
                            if (d2 < distB) distB = d2;
                        });

                        const total = distA + distB;
                        if (total < mejorDistancia) {
                            mejorDistancia = total;
                            mejorRuta = ruta;
                        }
                    });

                    if (mejorRuta) {
                        if (rutaEnMapa) map.removeLayer(rutaEnMapa);

                        // Determinar empresa a partir de la ruta
                        let empresaNombre = '';
                        if (mejorRuta.archivo.includes('sobusa')) {
                            empresaNombre = 'sobusa';
                        } else if (mejorRuta.archivo.includes('alianza sodis')) {
                            empresaNombre = 'alianza sodis';
                        } else if (mejorRuta.archivo.includes('coolitoral')) {
                            empresaNombre = 'coolitoral';
                        } else if (mejorRuta.archivo.includes('la carolina')) {
                            empresaNombre = 'la carolina';
                        }

                        // Asignar color según la empresa
                        let colorRuta = 'green'; // Valor por defecto
                        if (empresaNombre === 'sobusa') {
                            colorRuta = '#32CD32'; // verde
                        } else if (empresaNombre === 'alianza sodis') {
                            colorRuta = 'blue';
                        } else if (empresaNombre === 'coolitoral') {
                            colorRuta = '#006400'; // verde oscuro
                        } else if (empresaNombre === 'la carolina') {
                            colorRuta = '#996515'; // marrón/dorado
                        }

                        rutaEnMapa = L.geoJSON(mejorRuta.data, {
                            style: {
                                color: colorRuta,
                                weight: 5,
                                opacity: 0.9
                            }
                        }).addTo(map);
                        map.fitBounds(rutaEnMapa.getBounds());

                        // Mostrar el nombre del bus y la ruta junto al botón de búsqueda
                        const rutaNombre = mejorRuta.archivo.split('/').pop().replace('.geojson', '');
                        const rutaLabel = `${empresaNombre.charAt(0).toUpperCase() + empresaNombre.slice(1)} - ${rutaNombre}`;
                        document.getElementById('resultadoRuta').innerText = `Ruta Encontrada: ${rutaLabel}`;
                    } else {
                        alert('No se encontró una ruta cercana.');
                        document.getElementById('resultadoRuta').innerText = '';
                    }
                });
        }

        // Lista de rutas GeoJSON
        const rutas = [
            // SOBUSA
            { archivo: '/js/sobusa/B18-4175.geojson' },
            { archivo: '/js/sobusa/C11-4168.geojson' },
            { archivo: '/js/sobusa/C12-4169.geojson' },
            { archivo: '/js/sobusa/C13-4143.geojson' },
            { archivo: '/js/sobusa/C14-4170.geojson' },
            { archivo: '/js/sobusa/C16-4167.geojson' },

            // ALIANZA SODIS
            { archivo: '/js/alianza sodis/B13-4128.geojson' },
            { archivo: '/js/alianza sodis/B14-4174.geojson' },
            { archivo: '/js/alianza sodis/B15-4129.geojson' },
            { archivo: '/js/alianza sodis/C21-4182.geojson' },

            // COOLITORAL
            { archivo: '/js/coolitoral/A1-4106.geojson' },
            { archivo: '/js/coolitoral/A2-4107.geojson' },
            { archivo: '/js/coolitoral/A3-4108.geojson' },
            { archivo: '/js/coolitoral/A4-4109.geojson' },
            { archivo: '/js/coolitoral/B1-4117.geojson' },
            { archivo: '/js/coolitoral/B17-4163.geojson' },
            { archivo: '/js/coolitoral/B2A-4177.geojson' },
            { archivo: '/js/coolitoral/B3-4119.geojson' },
            { archivo: '/js/coolitoral/C19-4178.geojson' },
            { archivo: '/js/coolitoral/PT1-4101.geojson' },
            { archivo: '/js/coolitoral/PT2-4102.geojson' },
            { archivo: '/js/coolitoral/PT3-4103.geojson' },
            { archivo: '/js/coolitoral/PT4-4104.geojson' },
            { archivo: '/js/coolitoral/PT5-4105.geojson' },

            // LA CAROLINA
            { archivo: '/js/la carolina/A16-4161.geojson' },
            { archivo: '/js/la carolina/D6-4150.geojson' },
            { archivo: '/js/la carolina/D7-4151.geojson' }
        ];


        // Cargar rutas
        rutas.forEach(r => {
            fetch(r.archivo)
                .then(resp => resp.json())
                .then(data => {
                    const coords = [];
                    data.features.forEach(f => {
                        if (f.geometry.type === 'LineString') {
                            f.geometry.coordinates.forEach(c => {
                                coords.push({ lat: c[1], lng: c[0] });
                            });
                        } else if (f.geometry.type === 'MultiLineString') {
                            f.geometry.coordinates.forEach(line => {
                                line.forEach(c => {
                                    coords.push({ lat: c[1], lng: c[0] });
                                });
                            });
                        }
                    });
                    rutasGeojson.push({ archivo: r.archivo, data, coords });
                });
        });
    </script>
@endsection