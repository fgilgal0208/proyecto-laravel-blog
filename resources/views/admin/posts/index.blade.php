<x-layouts::app>
    <div class="mb-6 flex justify-between items-center">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboards</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Posts</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <a href="{{ route('admin.posts.create') }}" class="boton">Nuevo</a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden dark:bg-zinc-900 dark:border-zinc-700">
        <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-xs text-zinc-500 uppercase tracking-wider dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">ID</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Título</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Estado</th> <th scope="col" class="px-6 py-4 font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @foreach ($posts as $post)
                <tr class="hover:bg-zinc-50 transition-colors duration-200 dark:hover:bg-zinc-800/50">
                    <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">{{ $post->id }}</td>
                    <td class="px-6 py-4">{{ $post->title }}</td>
                    
                    <td class="px-6 py-4">
                        @if ($post->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                                Publicado
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-zinc-400 rounded-full"></span>
                                Borrador
                            </span>
                        @endif
                    </td>

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