<x-layouts::app>
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboards</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.posts.index') }}">Posts</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Nuevo</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    
    <div class="card">
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <flux:input name="title" label="Título" value="{{ old('title') }}" 
                placeholder="Escriba el titulo del post" class="mb-4" 
                oninput="string_to_slug(this.value, `input[name='slug']`)" />
            
            <flux:input name="slug" label="Slug" value="{{ old('slug') }}" 
                placeholder="Slug-del-post" class="mb-4" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <flux:input name="image_path" label="URL de la imagen" placeholder="https://ejemplo.com/imagen.jpg" 
                    value="{{ old('image_path') }}" />
                    
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">O subir archivo de imagen</label>
                    <input type="file" name="image" accept="image/*" class="w-full border-zinc-200 rounded-lg p-2 border">
                </div>
            </div>
                
            <flux:select name="category_id" label="Categoría" class="mb-4">
                @foreach ($categories as $category)
                    <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                @endforeach
            </flux:select>              

            <flux:textarea name="excerpt" label="Extracto" class="mb-4">{{ old('excerpt') }}</flux:textarea>
            
            <flux:textarea name="content" label="Contenido" rows="6" class="mb-4">{{ old('content') }}</flux:textarea>
            
            <div class="flex justify-end mt-6">
                <flux:button variant="primary" type="submit">Enviar</flux:button>
            </div>
        </form>
    </div>

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