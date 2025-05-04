@extends('layouts.base')

@section('title', 'Rutas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

                                <!-- Contenedor para los botones (Buscar Ruta, Guardar Ruta y Gestionar Rutas) -->
                                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                                    <!-- Botón de buscar ruta -->
                                    <button class="btn btn-gradient rounded-pill px-4 py-2 fw-bold" onclick="buscarRuta()">
                                        🚀 Buscar Ruta
                                    </button>
                                    <hr class="my-4" style="opacity: 0.2;">
                                    <!-- Botón de guardar ruta (solo si el usuario está autenticado) -->
                                    <button class="btn btn-success rounded-pill px-4 py-2 fw-bold" onclick="guardarRuta()">
                                        💾 Guardar Ruta
                                    </button>
                                    <hr class="my-4" style="opacity: 0.2;">
                                    <!-- Botón de gestionar rutas -->
                                    <button id="abrirModal" class="btn btn-info rounded-pill px-4 py-2 fw-bold">
                                        🗂️ Gestionar Rutas
                                    </button>
                                </div>


                                <!-- Resultado de la búsqueda bonito -->
                                <div id="resultadoRuta" class="mt-4 p-3 rounded text-white fw-bold shadow-sm"
                                    style="background: linear-gradient(135deg, #00c6ff, #0072ff); font-size: 1.1rem;">
                                    <!-- Aquí aparecerá: Ruta Encontrada: Empresa - NombreRuta -->
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- Modal -->
                <div id="modalGestion" class="modal"
                    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
                    <div style="background:white; padding:20px; border-radius:10px; width:400px;">
                        <h5 class="card-title fw-semibold text-dark mb-4">Mis Rutas Guardadas</h5>

                        <select id="selectRuta" class="form-select my-3" style="width:100%">
                            <option value="">Selecciona una ruta</option>
                        </select>

                        <div class="d-flex justify-content-between">
                            <button id="btnCargarRuta" class="btn btn-success">Cargar</button>
                            <button id="btnEliminarRuta" class="btn btn-danger">Eliminar</button>
                            <button id="cerrarModal" class="btn btn-secondary">Cerrar</button>
                        </div>
                    </div>
                </div>




                <!-- Mapa de Barranquilla -->
                <div class="border-0 rounded-4" id="map" style="height: 500px; width: 100%;"></div>
            </div>

        </div>
    </div>

    <!-- Leaflet CSS y JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([10.96854, -74.78132], 13); // Barranquilla
        let mejorRuta = null;
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

        function buscarRuta() {
            const valA = document.getElementById('puntoA').value;
            const valB = document.getElementById('puntoB').value;

            if (!valA || !valB) {
                alert('Por favor, selecciona Punto A y Punto B.');
                return;
            }

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

                    marcadorA = L.marker([latA, lonA]).addTo(map).bindPopup("Punto A").openPopup();
                    marcadorB = L.marker([latB, lonB]).addTo(map).bindPopup("Punto B").openPopup();

                    document.getElementById('puntoA').value = `${latA},${lonA}`;
                    document.getElementById('puntoB').value = `${latB},${lonB}`;


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

                        let empresaNombre = '';
                        if (mejorRuta.archivo.includes('sobusa')) empresaNombre = 'sobusa';
                        else if (mejorRuta.archivo.includes('alianza sodis')) empresaNombre = 'alianza sodis';
                        else if (mejorRuta.archivo.includes('coolitoral')) empresaNombre = 'coolitoral';
                        else if (mejorRuta.archivo.includes('la carolina')) empresaNombre = 'la carolina';

                        let colorRuta = 'green';
                        if (empresaNombre === 'sobusa') colorRuta = '#32CD32';
                        else if (empresaNombre === 'alianza sodis') colorRuta = 'blue';
                        else if (empresaNombre === 'coolitoral') colorRuta = '#006400';
                        else if (empresaNombre === 'la carolina') colorRuta = '#996515';

                        rutaEnMapa = L.geoJSON(mejorRuta.data, {
                            style: {
                                color: colorRuta,
                                weight: 5,
                                opacity: 0.9
                            }
                        }).addTo(map);
                        map.fitBounds(rutaEnMapa.getBounds());

                        const rutaNombre = mejorRuta.archivo.split('/').pop().replace('.geojson', '');
                        const rutaLabel = `${empresaNombre.charAt(0) + empresaNombre.slice(1)}/${rutaNombre}`;

                        document.getElementById('resultadoRuta').innerText = `${rutaLabel}`;

                        // Solo rutaLabel (sin "Ruta Encontrada:") como path del archivo
                        document.getElementById('resultadoRuta').dataset.busSugerido = rutaLabel;
                        document.getElementById('resultadoRuta').dataset.nombreRuta = rutaNombre;
                    } else {
                        alert('No se encontró una ruta cercana.');
                        document.getElementById('resultadoRuta').innerText = '';
                    }
                });
        }

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


        function guardarRuta() {
            const user_id = @json(auth()->check() ? auth()->user()->id : null);
            if (!user_id) {
                alert("Debes iniciar sesión para guardar una ruta.");
                return;
            }

            const origen = document.getElementById('puntoA').value.trim();
            const destino = document.getElementById('puntoB').value.trim();
            const bus_sugerido = document.getElementById('resultadoRuta').innerText.trim();
            const geojson_file = mejorRuta?.archivo?.split('/').pop(); // Esto te da solo el nombre del archivo

            if (!origen || !destino) {
                alert("Por favor completa los campos de origen y destino.");
                return;
            }

            if (!mejorRuta || !geojson_file) {
                alert("No se ha encontrado una ruta válida para guardar.");
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                alert("No se encontró el token CSRF.");
                return;
            }

            fetch("/guardar-ruta", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    user_id,
                    origen,
                    destino,
                    bus_sugerido,
                    geojson_file
                })
            })
                .then(response => {
                    if (!response.ok) throw new Error("Error al guardar la ruta.");
                    return response.json();
                })
                .then(data => {
                    alert("Ruta guardada exitosamente.");
                    console.log("Ruta guardada:", data);
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Hubo un problema al guardar la ruta.");
                });
        }
        document.getElementById("abrirModal").onclick = async () => {
            document.getElementById("modalGestion").style.display = "flex";
            await cargarOpcionesRutas();
        };

        document.getElementById("cerrarModal").onclick = () => {
            document.getElementById("modalGestion").style.display = "none";
        };

        async function cargarOpcionesRutas() {
            const response = await fetch("/rutas-usuario");
            const data = await response.json();

            const select = document.getElementById("selectRuta");
            select.innerHTML = '<option value="">Selecciona una ruta</option>';

            data.rutas.forEach(ruta => {
                const option = document.createElement("option");
                option.value = ruta.id;
                option.textContent = `🚌 ${ruta.bus_sugerido}`;
                option.dataset.origen = ruta.origen;
                option.dataset.destino = ruta.destino;
                option.dataset.bus = document.getElementById('resultadoRuta').dataset.busSugerido;
                option.dataset.geojson = ruta.geojson_file;
                option.dataset.bus = ruta.bus_sugerido;
                select.appendChild(option);
            });
        }


        document.getElementById("btnCargarRuta").onclick = async () => {
            const selectElement = document.getElementById("selectRuta");
            const selected = selectElement.selectedOptions[0];
            if (!selected || !selected.value) {
                console.warn("No se seleccionó ninguna ruta");
                return;
            }

            const puntoAInput = document.getElementById("puntoA");
            const puntoBInput = document.getElementById("puntoB");
            const resultadoRuta = document.getElementById("resultadoRuta");

            if (!puntoAInput || !puntoBInput || !resultadoRuta) {
                console.error("Uno de los elementos no se encontró en el DOM");
                return;
            }

            puntoAInput.value = selected.dataset.origen;
            puntoBInput.value = selected.dataset.destino;
            resultadoRuta.innerText = selected.dataset.bus;

            // Eliminar marcadores anteriores si existen
            if (window.marcadorA) {
                map.removeLayer(window.marcadorA);
                window.marcadorA = null;
            }
            if (window.marcadorB) {
                map.removeLayer(window.marcadorB);
                window.marcadorB = null;
            }

            // Crear nuevos marcadores
            const [latA, lonA] = selected.dataset.origen.split(',').map(Number);
            const [latB, lonB] = selected.dataset.destino.split(',').map(Number);

            if (!isNaN(latA) && !isNaN(lonA) && !isNaN(latB) && !isNaN(lonB)) {
                window.marcadorA = L.marker([latA, lonA]).addTo(map).bindPopup("Punto A").openPopup();
                window.marcadorB = L.marker([latB, lonB]).addTo(map).bindPopup("Punto B").openPopup();
            } else {
                console.error("Coordenadas inválidas.");
                return;
            }

            // Construir ruta al GeoJSON con base en bus_sugerido
            const rutaGeoJson = selected.dataset.bus;

            if (!rutaGeoJson) {
                console.error("El atributo dataset.bus está vacío o indefinido");
                alert("No se pudo determinar la ruta a cargar.");
                return;
            }

            const geoJsonUrl = `js/${rutaGeoJson}.geojson`;

            console.log("GeoJSON URL:", geoJsonUrl);

            try {
                const resp = await fetch(geoJsonUrl);
                if (!resp.ok) throw new Error(`Error HTTP: ${resp.status}`);

                const data = await resp.json();
                console.log("GeoJSON recibido:", data);

                if (!data || !data.features || !Array.isArray(data.features) || data.features.length === 0) {
                    throw new Error("El GeoJSON está vacío o mal formado.");
                }

                // Eliminar ruta anterior si existe
                if (window.rutaEnMapa) {
                    map.removeLayer(window.rutaEnMapa);
                    window.rutaEnMapa = null;
                }

                // Cargar nueva ruta con el color especificado
                window.rutaEnMapa = L.geoJSON(data, {
                    style: {
                        color: selected.dataset.color || '#FF5733', // Asume que `color` está en los datos de la ruta
                        weight: 5,
                        opacity: 0.9
                    },
                    onEachFeature: (feature, layer) => {
                        if (feature.properties && feature.properties.name) {
                            layer.bindPopup(feature.properties.name);
                        }
                    }
                }).addTo(map);

                // Ajustar mapa al nuevo recorrido
                map.fitBounds(window.rutaEnMapa.getBounds());

                // Limpiar el mapa si ya hay una ruta cargada y marcar los puntos A y B
                if (window.marcadorA || window.marcadorB) {
                    map.removeLayer(window.marcadorA);
                    map.removeLayer(window.marcadorB);
                }

                // Crear nuevos marcadores
                const [latA, lonA] = selected.dataset.origen.split(',').map(Number);
                const [latB, lonB] = selected.dataset.destino.split(',').map(Number);

                if (!isNaN(latA) && !isNaN(lonA) && !isNaN(latB) && !isNaN(lonB)) {
                    window.marcadorA = L.marker([latA, lonA]).addTo(map).bindPopup("Punto A").openPopup();
                    window.marcadorB = L.marker([latB, lonB]).addTo(map).bindPopup("Punto B").openPopup();
                } else {
                    console.error("Coordenadas inválidas.");
                    return;
                }

                // Mostrar mensaje en la interfaz
                document.getElementById('resultadoRuta').innerText = `Ruta cargada: ${rutaGeoJson}`;
                document.getElementById('modalGestion').style.display = 'none';

            } catch (error) {
                console.error('Error al cargar la ruta:', error);
                alert('No se pudo cargar la ruta seleccionada.');
            }
        };








        document.getElementById("btnEliminarRuta").onclick = async () => {
            const selected = document.getElementById("selectRuta").selectedOptions[0];
            if (!selected || !selected.value) return;

            const rutaId = selected.value;
            if (!confirm("¿Seguro que deseas eliminar esta ruta?")) return;

            await fetch(`/eliminar-ruta/${rutaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            await cargarOpcionesRutas();
        };

    </script>

@endsection