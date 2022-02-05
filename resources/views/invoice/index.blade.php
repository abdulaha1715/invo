<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice') }}
            </h2>
            <a href="{{ route('invoice.create') }}" class="border border-emerald-400 px-3 py-1">Add New</a>
        </div>
    </x-slot>

    @include('layouts.messages')


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">



                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border py-2">#</th>
                                <th class="border py-2">Client</th>
                                <th class="border py-2">Status</th>
                                <th class="border py-2">Download</th>
                                <th class="border py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>



                            @foreach ($invoices as $invoice)

                            <tr>
                                <td class="border py-2 text-center px-2">{{ $invoice->invoice_id }}</td>
                                <td class="border py-2 text-left px-2">{{ $invoice->client->name }}</td>
                                <td class="border py-2 text-center">{{ $invoice->status }}</td>
                                <td class="border py-2 text-center">
                                    <a href="{{ $invoice->download_url }}" class="bg-purple-800 text-white px-3 py-1 mr-2">Download PDF</a>
                                </td>
                                <td class="border py-2 text-center">
                                    <div class="flex justify-center">
                                        <a href="{{ route('invoice.edit', $invoice->id) }}"
                                            class="bg-emerald-800 text-white px-3 py-1 mr-2">Edit</a>
                                        <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST"
                                            onsubmit="return confirm('Do you really want to delete?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-800 text-white px-3 py-1">Delete</button>
                                        </form>
                                    </div>



                                </td>
                            </tr>


                            @endforeach


                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $invoices->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
