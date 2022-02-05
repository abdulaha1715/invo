<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Preview Invoice') }}
            </h2>
            <a href="#" class="border border-emerald-400 px-3 py-1 text-lg">Send Email</a>
        </div>
    </x-slot>


    <div class="py-20 border-t bg-white">
        <div class="container mx-auto">
            @include('invoice.pdf')
        </div>
    </div>


</x-app-layout>
