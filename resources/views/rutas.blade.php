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
                <!-- Modal Gestionar Rutas -->
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
        let puntoA, puntoB;
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
                if (puntoA) map.removeLayer(puntoA);
                puntoA = L.marker(e.latlng).addTo(map).bindPopup("Punto A").openPopup();
                document.getElementById('puntoA').value = `${e.latlng.lat},${e.latlng.lng}`;
                seleccionando = 'B';
            } else {
                if (puntoB) map.removeLayer(puntoB);
                puntoB = L.marker(e.latlng).addTo(map).bindPopup("Punto B").openPopup();
                document.getElementById('puntoB').value = `${e.latlng.lat},${e.latlng.lng}`;
                seleccionando = 'A';
            }
        });
        // Función para calcular la distancia entre dos puntos
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

        // Función para buscar la ruta
        function buscarRuta() {
            const valA = document.getElementById('puntoA').value;
            const valB = document.getElementById('puntoB').value;
            // Limpiar mapa completamente antes de cargar nueva ruta
            if (window.rutaEnMapa) {
                map.removeLayer(window.rutaEnMapa);
                window.rutaEnMapa = null;
            }

            if (window.puntoA) {
                map.removeLayer(window.puntoA);
                window.puntoA = null;
            }
            if (window.puntoB) {
                map.removeLayer(window.puntoB);
                window.puntoB = null;
            }
            if (!valA || !valB) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Por favor, selecciona Punto A y Punto B.',
                    confirmButtonText: 'Entendido'
                });
                return;
            }


            // Obtener coordenadas de las direcciones ingresadas
            // Usando Nominatim para obtener coordenadas
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
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'No se pudo encontrar la dirección.',
                                confirmButtonText: 'Entendido'
                            });
                            return null;
                        }
                    });
            }

            // Obtener coordenadas de ambas direcciones
            // Usando Promise.all para esperar ambas respuestas
            Promise.all([obtenerCoordenadas(valA), obtenerCoordenadas(valB)])
                .then(([coordenadasA, coordenadasB]) => {
                    if (!coordenadasA || !coordenadasB) return;

                    const { lat: latA, lon: lonA } = coordenadasA;
                    const { lat: latB, lon: lonB } = coordenadasB;

                    if (puntoA) map.removeLayer(puntoA);
                    if (puntoB) map.removeLayer(puntoB);

                    puntoA = L.marker([latA, lonA]).addTo(map).bindPopup("Punto A").openPopup();
                    puntoB = L.marker([latB, lonB]).addTo(map).bindPopup("Punto B").openPopup();

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

                        Swal.fire({
                            icon: 'error',
                            title: 'Ruta no encontrada',
                            text: 'No se encontró una ruta cercana.',
                            confirmButtonText: 'Entendido'
                        });
                        document.getElementById('resultadoRuta').innerText = '';
                    }
                });
        }

        // Cargar rutas GeoJSON
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

        // Cargar cada archivo GeoJSON
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


        // Guardar ruta
        function guardarRuta() {
            const user_id = @json(auth()->check() ? auth()->user()->id : null);
            if (!user_id) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Debes iniciar sesión para guardar una ruta.',
                    confirmButtonText: 'Entendido'
                });

                return;
            }

            Swal.fire({
                title: 'Nombre de la ruta',
                input: 'text',
                inputLabel: 'Escribe un nombre para esta ruta:',
                inputPlaceholder: 'Ej: Ruta desde casa al trabajo',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debes escribir un nombre para la ruta';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const nombre_ruta = result.value;
                    const origen = document.getElementById('puntoA').value.trim();
                    const destino = document.getElementById('puntoB').value.trim();
                    const bus_sugerido = document.getElementById('resultadoRuta').innerText.trim();
                    const geojson_file = mejorRuta?.archivo?.split('/').pop(); // Esto te da solo el nombre del archivo

                    if (!origen || !destino) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Campos incompletos',
                            text: 'Por favor completa los campos de origen y destino.',
                            confirmButtonText: 'Ok'
                        });
                        return;
                    }

                    if (!mejorRuta || !geojson_file) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ruta no válida',
                            text: 'No se ha encontrado una ruta válida para guardar.',
                            confirmButtonText: 'Entendido'
                        });
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!csrfToken) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de seguridad',
                            text: 'No se encontró el token CSRF.',
                            confirmButtonText: 'Cerrar'
                        });
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
                            nombre_ruta,
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
                            Swal.fire({
                                icon: 'success',
                                title: '¡Ruta guardada!',
                                text: 'Ruta guardada exitosamente.',
                                confirmButtonText: 'Ok'
                            });
                            console.log("Ruta guardada:", data);
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al guardar',
                                text: 'Hubo un problema al guardar la ruta.',
                                confirmButtonText: 'Cerrar'
                            });
                        });
                }
            });
        }

        // Abrir modal para gestionar rutas
        document.getElementById("abrirModal").onclick = async () => {
            const user_id = @json(auth()->check() ? auth()->user()->id : null);
            if (!user_id) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Debes iniciar sesión para gestionar tus rutas.',
                    confirmButtonText: 'Entendido'
                });

                return;
            }
            document.getElementById("modalGestion").style.display = "flex";
            await cargarOpcionesRutas();
        };

        // Cerrar modal
        document.getElementById("cerrarModal").onclick = () => {
            document.getElementById("modalGestion").style.display = "none";
        };

        // Cargar rutas del usuario
        async function cargarOpcionesRutas() {
            const response = await fetch("/rutas-usuario");
            const data = await response.json();

            const select = document.getElementById("selectRuta");
            select.innerHTML = '<option value="">Selecciona una ruta</option>';

            data.rutas.forEach(ruta => {
                const option = document.createElement("option");
                option.value = ruta.id;
                option.textContent = `🚌 ${ruta.nombre_ruta} - ${ruta.bus_sugerido}`;
                option.dataset.origen = ruta.origen;
                option.dataset.destino = ruta.destino;
                option.dataset.bus = document.getElementById('resultadoRuta').dataset.busSugerido;
                option.dataset.geojson = ruta.geojson_file;
                option.dataset.bus = ruta.bus_sugerido;
                select.appendChild(option);
            });
        }


        // Cargar ruta seleccionada
        document.getElementById("btnCargarRuta").onclick = async () => {
            if (rutaEnMapa) map.removeLayer(rutaEnMapa);
            if (puntoA) map.removeLayer(puntoA);
            if (puntoB) map.removeLayer(puntoB);

            const selectElement = document.getElementById("selectRuta");
            const selected = selectElement.selectedOptions[0];
            if (!selected || !selected.value) {
                console.warn("No se seleccionó ninguna ruta");
                return;
            }

            if (window.rutaEnMapa) {
                map.removeLayer(window.rutaEnMapa);
                window.rutaEnMapa = null;
            }
            if (window.puntoA) {
                map.removeLayer(window.puntoA);
                window.puntoA = null;
            }
            if (window.puntoB) {
                map.removeLayer(window.puntoB);
                window.puntoB = null;
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

            const [latA, lonA] = selected.dataset.origen.split(',').map(Number);
            const [latB, lonB] = selected.dataset.destino.split(',').map(Number);

            if (!isNaN(latA) && !isNaN(lonA) && !isNaN(latB) && !isNaN(lonB)) {
                window.puntoA = L.marker([latA, lonA]).addTo(map).bindPopup("Punto A").openPopup();
                window.puntoB = L.marker([latB, lonB]).addTo(map).bindPopup("Punto B").openPopup();
            } else {
                console.error("Coordenadas inválidas.");
                return;
            }

            const rutaGeoJson = selected.dataset.bus;

            if (!rutaGeoJson) {
                console.error("El atributo dataset.bus está vacío o indefinido");

                Swal.fire({
                    icon: 'error',
                    title: 'Ruta no disponible',
                    text: 'No se pudo determinar la ruta a cargar.',
                    confirmButtonText: 'Cerrar'
                });
                return;
            }

            const geoJsonUrl = `js/${rutaGeoJson}.geojson`;

            try {
                const resp = await fetch(geoJsonUrl);
                if (!resp.ok) throw new Error(`Error HTTP: ${resp.status}`);

                const data = await resp.json();
                console.log("GeoJSON recibido:", data);

                if (!data || !data.features || !Array.isArray(data.features) || data.features.length === 0) {
                    throw new Error("El GeoJSON está vacío o mal formado.");
                }

                // Obtener color según empresa
                let empresaNombre = '';
                if (rutaGeoJson.includes('sobusa')) empresaNombre = 'sobusa';
                else if (rutaGeoJson.includes('alianza sodis')) empresaNombre = 'alianza sodis';
                else if (rutaGeoJson.includes('coolitoral')) empresaNombre = 'coolitoral';
                else if (rutaGeoJson.includes('la carolina')) empresaNombre = 'la carolina';

                let colorRuta = '#FF5733'; // color por defecto
                if (empresaNombre === 'sobusa') colorRuta = '#32CD32';
                else if (empresaNombre === 'alianza sodis') colorRuta = 'blue';
                else if (empresaNombre === 'coolitoral') colorRuta = '#006400';
                else if (empresaNombre === 'la carolina') colorRuta = '#996515';

                // Cargar nueva ruta en el mapa
                window.rutaEnMapa = L.geoJSON(data, {
                    style: {
                        color: colorRuta,
                        weight: 5,
                        opacity: 0.9
                    },
                    onEachFeature: (feature, layer) => {
                        if (feature.properties && feature.properties.name) {
                            layer.bindPopup(feature.properties.name);
                        }
                    }
                }).addTo(map);

                map.fitBounds(window.rutaEnMapa.getBounds());
                document.getElementById('modalGestion').style.display = 'none';
                resultadoRuta.innerText = `Ruta cargada: ${rutaGeoJson}`;

                Swal.fire({
                    icon: 'success',
                    title: '¡Ruta cargada con éxito!',
                    text: `La ruta "${rutaGeoJson}" se ha visualizado correctamente en el mapa.`,
                    timer: 2500,
                    showConfirmButton: false
                });

            } catch (error) {
                console.error('Error al cargar la ruta:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Ruta no disponible',
                    text: 'No se pudo cargar la ruta seleccionada.',
                    confirmButtonText: 'Cerrar'
                });
            }
        };


        // Eliminar ruta
        document.getElementById("btnEliminarRuta").onclick = async () => {
            const selected = document.getElementById("selectRuta").selectedOptions[0];
            if (!selected || !selected.value) return;

            const rutaId = selected.value;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará la ruta permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        await fetch(`/eliminar-ruta/${rutaId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        await cargarOpcionesRutas();

                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'La ruta ha sido eliminada correctamente.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } catch (error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un problema al eliminar la ruta.',
                            icon: 'error'
                        });
                    }
                }
            });

        };

    </script>

@endsection