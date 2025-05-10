<style>
    body {
        background: linear-gradient(to right top, #2e026d, #3b0764, #491069, #581c87, #6d28d9);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: white;
        min-height: 100vh;
        padding: 40px 0;
    }

    .admin-panel {
        max-width: 1200px;
        margin: auto;
        padding: 2rem;
    }

    .panel-title,
    .titulo-flota {
        font-size: 2.5rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 3rem;
        text-shadow: 2px 2px #00000055;
        color: #fff;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: #e0e7ff;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .table-wrapper {
        overflow-x: auto;
        max-width: 100%;
        border-radius: 1rem;
    }

    /* Estilo Glass para Tablas */
    .styled-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
        background: rgba(37, 2, 66, 0.1);
        border: 2px solid rgb(255, 255, 255);
        /* Borde más visible */
        border-radius: 1rem;
        overflow: hidden;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    .styled-table th,
    .styled-table td {
        padding: 1rem;
        text-align: left;
        color: #1D2D44;
        backdrop-filter: blur(4px);
        font-weight: 500;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        /* Borde inferior de las celdas */
    }

    .styled-table thead {
        background-color: rgba(20, 33, 94, 0.2);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
    }

    .styled-table tbody tr {
        transition: background-color 0.3s ease;
    }

    .styled-table tbody tr:nth-child(odd) {
        background-color: rgba(255, 255, 255, 0.06);
    }

    .styled-table tbody tr:hover {
        background-color: rgba(83, 80, 80, 0.2);
    }

    /* Botón de eliminar */
    .danger-btn {
        background-color: #dc2626;
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(220, 38, 38, 0.5);
        transition: all 0.2s ease-in-out;
    }

    .danger-btn:hover {
        background-color: #b91c1c;
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(220, 38, 38, 0.8);
    }

    .capitalize {
        text-transform: capitalize;
    }

    /* Tema Claro */
    .container th {
        color: #1D2D44;
    }

    .container td {
        font-size: 1.05rem;
        color: #333;
    }

    /* Tema Oscuro */
    [data-theme="dark"] .styled-table {
        background-color: rgba(255, 255, 255, 0.06);
        border: 2px solid rgba(255, 255, 255, 0.4);
        /* Borde más visible */
    }

    [data-theme="dark"] .styled-table thead {
        background-color: #334155;
        color: #F0EBD8;
    }

    [data-theme="dark"] .styled-table th,
    [data-theme="dark"] .styled-table td {
        color: #F0EBD8 !important;
    }

    [data-theme="dark"] .styled-table tbody tr:nth-child(odd) {
        background-color: #273549;
    }

    [data-theme="dark"] .styled-table tbody tr:hover {
        background-color: #3B4C63;
    }
</style>


@extends('layouts.base')

@section('title', 'Panel de Administración')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="admin-panel">
        <h1 class="titulo-flota">Panel de Administración</h1>

        {{-- Tabla de Usuarios --}}
        <div class="container py-5">
            <h2 class="titulo-flota">Usuarios Registrados</h2>
            <div class="table-wrapper">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->name }} {{ $usuario->last_name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->phone }}</td>
                                <td class="capitalize">{{ $usuario->role }}</td>
                                <td>
                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST"
                                        id="delete-form-{{ $usuario->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="danger-btn"
                                            onclick="confirmDelete({{ $usuario->id }})">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="d-flex justify-content-center flex-column align-items-center">
                    <nav aria-label="Paginación de comentarios">
                        <ul class="pagination">
                            <li class="page-item {{ $usuarios->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $usuarios->previousPageUrl() }}" tabindex="-1"
                                    aria-disabled="true">
                                    Anterior
                                </a>
                            </li>

                            @for ($i = 1; $i <= $usuarios->lastPage(); $i++)
                                <li class="page-item {{ $usuarios->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ $usuarios->url($i) }}&comentarios_page={{ $comentarios->currentPage() }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <li class="page-item {{ $usuarios->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $usuarios->nextPageUrl() }}" aria-label="Next">Siguiente</a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Total de usuarios -->
                    <p class="mt-3 text-muted">
                        Total de usuarios: {{ $usuarios->total() }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Tabla de Comentarios --}}
        <div class="container py-5">
            <h2 class="titulo-flota">Comentarios de Usuarios</h2>
            <div class="table-wrapper">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Comentario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comentarios as $comentario)
                            <tr>
                                <td>{{ $comentario->user->name }}</td>
                                <td>{{ $comentario->content }}</td>
                                <td>
                                    <form action="{{ route('comentarios.destroy', $comentario->id) }}" method="POST"
                                        id="delete-comment-{{ $comentario->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="danger-btn"
                                            onclick="confirmDeleteComment({{ $comentario->id }})">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                                    <a class="page-link"
                                        href="{{ $comentarios->url($i) }}&usuarios_page={{ $usuarios->currentPage() }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <li class="page-item {{ $comentarios->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $comentarios->nextPageUrl() }}"
                                    aria-label="Next">Siguiente</a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Total de comentarios -->
                    <p class="mt-3 text-muted">
                        Total de comentarios: {{ $comentarios->total() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Este usuario será eliminado permanentemente junto con sus Rutas y Comentarios!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
                Swal.fire('Eliminado', 'El Usuario ha sido eliminado con éxito.', 'success');
            }
        });
    }

    function confirmDeleteComment(commentId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Este comentario será eliminado permanentemente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-comment-' + commentId).submit();
                Swal.fire('Eliminado', 'El comentario ha sido eliminado con éxito.', 'success');
            }
        });
    }
</script>