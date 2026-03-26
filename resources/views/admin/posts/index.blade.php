<x-layouts::app>
    <div class="mb-6 flex justify-between items-center">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboards</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Posts</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <a href="{{ route('admin.posts.create') }}" class="boton">Nuevo</a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left text-zinc-600">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-xs text-zinc-500 uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">id</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Titulo</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
            @foreach ($posts as $post)
                <tr class="hover:bg-zinc-50 transition-colors duration-200">
                    <td class="px-6 py-4 font-medium text-zinc-900">{{ $post->id }}</td>
                    <td class="px-6 py-4">{{ $post->title }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="boton">Edit</a>
                        
                            <form class="delete-form" action="{{ route('admin.posts.destroy' , $post) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="boton-rojo" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach 
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $posts->links() }}
    </div>

    @push('js')
        <script>
            const forms = document.querySelectorAll('.delete-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    
                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: "¡No podrás revertir este cambio!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, eliminar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layouts::app>