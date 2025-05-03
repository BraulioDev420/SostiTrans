@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-success text-white text-center fs-4 fw-semibold rounded-top-4">
                    {{ __('Verificación de Correo Electrónico') }}
                </div>

                <div class="card-body p-4 text-center">

                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo electrónico.') }}
                        </div>
                    @endif

                    <p class="mb-3">
                        {{ __('Antes de continuar, por favor revisa tu correo electrónico y haz clic en el enlace de verificación.') }}
                    </p>

                    <p class="mb-3">
                        {{ __('¿No recibiste el correo?') }}
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary fw-semibold">
                            {{ __('Haz clic aquí para reenviar el enlace') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
