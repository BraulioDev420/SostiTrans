@extends('layouts.base')

@section('title', 'Rutas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <div id="sidebar-toggle">&#x25B6;</div> <!-- Flecha derecha -->
        <div class="row">
            <!-- Menú lateral izquierdo -->
            <div id="sidebar" class="col-md-3">
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

                <!-- Instrucciones para caminar más abajo -->
                <div id="panel-instrucciones" class="card shadow-sm border-0 mt-5 mb-4" style=" display: none;">
                    <div class="card-body">
                        <h5 class="card-title mb-3 text-primary d-flex align-items-center">
                            <i class="fas fa-walking me-2"></i> Instrucciones para caminar
                        </h5>
                        <ul id="lista-instrucciones" class="list-unstyled ps-2 mb-0"></ul>
                    </div>
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
                    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);  justify-content:center; align-items:center; z-index:9999;">

                    <div
                        style="background:#ffffff; padding:2rem; border-radius:1rem; width:90%; max-width:450px; box-shadow:0 10px 25px rgba(0,0,0,0.2); position:relative; animation: fadeIn 0.3s ease-in-out;">

                        <!-- Botón de cierre flotante -->
                        <button id="cerrarModal"
                            style="position:absolute; top:15px; right:15px; background:transparent; border:none; font-size:1.5rem; color:#999; cursor:pointer;">
                            &times;
                        </button>

                        <!-- Título -->
                        <h4 class="text-center fw-bold mb-4" style="color:#333;">🗺️ Mis Rutas Guardadas</h4>

                        <!-- Selector de rutas -->
                        <label for="selectRuta" class="form-label text-muted fw-semibold">Selecciona una ruta:</label>
                        <select id="selectRuta" class="form-select mb-4"
                            style="border-radius:0.5rem; padding:0.6rem; border:1px solid #ccc;">
                            <option value="">Selecciona una ruta</option>
                        </select>

                        <!-- Botones de acción -->
                        <div class="d-flex flex-column gap-3">
                            <button id="btnCargarRuta" class="btn btn-success fw-semibold w-100 py-2 rounded-pill">
                                ✅ Cargar Ruta
                            </button>
                            <button id="btnEliminarRuta" class="btn btn-danger fw-semibold w-100 py-2 rounded-pill">
                                🗑️ Eliminar Ruta
                            </button>
                            <button class="btn btn-secondary fw-semibold w-100 py-2 rounded-pill"
                                onclick="document.getElementById('modalGestion').style.display='none'">
                                ❌ Cerrar
                            </button>

                        </div>
                    </div>
                </div>

                <!-- Animación opcional -->
                <style>
                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                            transform: translateY(-20px);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                </style>


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

        // Función para calcular la distancia entre dos puntos con la formula de havérsin
        function distancia(lat1, lon1, lat2, lon2) {
            // Radio de la Tierra en metros
            const R = 6371e3;
            // Convierte las latitudes de grados a radianes
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            // Calcula la diferencia de latitud y longitud
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;
            // Aplica la fórmula de havérsin
            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            // Devuelve la distancia en metros
            return R * c;
        }

        async function buscarRuta() {
            const valA = document.getElementById('puntoA').value.trim();
            const valB = document.getElementById('puntoB').value.trim();

            if (!valA || !valB) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Por favor, ingresa las direcciones del Punto A y Punto B.',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // Limpiar capas anteriores del mapa
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

            async function obtenerCoordenadas(direccion) {
                if (puntoA) map.removeLayer(puntoA);
                if (puntoB) map.removeLayer(puntoB);
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccion)}`);
                    const data = await response.json();
                    if (data.length === 0) throw new Error();
                    return { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon) };
                } catch {
                    Swal.fire({
                        icon: 'error',
                        title: 'Dirección no encontrada',
                        text: `No se pudo encontrar la dirección: ${direccion}`,
                        confirmButtonText: 'Entendido'
                    });
                    return null;
                }
            }

            const coordenadasA = await obtenerCoordenadas(valA);
            const coordenadasB = await obtenerCoordenadas(valB);
            if (!coordenadasA || !coordenadasB) return;

            const { lat: latA, lon: lonA } = coordenadasA;
            const { lat: latB, lon: lonB } = coordenadasB;

            // Añadir marcadores al mapa
            window.puntoA = L.marker([latA, lonA]).addTo(map).bindPopup("Punto A").openPopup();
            window.puntoB = L.marker([latB, lonB]).addTo(map).bindPopup("Punto B").openPopup();

            // Actualizar inputs con coordenadas
            document.getElementById('puntoA').value = `${latA},${lonA}`;
            document.getElementById('puntoB').value = `${latB},${lonB}`;

            // Buscar la mejor ruta
            //let mejorRuta = null;
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

            if (!mejorRuta) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ruta no encontrada',
                    text: 'No se encontró una ruta cercana.',
                    confirmButtonText: 'Entendido'
                });
                document.getElementById('resultadoRuta').innerText = '';
                return;
            }

            // Mostrar la mejor ruta en el mapa
            const empresaNombre = (() => {
                const archivo = mejorRuta.archivo.toLowerCase();
                if (archivo.includes('sobusa')) return 'sobusa';
                if (archivo.includes('alianza sodis')) return 'alianza sodis';
                if (archivo.includes('coolitoral')) return 'coolitoral';
                if (archivo.includes('la carolina')) return 'la carolina';
                return 'desconocida';
            })();

            const colores = {
                'sobusa': '#32CD32',
                'alianza sodis': 'blue',
                'coolitoral': '#006400',
                'la carolina': '#996515',
                'desconocida': 'gray'
            };

            window.rutaEnMapa = L.geoJSON(mejorRuta.data, {
                style: {
                    color: colores[empresaNombre],
                    weight: 5,
                    opacity: 0.9
                }
            }).addTo(map);
            map.fitBounds(window.rutaEnMapa.getBounds());

            const rutaNombre = mejorRuta.archivo.split('/').pop().replace('.geojson', '');
            const rutaLabel = `${empresaNombre}/${rutaNombre}`;
            const resultadoEl = document.getElementById('resultadoRuta');
            resultadoEl.innerText = rutaLabel;
            resultadoEl.dataset.busSugerido = rutaLabel;
            resultadoEl.dataset.nombreRuta = rutaNombre;

            // Punto más cercano a A
            const puntoCercano = mejorRuta.coords.reduce((min, actual) => {
                const dist = distancia(latA, lonA, actual.lat, actual.lng);
                return dist < min.dist ? { ...actual, dist } : min;
            }, { dist: Infinity });

            // Obtener instrucciones caminando
            obtenerInstruccionesCaminando({ lat: latA, lon: lonA }, { lat: puntoCercano.lat, lon: puntoCercano.lng });

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
        function obtenerInstruccionesCaminando(origen, destino) {
            const apiKey = '5b3ce3597851110001cf624828165cc2256d405e9671cd6a320ea5ec';

            const coordenadas = [
                [origen.lon, origen.lat],
                [destino.lon, destino.lat]
            ];

            // GeoJSON para trazar la caminata
            const bodyGeojson = { coordinates: coordenadas };

            // 1. Trazar caminata
            fetch('https://api.openrouteservice.org/v2/directions/foot-walking/geojson', {
                method: 'POST',
                headers: {
                    'Authorization': apiKey,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(bodyGeojson)
            })
                .then(res => res.json())
                .then(data => {
                    window.caminata = JSON.stringify(data);

                    const caminata = L.geoJSON(data, {
                        style: {
                            color: '#FF4500',
                            weight: 4,
                            dashArray: '5, 10'
                        }
                    }).addTo(map);

                    if (window.caminataActual) {
                        map.removeLayer(window.caminataActual);
                    }
                    window.caminataActual = caminata;
                })
                .catch(err => {
                    console.error('Error al trazar el camino:', err);
                    Swal.fire('Error', 'No se pudo trazar el camino caminando.', 'error');
                });

            // 2. Obtener instrucciones
            const bodyInstrucciones = {
                coordinates: coordenadas,
                instructions: true,
                language: "es"
            };

            fetch('https://api.openrouteservice.org/v2/directions/foot-walking', {
                method: 'POST',
                headers: {
                    'Authorization': apiKey,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(bodyInstrucciones)
            })
                .then(res => res.json())
                .then(data => {
                    const instrucciones = data.routes[0].segments[0].steps;
                    const lista = document.getElementById("lista-instrucciones");
                    lista.innerHTML = "";

                    instrucciones.forEach((paso, index) => {
                        const li = document.createElement("li");
                        li.innerHTML = `<i class="fas fa-chevron-right me-2 text-success"></i>${index + 1}. ${paso.instruction}`;
                        lista.appendChild(li);
                    });

                    // 🔸 Guardamos las instrucciones como texto plano para enviarlas al backend
                    const textoPlano = instrucciones.map((paso, i) => `${i + 1}. ${paso.instruction}`).join('\n');
                    window.bodyInstrucciones = textoPlano;

                    document.getElementById("panel-instrucciones").style.display = "block";
                })
                .catch(err => {
                    console.error('Error al obtener instrucciones:', err);
                    Swal.fire('Error', 'No se pudieron obtener las instrucciones caminando.', 'error');
                });
        }






        // Guardar ruta
        // Guarda la ruta en base a datos actuales y ruta caminando ya trazada
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
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                const nombre_ruta = result.value;
                const origen = document.getElementById('puntoA').value.trim();
                const destino = document.getElementById('puntoB').value.trim();
                const bus_sugerido = document.getElementById('resultadoRuta').innerText.trim();
                const geojson_file = mejorRuta?.archivo?.split('/').pop();
                const instrucciones_caminando = window.bodyInstrucciones;
                const geojson_caminando = window.caminata;

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

                if (!window.caminataActual) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Camino caminando no disponible',
                        text: 'Por favor, primero traza la ruta caminando para poder guardarla.',
                        confirmButtonText: 'Cerrar'
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

                try {
                    const response = await fetch("/guardar-ruta", {
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
                            geojson_file,
                            instrucciones_caminando,
                            geojson_caminando
                        })
                    });

                    if (!response.ok) throw new Error("Error al guardar la ruta.");

                    const data = await response.json();

                    Swal.fire({
                        icon: 'success',
                        title: '¡Ruta guardada!',
                        text: 'Ruta guardada exitosamente.',
                        confirmButtonText: 'Ok'
                    });

                    console.log("Ruta guardada:", data);
                } catch (error) {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar',
                        text: 'Hubo un problema al guardar la ruta.',
                        confirmButtonText: 'Cerrar'
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

            const modal = document.getElementById("modalGestion");
            modal.classList.remove("modal-hide");
            modal.classList.add("modal-show");
            modal.style.display = "flex";

            await cargarOpcionesRutas();
        };

        // Cerrar modal con animación
        document.getElementById("cerrarModal").onclick = () => {
            const modal = document.getElementById("modalGestion");
            modal.classList.remove("modal-show");
            modal.classList.add("modal-hide");

            setTimeout(() => {
                modal.style.display = "none";
            }, 300); // Tiempo igual al de la animación
        };


        // Cargar rutas del usuario
        async function cargarOpcionesRutas() {
            const response = await fetch("/rutas-usuario");
            const data = await response.json(); // Recibe JSON con rutas

            const select = document.getElementById("selectRuta");
            select.innerHTML = '<option value="">Selecciona una ruta</option>'; // Limpia opciones anteriores

            data.rutas.forEach(ruta => {
                const option = document.createElement("option");
                option.value = ruta.id;
                option.textContent = `🚌 ${ruta.nombre_ruta} - ${ruta.bus_sugerido}`;
                option.dataset.origen = ruta.origen;
                option.dataset.destino = ruta.destino;
                option.dataset.bus = document.getElementById('resultadoRuta').dataset.busSugerido;
                option.dataset.geojson = ruta.geojson_file;
                option.dataset.bus = ruta.bus_sugerido;
                option.dataset.instruccionesCaminando = ruta.instrucciones_caminando;  // texto o URL con instrucciones
                option.dataset.geojsonCaminando = ruta.geojson_caminando;            // nombre del archivo GeoJSON caminando

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
            //Rellena los inputs y muestra el nombre del bus
            puntoAInput.value = selected.dataset.origen;
            puntoBInput.value = selected.dataset.destino;
            resultadoRuta.innerText = selected.dataset.bus;
            //Agrega marcadores en el mapa para Punto A y Punto B
            const [latA, lonA] = selected.dataset.origen.split(',').map(Number);
            const [latB, lonB] = selected.dataset.destino.split(',').map(Number);

            if (!isNaN(latA) && !isNaN(lonA) && !isNaN(latB) && !isNaN(lonB)) {
                window.puntoA = L.marker([latA, lonA]).addTo(map).bindPopup("Punto A").openPopup();
                window.puntoB = L.marker([latB, lonB]).addTo(map).bindPopup("Punto B").openPopup();
            } else {
                console.error("Coordenadas inválidas.");
                return;
            }
            //Cargar archivo GeoJSON de la ruta
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

            const geoJsonUrl = `js/${rutaGeoJson}.geojson`;// Ruta donde están las rutas GeoJSON

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
                //Pintar la ruta en el mapa con color según empresa
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
                //Ajustar el mapa y cerrar el modal
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
            const geojsonCaminando = selected.dataset.geojsonCaminando;
            const instruccionesCaminando = selected.dataset.instruccionesCaminando;

            // Mostrar instrucciones caminando si tienes un contenedor para eso
            if (instruccionesCaminando) {
                const panel = document.getElementById('panel-instrucciones');
                const lista = document.getElementById('lista-instrucciones');

                // Mostrar el panel
                if (panel) panel.style.display = 'block';

                // Limpiar lista anterior
                if (lista) {
                    lista.innerHTML = '';

                    // Si las instrucciones están en formato string con saltos de línea
                    const instrucciones = instruccionesCaminando.split('\\n'); // Ojo: doble backslash por si viene como string JSON

                    instrucciones.forEach(instr => {
                        if (instr.trim() !== '') {
                            const li = document.createElement('li');
                            li.innerHTML = `<i class="fas fa-chevron-right me-2 text-success"></i>${instr}`;
                            lista.appendChild(li);
                        }
                    });
                }
            }


            // Cargar GeoJSON caminando en el mapa
            if (geojsonCaminando) {
                try {
                    const geoJsonCaminoUrl = `js/${geojsonCaminando}.geojson`;  // o ruta correcta
                    const respCamino = await fetch(geoJsonCaminoUrl);
                    if (!respCamino.ok) throw new Error(`Error HTTP caminando: ${respCamino.status}`);

                    const dataCamino = await respCamino.json();

                    if (!dataCamino || !dataCamino.features || !Array.isArray(dataCamino.features) || dataCamino.features.length === 0) {
                        throw new Error("El GeoJSON caminando está vacío o mal formado.");
                    }

                    // Quitar ruta caminando anterior si existe
                    if (window.rutaCaminandoEnMapa) {
                        map.removeLayer(window.rutaCaminandoEnMapa);
                        window.rutaCaminandoEnMapa = null;
                    }

                    // Pintar ruta caminando con otro color o estilo
                    window.rutaCaminandoEnMapa = L.geoJSON(dataCamino, {
                        style: {
                            color: '#0000FF',  // color azul para camino a pie
                            weight: 4,
                            opacity: 0.7,
                            dashArray: '5,10'
                        },
                        onEachFeature: (feature, layer) => {
                            if (feature.properties && feature.properties.name) {
                                layer.bindPopup(`Camino: ${feature.properties.name}`);
                            }
                        }
                    }).addTo(map);

                } catch (error) {
                    console.error('Error al cargar el GeoJSON caminando:', error);
                    // Puedes mostrar alerta si quieres
                }
            }
             if (window.caminataActual) {
                map.removeLayer(window.caminataActual);
                window.caminataActual = null;
            }
        };


        // Eliminar ruta
        document.getElementById("btnEliminarRuta").onclick = async () => {
            //Se obtiene la ruta seleccionada del select
            const selected = document.getElementById("selectRuta").selectedOptions[0];
            if (!selected || !selected.value) return;
            //Se obtiene el id de la ruta seleccionada
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
            document.getElementById('modalGestion').style.display = 'none';
        };
        // Toggle sidebar on mobile
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const contentOverlay = document.getElementById('content-overlay');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            contentOverlay.classList.toggle('show');
        });

        contentOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            contentOverlay.classList.remove('show');
        });


    </script>

@endsection