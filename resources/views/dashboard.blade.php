<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">Últimos Posts Publicados</h1>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <a href="{{ route('posts.show', $post) }}" class="group flex flex-col overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-1">
                    
                    @if($post->image_path)
                        <img src="{{ $post->image_path }}" alt="{{ $post->title }}" class="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="h-48 w-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400">
                            Sin imagen
                        </div>
                    @endif

                    <div class="flex flex-col flex-1 p-5 relative z-10 bg-white dark:bg-zinc-900">
                        <h2 class="mb-2 text-xl font-semibold text-zinc-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $post->title }}</h2>
                        <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400 flex-1 line-clamp-3">
                            {{ $post->excerpt ?? 'Sin extracto disponible...' }}
                        </p>
                        
                        <div class="mt-auto flex items-center text-xs text-zinc-500 dark:text-zinc-400 font-medium">
                            <flux:icon name="clock" class="size-4 mr-1.5" />
                            Publicado el {{ $post->published_at->format('d/m/Y \a \l\a\s H:i') }}
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-zinc-300 p-12 text-center text-zinc-500 dark:border-zinc-700">
                    Aún no hay ningún post publicado.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts::app>