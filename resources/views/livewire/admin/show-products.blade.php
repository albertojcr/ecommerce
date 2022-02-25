<div x-data="{ openAdvancedFilters: false }">
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
        <div class="px-6 py-4 flex">
            <x-jet-input class="w-1/3"
                         dusk="search"
                         wire:model="search"
                         type="text"
                         placeholder="Introduzca el nombre del producto a buscar" />
        </div>

        <div class="px-6 py-4 flex items-center gap-10">
            <div @click="openAdvancedFilters = !openAdvancedFilters"
                 class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 rounded font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 cursor-pointer">
                <i class="fa-solid fa-filter mr-2"></i>
                Filtros avanzados
            </div>

            <x-jet-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <div class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 rounded font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 cursor-pointer">
                        <i class="fa-solid fa-table-columns mr-1"></i>
                        Columnas
                    </div>
                </x-slot>

                <x-slot name="content">
                    @foreach($columns as $column)
                        <div class="block px-4 py-2 text-sm">
                            <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}">
                            <label>{{ __(ucfirst($column)) }}</label>
                        </div>
                    @endforeach
                </x-slot>
            </x-jet-dropdown>

            <div>
                Mostrar
                <select class="form-control" wire:model="filters.rowsToShow">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                resultados
            </div>

        </div>

        <!-- Filtros avanzados -->
        <div class="px-6 py-4 flex gap-4 border-2 bg-indigo-50 hidden" :class="{ 'hidden': !openAdvancedFilters }">
            <div>
                <x-jet-label value="Categoría" />
                <select class="form-control" wire:model="category_id">
                    <option value="" selected disabled>Seleccione una categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-jet-label value="Subcategoría" />
                <select class="w-full form-control" wire:model="subcategory_id">
                    <option value="" selected disabled>Seleccione una subcategoría</option>
                    @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-jet-label value="Marca" />
                <select class="form-control w-full" wire:model="brand_id">
                    <option value="" selected disabled>Seleccione una marca</option>
                        @foreach ($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                        @endforeach
                </select>
            </div>
            <div>
                <x-jet-label value="Precio" />
                <div id="slider" class="w-96"></div>
            </div>
            <div>
                <x-jet-label value="Fecha de creación" />
            </div>
            <div>
                <x-jet-label value="Color" />
            </div>
            <div>
                <x-jet-label value="Talla" />
            </div>
            <div>
                <x-jet-label value="Stock" />
            </div>

        </div>

        @if($products->count())
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>

                        @foreach($this->selectedColumns as $column)
                            <th wire:click="$set('fieldToOrder', '{{ $column }}')" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __(ucfirst($column)) }}
                            </th>
                        @endforeach

                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Editar</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr>
                            @if($this->showColumn('name'))
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
                            @endif
                            @if($this->showColumn('category'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->subcategory->category->name }}</div>
                                </td>
                            @endif
                            @if($this->showColumn('subcategory'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->subcategory->name }}</div>
                                </td>
                            @endif
                            @if($this->showColumn('brand'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->brand->name }}</div>
                                </td>
                            @endif
                            @if($this->showColumn('sizes'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @forelse($product->sizes as $size)
                                        <div class="text-sm text-gray-900 underline cursor-pointer"
                                             wire:loading.attr="disabled"
                                             wire:target="showSizeInfo"
                                             wire:click="showSizeInfo({{ $size }})">{{ $size->name }}</div>
                                    @endforeach
                                </td>
                            @endif
                            @if($this->showColumn('colors'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->subcategory->size)

                                        @foreach($product->sizes as $size)
                                            <div class="text-sm text-gray-500">
                                                @forelse($size->colors as $color)
                                                    <span class="pl-1">{{ '- ' . trans(ucfirst($color->name)) . ': ' . $color->pivot->quantity }}</span>
                                                @empty
                                                    <span>-</span>
                                                @endforelse
                                            </div>
                                        @endforeach

                                    @elseif($product->subcategory->color)

                                        @forelse($product->colors as $color)
                                            <div class="text-sm text-gray-900">{{ __(ucfirst($color->name)) . ': ' }} <span class="text-sm text-gray-500">{{ $color->pivot->quantity }}</span></div>
                                        @empty
                                            <span class="text-sm text-gray-500">-</span>
                                        @endforelse

                                    @endif

                                </td>
                            @endif
                            @if($this->showColumn('status'))
                                <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $product->status == 1 ? 'red' : 'green' }}-100 text-{{ $product->status == 1 ? 'red' : 'green' }}-800">
                                {{ $product->status == 1 ? 'Borrador' : 'Publicado' }}
                            </span>
                                </td>
                            @endif
                            @if($this->showColumn('price'))
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->price }} &euro;
                                </td>
                            @endif
                            @if($this->showColumn('created_at'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->created_at->format('d-m-Y') }}</div>
                                </td>
                            @endif

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

    @push('scripts')
        <script>
            let slider = document.getElementById('slider');

            noUiSlider.create(slider, {
                start: [1, 200],
                animate: true,
                tooltips: [true, true],
                connect: true,
                range: {
                    'min': 1,
                    'max': 200
                }
            });
        </script>
    @endpush
</div>
