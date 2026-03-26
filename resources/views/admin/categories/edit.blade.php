<x-layouts::app>
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">
            Dashboards
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">
            Categories
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>
            Editar
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>
    
    <div class="card">
<form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

                <flux:input name="name" label="Nombre" value="{{ old('name' , $category->name) }}" placeholder="Escriba el nombre de la categoría" class="mb-4" />
                <div class="flex justify-end ">
                    <flux:button variant="primary" type="submit">Enviar</flux:button>
                </div>
        </form>


    </div> 
</x-layouts::app>