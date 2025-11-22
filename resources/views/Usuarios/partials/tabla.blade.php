<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($usuarios as $usuario)
        <tr>
            <td>{{ $usuarios->firstItem() + $loop->index }}</td> {{-- Numeración consecutiva --}}
            <td>{{ $usuario->name }}</td>
            <td>{{ $usuario->email }}</td>
            <td>
                <div class="d-flex justify-content-center gap-1">


                    {{-- 🔐 BOTÓN ASIGNAR ROL --}}
                    <a href="{{ route('usuarios.asignar', $usuario->id) }}"
                       class="btn btn-warning"
                       style="width:36px; height:36px; display:flex; justify-content:center; align-items:center;"
                       title="Asignar Rol">
                        <i class="bi bi-shield-lock"></i>
                    </a>






                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-editar" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Desea eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-eliminar" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5">No se encontraron usuarios.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<style>
.btn-ver, .btn-editar, .btn-eliminar {
    width: 36px;
    height: 36px;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1rem;
    border-radius: 6px;
}

.btn-ver {
    border: 2px solid #85bfff;
    color: #0856b3;
    background-color: white;
}
.btn-ver:hover {
    background-color: #e0f0ff;
    color: #0856b3;
}

.btn-editar {
    border: 2px solid #ffcc00;
    color: #996600;
    background-color: white;
}
.btn-editar:hover {
    background-color: #fff8cc;
    color: #996600;
}

.btn-eliminar {
    border: 2px solid #ff4d4d;
    color: #a30000;
    background-color: white;
}
.btn-eliminar:hover {
    background-color: #ffe5e5;
    color: #a30000;
}
</style>
