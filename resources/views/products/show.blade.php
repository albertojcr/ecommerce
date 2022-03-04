<x-app-layout>
    <div class="container-menu py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <div>
                <div class="flexslider">
                    <ul class="slides">
                        @foreach($product->images as $image)
                            <li data-thumb="{{ Storage::url($image->url) }}">
                                <img dusk="product-image-{{ $image->id }}" src="{{ Storage::url($image->url) }}" />
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="-mt-10 text-gray-700">
                    <h2 class="font-bold text-lg">Descripción</h2>
                    {!! $product->description !!}
                </div>

                @can('review', $product)
                    <div class="text-gray-700 mt-4">
                        <h2 class="font-bold text-lg">Dejar reseña</h2>

                        <form action="{{ route('reviews.store', $product) }}" method="POST">
                            @csrf

                            <textarea name="comment"
                                      x-data
                                      x-init="ClassicEditor
                                        .create($refs.editor, {
                                            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
                                        } )
                                        .catch( error => {
                                            console.log( error );
                                        });"
                                      x-ref="editor">
                            </textarea>

                            <x-jet-input-error for="comment"></x-jet-input-error>

                            <div class="flex items-center mt-2" x-data="{ rating: 5 }">
                                <p class="font-semibold mr-3">Calificación: </p>

                                <ul class="flex space-x-2">
                                    <li :class="rating >= 1 ? 'text-yellow-500' : ''">
                                        <button type="button" class="focus:outline-none" @click="rating = 1">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </li>
                                    <li :class="rating >= 2 ? 'text-yellow-500' : ''">
                                        <button type="button" class="focus:outline-none" @click="rating = 2">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </li>
                                    <li :class="rating >= 3 ? 'text-yellow-500' : ''">
                                        <button type="button" class="focus:outline-none" @click="rating = 3">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </li>
                                    <li :class="rating >= 4 ? 'text-yellow-500' : ''">
                                        <button type="button" class="focus:outline-none" @click="rating = 4">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </li>
                                    <li :class="rating >= 5 ? 'text-yellow-500' : ''">
                                        <button type="button" class="focus:outline-none" @click="rating = 5">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </li>
                                </ul>

                                <x-jet-button class="ml-auto">
                                    Agregar reseña
                                </x-jet-button>

                                <input x-model="rating" class="hidden" type="number" name="rating">
                            </div>
                        </form>
                    </div>
                @endcan

                @if($product->reviews->isNotEmpty())

                    <div class="mt-6">
                        <h2 class="font-bold text-lg">Reseñas</h2>

                        <div class="mt-2">
                            @foreach($product->reviews as $review)
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <img class="w-10 h-10 rounded-full object-cover mr-4" src="{{ $review->user->profile_photo_url }}" alt="$review->user->name">
                                    </div>

                                    <div class="flex-1">
                                        <p class="font-semibold">{{ $review->user->name }} <span class="text-gray-500 text-sm">({{ $review->created_at->diffForHUmans() }})</span></p>
                                        <div>
                                            {!! $review->comment !!}
                                        </div>
                                    </div>

                                    <div>
                                        <p>
                                            {{ $review->rating }}

                                            <i class="fas fa-star text-yellow-500"></i>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @endif

            </div>
            <div>
                <h1 class="text-xl font-bold text-trueGray-700">{{ $product->name }}</h1>
                <div class="flex">
                    <p class="text-trueGray-700">Marca: <a class="underline capitalize hover:text-orange-500" href="">{{ $product->brand->name }}</a></p>
                    <p class="text-trueGray-700 mx-6">
                        {{ round($product->reviews->avg('rating'), 1) }}
                        <i class="fas fa-star text-sm text-yellow-400"></i>
                    </p>
                    <a class="text-orange-500 hover:text-orange-600 underline" href="">
                        {{ $product->reviews->count() }} reseñas
                    </a>
                </div>
                <p class="text-2xl font-semibold text-trueGray-700 my-4">{{ $product->price }} &euro;</p>
                <div class="bg-white rounded-lg shadow-lg mb-6">
                    <div class="flex items-center p-4">
                        <span class="flex items-center justify-center h-10 w-10 rounded-full bg-lime-600">
                            <i class="fas fa-truck text-sm text-white"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-lg font-semibold text-lime-600">Se hacen envíos solo a la península</p>
                            <p>Recíbelo el {{ Date::now()->addDay(7)->locale('es')->format('l j F') }}</p>
                        </div>
                    </div>
                </div>

                @if ($product->subcategory->size)
                    @livewire('add-cart-item-size', ['product' => $product])
                @elseif($product->subcategory->color)
                    @livewire('add-cart-item-color', ['product' => $product])
                @else
                    @livewire('add-cart-item', ['product' => $product])
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.flexslider').flexslider({
                    animation: "slide",
                    controlNav: "thumbnails"
                });
            });
        </script>
    @endpush
</x-app-layout>
