@php
    $userPrefix = auth()->check() ? (auth()->user()->role === 'admin' ? 'admin' : \Illuminate\Support\Str::slug(auth()->user()->name)) : 'admin';
@endphp

<x-layouts::app :title="$post->title">
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Leer Post</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="max-w-4xl mx-auto bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden mb-12">
        @if($post->image_path)
            <img src="{{ $post->image_path }}" alt="{{ $post->title }}" class="w-full h-64 md:h-96 object-cover">
        @endif

        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $post->title }}</h1>
                
                <div class="flex space-x-2 shrink-0">
                    <form action="{{ route('posts.unpublish', $post) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <flux:button type="submit" variant="subtle">A Borrador</flux:button>
                    </form>

                    <form class="delete-form" action="{{ route('admin.posts.destroy', ['post' => $post, 'user_prefix' => $userPrefix]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <flux:button type="submit" variant="danger">Eliminar</flux:button>
                    </form>
                </div>
            </div>

            <div class="flex flex-wrap items-center text-sm text-zinc-500 dark:text-zinc-400 mb-8 gap-4">
                <span class="flex items-center">
                    <flux:icon name="clock" class="size-4 mr-1.5" />
                    {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Sin fecha' }}
                </span>
                
                @if($post->category)
                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800">
                        {{ $post->category->name }}
                    </span>
                @endif
            </div>

            <div class="prose dark:prose-invert max-w-none text-zinc-700 dark:text-zinc-300 whitespace-pre-line text-lg leading-relaxed">
                {{ $post->content }}
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // El mismo script de confirmación de borrado que usamos en tu index
            const forms = document.querySelectorAll('.delete-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    
                    Swal.fire({
                        title: "¿Eliminar post?",
                        text: "Esta acción lo borrará permanentemente de tu blog.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar"
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