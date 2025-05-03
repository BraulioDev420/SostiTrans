@extends('layouts.baselogin')

@section('content')
<!-- Font Awesome para los íconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<style>
body {
    background: linear-gradient(135deg, #1e2a38, #2c3e50);
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 0;
}

/* Tarjeta de formulario */
.glass-card {
    background: rgba(36, 52, 70, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 18px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: #fff;
    transition: none !important;
    transform: none !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Evitar cambios al hacer hover sobre la tarjeta */
.glass-card:hover {
    background: rgba(36, 52, 70, 0.9) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: #fff !important;
    transform: none !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
}

/* Modo claro */
[data-theme="light"] .glass-card {
    background: rgba(255, 255, 255, 0.2);
    color: #000;
}

[data-theme="light"] .glass-card:hover {
    background: rgba(255, 255, 255, 0.2) !important;
    color: #000 !important;
}

/* Inputs */
.form-label {
    font-weight: 600;
}

.form-control {
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff !important;
    border-radius: 10px;
    padding: 0.7rem 1rem;
    transition: border-color 0.3s, box-shadow 0.3s;
    caret-color: #fff;
}


.form-control:focus {
    background-color: rgba(255, 255, 255, 0.08);
    border: 1px solid #6fb1fc;
    box-shadow: 0 0 10px #6fb1fc33;
    outline: none;
}

.form-control:hover {
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid #6fb1fc;  /* Azul al pasar el mouse */
    box-shadow: 0 0 10px #6fb1fc33; /* Sombra azul */
}


.form-control::placeholder {
    color: #ccc;
}

/* Botón */
.btn-primary {
    background-color: #6fb1fc;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    padding: 0.75rem;
    transition: none !important;
}

.btn-primary:hover {
    background-color: #6fb1fc !important;
}

/* Enlaces */
.text-info {
    color: #6fb1fc !important;
    text-decoration: none;
    transition: none !important;
}

.text-info:hover {
    color: #6fb1fc !important;
    text-decoration: none;
}

/* Iconos */
.input-group-text {
    background-color: transparent;
    border: none;
    color: #6fb1fc;
}

/* Estructura input con icono */
.icon-input {
    display: flex;
    align-items: center;
    gap: 10px;
}
/* Botón Registrar */
.btn-primary {
    background-color: #6fb1fc;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    padding: 0.75rem;
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease; /* Efecto de transición */
}

.btn-primary:hover {
    background-color: #4a90e2; /* Cambiar a un color más oscuro al hacer hover */
    transform: scale(1.05); /* Efecto de agrandar el botón */
    box-shadow: 0 0 20px rgba(70, 130, 180, 0.7); /* Agregar sombra azul al hacer hover */
}

/* Botón Iniciar sesión */
.text-info {
    color: #6fb1fc !important;
    text-decoration: none;
    transition: color 0.3s ease, transform 0.3s ease; /* Efecto de transición */
}

.text-info:hover {
    color: #4a90e2 !important; /* Cambiar a un color más oscuro al hacer hover */
    transform: scale(1.05); /* Efecto de agrandar el texto */
}
</style>

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="glass-card p-5">
            <h2 class="text-center fw-bold mb-4">{{ __('Regístrate') }}</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Nombre') }}</label>
                    <div class="input-group icon-input">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autofocus placeholder="Tu nombre">
                    </div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Apellido -->
                <div class="mb-3">
                    <label for="last_name" class="form-label">{{ __('Apellido') }}</label>
                    <div class="input-group icon-input">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <input id="last_name" type="text"
                            class="form-control @error('last_name') is-invalid @enderror"
                            name="last_name" value="{{ old('last_name') }}" required placeholder="Tu apellido">
                    </div>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Correo -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                    <div class="input-group icon-input">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input id="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required placeholder="ejemplo@correo.com">
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label for="phone" class="form-label">{{ __('Teléfono') }}</label>
                    <div class="input-group icon-input">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input id="phone" type="text"
                            class="form-control @error('phone') is-invalid @enderror"
                            name="phone" value="{{ old('phone') }}" placeholder="000-000-0000">
                    </div>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                    <div class="input-group icon-input">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" required placeholder="********">
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirmar contraseña -->
                <div class="mb-3">
                    <label for="password-confirm" class="form-label">{{ __('Confirmar Contraseña') }}</label>
                    <div class="input-group icon-input">
                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                        <input id="password-confirm" type="password" class="form-control"
                            name="password_confirmation" required placeholder="Repite tu contraseña">
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Registrar') }}
                    </button>
                </div>

                <div class="text-center mt-4">
                    <p class="mb-1 fs-5">¿Ya tienes una cuenta?</p>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-info fs-5">Inicia sesión</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
