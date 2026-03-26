<x-layouts::app>
    <div class="mb-6 flex justify-between items-center">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboards</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Etiquetas</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <a href="{{ route('admin.tags.create') }}" class="boton">Nueva Etiqueta</a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left text-zinc-600">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-xs text-zinc-500 uppercase">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Nombre</th>
                    <th class="px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
            @foreach ($tags as $tag)
                <tr class="hover:bg-zinc-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $tag->id }}</td>
                    <td class="px-6 py-4">{{ $tag->name }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="boton">Edit</a>
                        <form class="delete-form" action="{{ route('admin.tags.destroy', $tag) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="boton-rojo" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach 
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tags->links() }}</div>

    @push('js')
        <script>
            // Aquí puedes pegar el mismo script de SweetAlert que usas en posts/index.blade.php
            const forms = document.querySelectorAll('.delete-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    Swal.fire({ title: "¿Estás seguro?", icon: "warning", showCancelButton: true, confirmButtonText: "Sí, eliminar" })
                    .then((result) => { if (result.isConfirmed) form.submit(); });
                });
            });
        </script>
    @endpush
</x-layouts::app>