<x-layouts::app>
    <div class="card">
        <form action="{{ route('admin.tags.update', $tag) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <flux:input name="name" label="Nombre" value="{{ old('name', $tag->name) }}" 
                oninput="string_to_slug(this.value, `input[name='slug']`)" class="mb-4" />
            <flux:input name="slug" label="Slug" value="{{ old('slug', $tag->slug) }}" class="mb-4" />
            
            <div class="flex justify-end mt-6">
                <flux:button variant="primary" type="submit">Actualizar</flux:button>
            </div>
        </form>
    </div>

    @push('js')
        <script src="{{ asset('js/assets/string_to_slug.js') }}"></script>
    @endpush
</x-layouts::app>