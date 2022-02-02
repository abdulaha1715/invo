<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View Tasks') }}
            </h2>
            <a href="{{ route('task.create') }}" class="border border-emerald-400 px-3 py-1">Add New</a>
        </div>
    </x-slot>

    @include('layouts.messages')


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">


                    <div class="">
                        <h1>{{ $task->name }}</h1>
                        <h2>Price: ${{ $task->price }}</h2>
                        <h2>Client: {{ $task->client->name }}</h2>
                        <h1 class="my-3 font-bold">Task Details</h1>

                        <div class="border my-4 p-5">
                            {!! $task->description !!}
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
