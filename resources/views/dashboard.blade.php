<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <div class="">
        <div class="container mx-auto py-10">
            <div class="grid grid-cols-4 gap-5">
                <div class="bg-gradient-to-tr from-cyan-300 to-white rounded-md">
                    <a href="{{ route('client.index') }}" class="flex px-10 py-14 flex-col items-center">
                        <h1 class="font-bold text-3xl">{{ count($user->clients) }}</h1>
                        <h2 class="text-emerald-900 font-black uppercase">Clients</h2>
                    </a>
                </div>
                <div class="bg-gradient-to-tl from-cyan-300 to-white rounded-md">
                    <a href="{{ route('task.index') }}?status=pending" class="flex px-10 py-14 flex-col items-center">
                        <h1 class="font-bold text-3xl">{{ count($pending_tasks) }}</h1>
                        <h2 class="text-emerald-900 font-black uppercase">Pending Tasks</h2>
                    </a>
                </div>
                <div class="bg-gradient-to-bl from-cyan-300 to-white rounded-md">
                    <a href="{{ route('task.index') }}?status=complete" class="flex px-10 py-14 flex-col items-center">
                        <h1 class="font-bold text-3xl">{{ count($user->tasks) - count($pending_tasks) }}</h1>
                        <h2 class="text-emerald-900 font-black uppercase">Completed Tasks</h2>
                    </a>
                </div>
                <div class="bg-gradient-to-br from-cyan-300 to-white rounded-md">
                    <a href="{{ route('invoice.index') }}?status=unpaid" class="flex px-10 py-14 flex-col items-center">
                        <h1 class="font-bold text-3xl">{{ count($unpaid_invoices) }}</h1>
                        <h2 class="text-emerald-900 font-black uppercase">Due Invoice</h2>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="container mx-auto">
            <div class="flex justify-between space-x-5">
                <div class="prose max-w-none flex-1">
                    <h3 class="text-white">Todo:</h3>
                    <ul class="bg-cyan-600 px-5 py-4 inline-block rounded-md list-none">
                        @forelse ($pending_tasks->slice(0,5) as $task)
                            <li><a class="text-white hover:text-black transition-all duration-300"  href="{{ route('task.show',$task->slug) }}">{{ $task->name }}</a></li>
                        @empty
                        <li>No tasks found!</li>
                        @endforelse
                    </ul>
                </div>
                <div class="prose max-w-none flex-1">
                    <h3 class="text-white">Payment History:</h3>

                    <ul class="bg-cyan-600 text-white rounded-md px-5 py-4  list-none">
                        @forelse ($paid_invoices->slice(0,5) as $invoice)
                        <li class="flex justify-between items-center">
                            <span class="text-sm">{{ $invoice->updated_at->format('d M, Y') }}</span>
                            <span class="text-left flex-1 mx-5">{{ $invoice->client->name }}</span>
                            <span class="text-left">${{ $invoice->amount }}</span></li>
                        @empty
                        <li>No paid invoice found!</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
