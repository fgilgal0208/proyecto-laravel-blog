<x-layouts::app>
    <div class="mb-6 flex justify-between items-center">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">
                Dashboards
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>
                Categories
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <a href="{{ route('admin.categories.create') }}" class="boton">Nuevo</a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left text-zinc-600">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-xs text-zinc-500 uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">ID</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Name</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Edit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
            @foreach ($categories as $category)
                <tr class="hover:bg-zinc-50 transition-colors duration-200">
                    <td class="px-6 py-4 font-medium text-zinc-900">{{ $category->id }}"</td>
                    <td class="px-6 py-4">{{ $category->name }}</td>
                    <td class="px-6 py-4">
                        <div class = "flex space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="boton">Edit</a>
                        
                            <form class="delete-form "action="{{ route('admin.categories.destroy' , $category) }}" method="POST">
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
    @push('js')
        <script>
            forms = document.querySelectorAll('.delete-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    
                    Swal.fire({
                    title: "Estás seguro?",
                    text: "No podras revertir este cambio!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes!"
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