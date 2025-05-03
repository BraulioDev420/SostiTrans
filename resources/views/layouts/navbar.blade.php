<style>
    .navbar {
        position: fixed;
        width: 100%;
        top: 0;
        left: 0;
        z-index: 1050;
        padding: 0.75rem 1rem;
        transition: all 0.4s ease;
        background-color: rgba(29, 45, 68, 0.6);
        /* Transparente */
        backdrop-filter: blur(10px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .navbar.hidden {
        transform: translateY(-100%);
        opacity: 0;
    }

    .navbar.show {
        transform: translateY(0);
        opacity: 1;
    }

    .nav-link {
        transition: all 0.3s ease;
        border-radius: 12px;
        color: #F0EBD8 !important;
    }

    .nav-link:hover {
        background-color: rgba(240, 235, 216, 0.1);
        border-radius: 12px;
        color:rgb(10, 10, 10) !important;
    }

    .nav-link.active-custom {
        background-color: rgba(240, 235, 216, 0.85) !important;
        color: #1D2D44 !important;
        font-weight: bold;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(240, 235, 216, 0.3);
    }

    .navbar-brand img {
        border: 4px solid #F0EBD8;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        transition: border-color 0.3s ease;
    }

    .navbar-brand img:hover {
        border-color: #748CAB;
    }

    [data-theme="dark"] .navbar {
        background-color: rgba(13, 19, 33, 0.6);
    }

    [data-theme="dark"] .nav-link:hover {
        background-color: rgba(240, 235, 216, 0.08);
        color: #748CAB !important;
    }

    .btn-outline-light {
        color: #F0EBD8;
        border-color: #748CAB;
        padding: 0.5rem 1.2rem;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
        background-color: #3E5C76;
        color: #F0EBD8;
        border-color: #3E5C76;
    }

    body {
        padding-top: 80px;
        /* espacio para el navbar fijo */
    }

    .user-info {
    background-color: rgba(240, 235, 216, 0.1);
    padding: 0.4rem 1.2rem;
    border-radius: 30px;
    color: #F0EBD8;
    font-size: 1rem;
    font-weight: 500;
    text-shadow: 0 1px 3px black;
    text-align: right;
    white-space: nowrap;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.user-info:hover {
    background-color: rgba(240, 235, 216, 0.2);
    cursor: default;
}

</style>

<nav class="navbar navbar-expand-lg navbar-dark show" id="mainNavbar">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center">
        {{-- Logo estilizado --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" width="55"
                height="55" class="rounded-circle border border-light shadow-sm" />
            <span class="fw-bold fs-4 text-white" style="text-shadow: 1px 1px 5px #000000aa;">SostiTrans</span>
        </a>

        {{-- Navegación principal --}}
        <ul class="navbar-nav flex-row gap-3 mx-auto">
            <li class="nav-item">
                <a class="nav-link px-3 py-2 {{ request()->is('/') || request()->is('home') ? 'active-custom' : '' }}"
                    href="{{ route('home') }}">Inicio</a>

            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 {{ request()->is('rutas*') || request()->is('empresa/*') ? 'active-custom' : '' }}"
                    href="{{ route('rutas.index') }}">Rutas</a>

            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 {{ request()->is('comentarios*') ? 'active-custom' : '' }}"
                    href="{{ route('comentarios.index') }}">Comentarios</a>
            </li>
        </ul>

        {{-- Usuario --}}
        <div class="d-flex flex-column align-items-end gap-2">
            @auth
            <div class="d-flex flex-column align-items-end gap-2">
    <div class="user-info w-100">
        <i class="bi bi-person-circle me-2 fs-5"></i>
        {{ auth()->user()->name . ' ' . auth()->user()->last_name }}
    </div>
    <form action="{{ route('logout') }}" method="POST" class="mb-0 w-100">
        @csrf
        <button type="submit" class="btn btn-outline-light btn-sm shadow-sm w-100">Cerrar Sesión</button>
    </form>
</div>

            @else
                <div class="d-flex flex-column align-items-end gap-2">
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm shadow-sm w-100">Crear Cuenta</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm shadow-sm w-100">Iniciar Sesión</a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    let prevScrollPos = window.scrollY;
    const navbar = document.getElementById('mainNavbar');

    window.addEventListener('scroll', function () {
        let currentScrollPos = window.scrollY;

        if (currentScrollPos > prevScrollPos && currentScrollPos > 100) {
            navbar.classList.add('hidden');
            navbar.classList.remove('show');
        } else {
            navbar.classList.add('show');
            navbar.classList.remove('hidden');
        }

        prevScrollPos = currentScrollPos;
    });
</script>