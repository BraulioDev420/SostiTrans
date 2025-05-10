@extends('layouts.base')

@section('title', 'Comentarios')

@section('content')
    @if(session('comentario_publicado'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Comentario publicado!',
                text: '{{ session('comentario_publicado') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    <style>
        .titulo-flota {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1D2D44;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .comentario-form {
            background-color: #f4f4f4;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .comentario-form textarea {
            resize: none;
            border-radius: 12px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .comentario-form .btn {
            background-color: #3E5C76;
            border: none;
            font-weight: bold;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            transition: background-color 0.3s ease;
            color: white;
        }

        .comentario-form .btn:hover {
            background-color: #1D2D44;
        }

        .comentario-card {
            border: none;
            border-radius: 16px;
            background-color: #ffffff;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .comentario-card .card-body {
            padding: 1.5rem;
        }

        .comentario-card strong {
            color: #1D2D44;
        }

        .comentario-card small {
            color: #748CAB;
        }

        .comentario-card p {
            margin-top: 1rem;
            font-size: 1.05rem;
            color: #333;
        }

        .login-reminder {
            text-align: center;
            font-size: 1.1rem;
            color: #333;
        }

        .login-reminder a {
            color: #3E5C76;
            font-weight: bold;
        }

        /* --- MODO OSCURO --- */
        [data-theme="dark"] .comentario-form,
        [data-theme="dark"] .comentario-card {
            background-color: #1D2D44;
            color: #F0EBD8;
        }

        [data-theme="dark"] .comentario-card strong,
        [data-theme="dark"] .comentario-card small,
        [data-theme="dark"] .comentario-card p {
            color: #F0EBD8 !important;
        }

        [data-theme="dark"] .comentario-form .btn {
            background-color: #748CAB;
            color: #fff;
        }

        [data-theme="dark"] .comentario-form .btn:hover {
            background-color: #A1B5D8;
        }

        [data-theme="dark"] .login-reminder {
            color: #F0EBD8;
        }

        [data-theme="dark"] .login-reminder a {
            color: #A1B5D8;
        }


    </style>

    <div class="container py-5">
        <h2 class="titulo-flota">Comentarios</h2>

        @auth
            <form action="{{ route('comentarios.store') }}" method="POST" class="comentario-form mb-5">
                @csrf
                <div class="mb-3">
                    <textarea name="content" class="form-control" rows="3" placeholder="Escribe tu comentario..."
                        required></textarea>
                </div>
                <button type="submit" class="btn text-light" id="btn-publicar">
                    <span id="btn-text">Publicar</span>
                    <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true"></span>
                </button>
            </form>
        @else
            <p class="login-reminder">Debes <a href="{{ route('login') }}">iniciar sesión</a> para comentar.</p>
        @endauth

        @foreach($comentarios as $comentario)
            <div class="card comentario-card mb-4">
                <div class="card-body">
                    <strong>{{ $comentario->user->name }}</strong>
                    <small class="ms-2">{{ $comentario->created_at->diffForHumans() }}</small>
                    <p>{{ $comentario->content }}</p>
                </div>
            </div>
        @endforeach

        <!-- Paginación -->
        <div class="d-flex justify-content-center flex-column align-items-center">
            <nav aria-label="Paginación de comentarios">
                <ul class="pagination">
                    <li class="page-item {{ $comentarios->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $comentarios->previousPageUrl() }}" tabindex="-1"
                            aria-disabled="true">
                            Anterior
                        </a>
                    </li>

                    @for ($i = 1; $i <= $comentarios->lastPage(); $i++)
                        <li class="page-item {{ $comentarios->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $comentarios->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    <li class="page-item {{ $comentarios->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $comentarios->nextPageUrl() }}" aria-label="Next">Siguiente</a>
                    </li>
                </ul>
            </nav>

            <!-- Total de comentarios -->
            <p class="mt-3 text-muted">
                Total de comentarios: {{ $comentarios->total() }}
            </p>
        </div>

    </div>
@endsection