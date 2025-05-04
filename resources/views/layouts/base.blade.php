<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'SostiTrans')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap + Bootswatch -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/lumen/bootstrap.min.css">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Bootstrap Icons (una sola vez) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Google Fonts (solo una vez) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
<!-- En tu base.blade.php o vista -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tu archivo de estilos personalizado -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>


<body>
    <button class="toggle-darkmode" onclick="toggleTheme()" id="theme-toggle">🌙</button>

    @include('layouts.navbar')


    <main class="w-100 position-relative overflow-hidden text-white py-5 parallax-bg">
        <div class="position-relative z-10">
            @yield('cards1')
        </div>
    </main>


    <main class="container py-4">
        @yield('content')
    </main>

    <main class="w-100 position-relative overflow-hidden text-white py-5 fondo">
        <div class="position-relative z-10">
            @yield('comentarios')
        </div>
    </main>


    <footer class="footer text-white py-4 ">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5>Contacto</h5>
                    <p><i class="bi bi-envelope"></i> Email: sostitrans@gmail.com</p>
                    <p><i class="bi bi-telephone"></i> Teléfono: 018000456</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5>Síguenos</h5>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-instagram" style="font-size: 24px;"></i>
                            @sostitrans
                        </a>
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-twitter" style="font-size: 24px;"></i>
                            @sostitrans
                        </a>
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-whatsapp" style="font-size: 24px;"></i>
                            +57 305 381 7080
                        </a>
                    </div>
                </div>
                <hr style="border-color: rgba(240, 235, 216, 0.2); margin: 0.5rem auto; width: 50%;">
                <p class="small">&copy; 2025 SostiTrans. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
                updateThemeIcon(savedTheme);
            }
        });

        function toggleTheme() {
            const html = document.documentElement;
            const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const toggleBtn = document.getElementById('theme-toggle');
            toggleBtn.textContent = theme === 'dark' ? '🌞' : '🌙';
        }

        function updateParallax() {
            const scrollY = window.scrollY;
            const bg1X = scrollY * 0.2;
            const bg2X = scrollY * 0.1;

            document.querySelector('.parallax-bg').style.backgroundPosition = `
            ${bg1X}px 0,
            ${bg2X}px 100px
        `;
        }

        window.addEventListener('scroll', updateParallax);
        window.addEventListener('load', updateParallax); // aplica también al entrar

        const myCarousel = document.querySelector('#carouselComentarios');
    const carousel = new bootstrap.Carousel(myCarousel, {
        interval: 4000, // cambia cada 4 segundos
        ride: 'carousel'
    });


    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>