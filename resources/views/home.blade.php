@extends('layouts.base')

@section('title', 'Inicio')


@section('cards1')

    <!-- CARDS -->
    <div class="container py-5 my-5 border-top">
        <h2 class="titulo-flota">Nuestra Flota de Buses</h2>
        <div class="position-relative">
            <!-- CARDS DE EMPRESAS -->
            <div class="container py-5">
                <div id="productCarousel" class="d-flex overflow-auto gap-4 px-2 pb-3" style="scroll-behavior: smooth;">
                    @php
                        $empresas = [
                            ['nombre' => 'Sobusa', 'descripcion' => 'Conoce todo sobre nuestra empresa de transporte.', 'imagen' => 'sobusa.jpeg', 'ruta' => '/empresa/1'],
                            ['nombre' => 'Alianza Sodis', 'descripcion' => 'Descubre nuestros servicios de transporte sostenible.', 'imagen' => 'sodis.jpeg', 'ruta' => '/empresa/2'],
                            ['nombre' => 'Coolitoral', 'descripcion' => 'Comprometidos con la comodidad de los usuarios.', 'imagen' => 'colitoral.jpeg', 'ruta' => '/empresa/3'],
                            ['nombre' => 'La Carolina', 'descripcion' => 'Únete a la revolución del transporte público.', 'imagen' => 'carolina.jpeg', 'ruta' => '/empresa/4']
                        ];
                    @endphp

                    @foreach ($empresas as $empresa)
                        <div class="animated-card glass-card p-0 rounded-4 shadow"
                            style="min-width: 260px; max-width: 280px; overflow: hidden;">
                            <div class="image-container">
                                <img src="{{ asset('images/' . $empresa['imagen']) }}" class="card-img-top"
                                    alt="{{ $empresa['nombre'] }}">
                                <div class="overlay">
                                    <div class="overlay-text text-center p-3">
                                        <h5 class="fw-bold">{{ $empresa['nombre'] }}</h5>
                                        <h5 class="small">{{ $empresa['descripcion'] }}</h5>
                                        <a href="{{ $empresa['ruta'] }}" class="btn btn-sm btn-info rounded-pill mt-2">Más
                                            Información</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

@endsection


@section('content')

    <!-- SOBRE NOSOTROS -->
    <div class="container py-5 my-5 border-top">
        <h2 class="titulo-flota">Sobre Nosotros</h2>
        <div class="row align-items-center">
            <!-- Información a la izquierda con estilo glass -->
            <div class="col-md-6">
                <div class="glass-card p-4">
                    <p class="d-flex overflow-auto gap-4 px-2 pb-3">✅ Priorizamos la satisfacción del usuario.</p>
                    <p class="d-flex overflow-auto gap-4 px-2 pb-3">🚌 Nuestra flota es moderna y sostenible.</p>
                    <p class="d-flex overflow-auto gap-4 px-2 pb-3">🛡️ Seguridad y comodidad ante todo.</p>
                </div>
            </div>

            <!-- Imagen + botón a la derecha -->
            <div class="col-md-6 text-center">
                <img src="https://th.bing.com/th/id/OIP.MFhExc58E308mMToMEVgMQHaEi?rs=1&pid=ImgDetMain"
                    class="img-fluid rounded mb-3 shadow" alt="Sobre Nosotros">
                <div>
                    <button type="button" class="btn btn-sm btn-info rounded-pill mt-2" data-bs-toggle="modal"
                        data-bs-target="#modalSobreNosotros">
                        Más sobre nosotros ->
                    </button>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal Sobre Nosotros -->
    <div class="modal fade" id="modalSobreNosotros" tabindex="-1" aria-labelledby="sobreNosotrosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content modal-constante border-0 shadow-lg rounded-4">
                <div class="modal-header bg-info text-white rounded-top-4">
                    <h5 class="modal-title" id="sobreNosotrosLabel">¿Quiénes Somos?</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <p><strong>SostiTrans</strong> es una plataforma web innovadora diseñada para mejorar el transporte
                        público en la ciudad. Nuestro objetivo es facilitar el acceso a rutas óptimas, fomentar el uso del
                        transporte colectivo y ofrecer soluciones inteligentes y sostenibles.</p>

                    <ul class="list-unstyled mt-4">
                        <li>🚍 <strong>Conectamos personas y destinos</strong> de forma eficiente.</li>
                        <li>🌍 <strong>Comprometidos con el medio ambiente</strong>, promoviendo una movilidad sostenible.
                        </li>
                        <li>🔒 <strong>Transparencia y seguridad</strong> en cada interacción.</li>
                        <li>🤝 <strong>Escuchamos a los ciudadanos</strong> para construir un mejor sistema de transporte
                            juntos.</li>
                    </ul>

                    <p class="mt-4">Nuestro equipo trabaja constantemente para incorporar nuevas tecnologías, mejorar la
                        experiencia del usuario y ofrecer información en tiempo real que haga del transporte una experiencia
                        más confiable y cómoda.</p>
                    <p class="mt-4"><strong>Equipo de Desarrolladores:</strong> Braulio Castro, Luis Pérez, Andrés Algarín,
                        Antony Lechuga.</p>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('comentarios')

    @include('destacados', ['comentarios' => $comentarios])

    <!-- COMENTARIOS -->
    <div class="container py-5 my-5 border-top">
        <h2 class="titulo-flota text-center mb-4">Comentarios</h2>

        <div class="row align-items-center">
            <!-- Carrusel de Comentarios con Glass Card a la izquierda -->
            <div class="col-md-6">
                <div id="carouselComentarios" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($comentarios as $index => $comentario)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="p-4 bg-glass rounded shadow text-dark">
                                    <p class="mb-2">{{ $comentario->content }}</p>
                                    <small class="text-muted">— {{ $comentario->user->name }} |
                                        {{ $comentario->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselComentarios"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselComentarios"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>

                <!-- Botón para añadir comentarios debajo del carrusel -->
                <div class="text-center mt-4">
                    <a href="{{ route('comentarios.index') }}" class="btn btn-lg btn-primary rounded-pill">Añadir
                        Comentarios</a>
                </div>
            </div>

            <!-- Imagen a la derecha -->
            <div class="col-md-6 text-center">
                <img src="https://static.vecteezy.com/system/resources/previews/016/139/670/large_2x/cartoon-customer-reviews-user-feedback-icon-in-comic-style-rating-sign-illustration-pictogram-stars-rating-business-splash-effect-concept-vector.jpg"
                    class="img-fluid rounded mb-3 shadow" alt="Comentarios">
            </div>
        </div>
    </div>
@endsection