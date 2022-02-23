<div>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-600">
                Lista de productos
            </h2>
            <x-button-link class="ml-auto" href="{{route('admin.products.create')}}">
                Agregar producto
            </x-button-link>
        </div>
    </x-slot>

    <!-- Tabla con filtros -->
    <x-table-responsive>
        <div class="px-6 py-4">
            <x-jet-input class="w-full"
                         dusk="search"
                         wire:model="search"
                         type="text"
                         placeholder="Introduzca el nombre del producto a buscar" />
        </div>

        @if($products->count())
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Categoría y subcategoría
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Marca
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Colores
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tallas
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Precio
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha creación
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Editar</span>
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 object-cover">
                                    <img class="h-10 w-10 rounded-full"
                                         src="{{ $product->images->count() ? Storage::url($product->images->first()->url) : 'img/default.jpg' }}"
                                         alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $product->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $product->subcategory->category->name }}</div>
                            <div class="text-sm text-gray-500">{{ $product->subcategory->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $product->brand->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @forelse($product->colors as $color)
                                <div class="text-sm text-gray-900">{{ __(ucfirst($color->name)) . ': ' }} <span class="text-sm text-gray-500">{{ $color->pivot->quantity }}</span></div>
                            @empty
                                <span class="text-sm text-gray-500">-</span>
                            @endforelse
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @forelse($product->sizes as $size)
                                <div class="text-sm text-gray-900 underline cursor-pointer"
                                     wire:loading.attr="disabled"
                                     wire:target="showSizeInfo"
                                     wire:click="showSizeInfo({{ $size }})">{{ $size->name }}</div>
                            @empty
                                <span class="text-sm text-gray-500">-</span>
                            @endforelse
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $product->status == 1 ? 'red' : 'green' }}-100 text-{{ $product->status == 1 ? 'red' : 'green' }}-800">
                            {{ $product->status == 1 ? 'Borrador' : 'Publicado' }}
                        </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->price }} &euro;
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $product->created_at->format('d-m-Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-4">
                No existen productos coincidentes
            </div>
        @endif

        @if($products->hasPages())
            <div class="px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </x-table-responsive>

    @if($shownSize)
        <x-jet-dialog-modal wire:model="open">
            <x-slot name="title">
                {{ $shownSize->product->name }}

            </x-slot>
            <x-slot name="content">
                <div class="mb-4">
                    {{ $shownSize->name }}

                    @foreach($shownSize->colors as $color)
                        <x-jet-label>{{ __(ucfirst($color->name)) . ': ' . $color->pivot->quantity }}</x-jet-label>
                    @endforeach
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$set('open', false)">
                    Cerrar
                </x-jet-secondary-button>
            </x-slot>
        </x-jet-dialog-modal>
    @endif




    <!-- Tabla original -->
    {{--<x-table-responsive>
        <div class="px-6 py-4">
            <x-jet-input class="w-full"
                         dusk="search"
                         wire:model="search"
                         type="text"
                         placeholder="Introduzca el nombre del producto a buscar" />
        </div>

        @if($products->count())
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nombre
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Categoría
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Estado
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Precio
                </th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Editar</span>
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 object-cover">
                                <img class="h-10 w-10 rounded-full"
                                     src="{{ $product->images->count() ? Storage::url($product->images->first()->url) : 'img/default.jpg' }}"
                                     alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $product->name }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $product->subcategory->category->name }}</div>
                        <div class="text-sm text-gray-500">{{ $product->subcategory->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $product->status == 1 ? 'red' : 'green' }}-100 text-{{ $product->status == 1 ? 'red' : 'green' }}-800">
                            {{ $product->status == 1 ? 'Borrador' : 'Publicado' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $product->price }} &euro;
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <div class="px-6 py-4">
                No existen productos coincidentes
            </div>
        @endif

        @if($products->hasPages())
            <div class="px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </x-table-responsive>--}}

</div>
