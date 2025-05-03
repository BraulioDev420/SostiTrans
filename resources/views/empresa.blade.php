@extends('layouts.base')

@section('title', $empresa->nombre)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Menú lateral izquierdo -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ url('/rutas') }}" class="list-group-item list-group-item-action">
                        Rutas y Paradas
                    </a>
                    @foreach(App\Models\Empresa::all() as $e)
                        <a href="{{ url('/empresa/' . $e->id) }}"
                            class="list-group-item list-group-item-action {{ $e->id == $empresa->id ? 'active' : '' }}">
                            {{ $e->nombre }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-md-9">
                <h1 class="titulo-flota">{{ $empresa->nombre }}</h1>

                <!-- Card de rutas disponibles -->
                <div class="card shadow-lg border-0 rounded-4 mb-4 theme-card">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-semibold mb-3">📌 Rutas Disponibles</h4>
                        <ul class="list-unstyled ruta-lista">
                            @foreach($rutas as $ruta)
                                <li class="mb-2">
                                    <a href="#" onclick="scrollToMap(); cargarRuta('{{ $ruta }}')" class="ruta-link">
                                        🚏 {{ $ruta }}
                                    </a>

                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Mapa -->
                <h4 class="fw-semibold mb-3">🗺️ Mapa de la Ruta Seleccionada</h4>
                <div id="map-section">
                    <div id="map" class="rounded-4 shadow-sm" style="height: 500px; width: 100%;"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function scrollToMap() {
            const mapSection = document.getElementById('map-section');
            if (mapSection) {
                mapSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
        let map = L.map('map').setView([10.96854, -74.78132], 13); // Barranquilla

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            
        }).addTo(map);

        let geojsonLayer;

        // Función para cargar la ruta seleccionada
        function cargarRuta(nombreArchivo) {
            if (geojsonLayer) {
                map.removeLayer(geojsonLayer);
            }

            // Obtener el nombre de la carpeta de acuerdo con la empresa
            let empresaNombre = '{{ strtolower($empresa->nombre) }}'; // "sobusa", "alianzasodis", etc.

            console.log(`/js/${empresaNombre}/${nombreArchivo}.geojson`);

            // Cargar el archivo GeoJSON de la ruta correspondiente
            fetch(`/js/${empresaNombre}/${nombreArchivo}.geojson`)
                .then(response => response.json())
                .then(data => {
                    let colorRuta = 'green'; // Valor predeterminado (para Sobusa)

                    // Asignar color según la empresa
                    if (empresaNombre === 'sobusa') {
                        colorRuta = '#32CD32'; // Sobusa -> verde
                    } else if (empresaNombre === 'alianza sodis') {
                        colorRuta = 'blue'; // Alianza Sodis -> azul
                    } else if (empresaNombre === 'coolitoral') {
                        colorRuta = '#006400'; // Coolitoral -> verde más oscuro
                    } else if (empresaNombre === 'la carolina') {
                        colorRuta = '#996515'; // La Carolina -> marrón/dorado
                    }

                    geojsonLayer = L.geoJSON(data, {
                        style: {
                            color: colorRuta,
                            weight: 5,
                            opacity: 0.8
                        }
                    }).addTo(map);

                    // Ajustar el mapa a los límites de la ruta cargada
                    const bounds = geojsonLayer.getBounds();
                    map.fitBounds(bounds);
                })
                .catch(error => console.error('Error al cargar el archivo GeoJSON:', error));

        }
    </script>
@endsection