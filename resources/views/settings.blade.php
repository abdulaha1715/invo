<x-app-layout>
    <x-slot name="header" >

    </x-slot>

    @php
    function getImageUrl($image){
        if( str_starts_with($image, 'http') ){
        return $image;
        }
        return asset('storage/uploads') . '/' . $image;
        }
    @endphp

    <div class="py-10">
        <div class="max-w-5xl mx-auto">
            <div class="">
                <div class="py-36 relative bg-black bg-opacity-50 bg-blend-overlay bg-cover bg-no-repeat bg-center mb-10"
                    style="background-image: url('https://picsum.photos/1024/400')">

                    <div class="absolute bottom-5 left-5 flex space-x-5">
                        <div class=" w-28 h-28 bg-center bg-cover rounded-full "
                            style="background-image: url({{ getImageUrl(Auth::user()->thumbnail) }})"></div>
                        <div class="">
                            <h1 class="text-white mt-5 text-lg">{{ Auth::user()->name }}</h1>
                            <h2 class="text-white">{{ Auth::user()->email }}</h2>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
