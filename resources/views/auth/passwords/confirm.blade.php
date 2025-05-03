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
</style>

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="glass-card p-5">
            <h2 class="text-center fw-bold mb-4">{{ __('Confirmar Contraseña') }}</h2>

            <p class="text-center mb-4 fs-5">
                {{ __('Por favor confirma tu contraseña antes de continuar.') }}
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                    <div class="input-group icon-input">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password" placeholder="Contraseña">
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botón de confirmar -->
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Confirmar Contraseña') }}
                    </button>
                </div>

                <!-- Enlace para restablecer la contraseña -->
                @if (Route::has('password.request'))
                    <div class="text-center mb-2 mt-3">
                        <a class="text-decoration-none text-secondary" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    </div>
                @endif

            </form>
        </div>
    </div>
</div>
@endsection
