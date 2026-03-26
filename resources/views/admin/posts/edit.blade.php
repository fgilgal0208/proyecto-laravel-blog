<x-layouts::app>
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboards</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.posts.index') }}">Posts</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Editar</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    
    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')
        
        <div class="relative mb">
            <img id="imgPreview" class = "w-full aspect-video object-cover object-center" src="{{ $post->image_path }}" alt="Imagen del post">
            <div class="absolute top-8 right-8">
                <label class="bg-white px-4 py-2 rounded-lg cursor-pointer">Cambiar Imagen
                    <input type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(event, '#imgPreview')" >

                </label>

            </div>
        </div>
        
        <div class="card space-y-4">      
            <flux:input name="title" label="Título" value="{{ old('title', $post->title) }}" class="mb-4" 
                oninput="string_to_slug(this.value, `input[name='slug']`)" />
            
            <flux:input name="slug" label="Slug" value="{{ old('slug', $post->slug) }}" class="mb-4" />

            <flux:input name="image_path" label="URL de la imagen" value="{{ old('image_path', $post->image_path) }}" class="mb-4" />

            <flux:select name="category_id" label="Categoría" class="mb-4">
                @foreach ($categories as $category)
                    <flux:select.option value="{{ $category->id }}" {{ $category->id == $post->category_id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>              

            <flux:textarea name="excerpt" label="Extracto" class="mb-4">{{ old('excerpt', $post->excerpt) }}</flux:textarea>
            
            <flux:textarea name="content" label="Contenido" rows="6" class="mb-4">{{ old('content', $post->content) }}</flux:textarea>
            
            <div class="flex justify-end mt-6">
                <flux:button variant="primary" type="submit">Actualizar Post</flux:button>
            </div>
        </div>
    </form>

    @push('js')
        <script>
        function string_to_slug(str, querySelector){
            str = str.trim().toLowerCase();
            str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            str = str.replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
            
            let inputElement = document.querySelector(querySelector);
            if(inputElement) {
                inputElement.value = str;
                inputElement.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
        </script> 
    @endpush
</x-layouts::app>