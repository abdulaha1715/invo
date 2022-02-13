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
                                <th class="border py-2">Email Sent</th>
                                <th class="border py-2">Preview</th>
                                <th class="border py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>



                            @forelse ($invoices as $invoice)

                            <tr>
                                <td class="border py-2 text-center px-2">{{ $invoice->invoice_id }}</td>
                                <td class="border py-2 text-left px-2">{{ $invoice->client->name }}</td>
                                <td class="border py-2 text-center capitalize">{{ $invoice->status }}</td>
                                <td class="border py-2 text-center capitalize">{{ $invoice->email_sent }}</td>
                                <td class="border py-2 text-center">
                                    <a target="_blank" href="{{ asset('storage/invoices/' .$invoice->download_url )  }}" class="bg-teal-600 text-white px-3 py-1 mr-2">View</a>
                                </td>
                                <td class="border py-2 text-center">
                                    <div class="flex justify-center space-x-3">

                                        <a href="{{ route('invoice.sendEmail', $invoice) }}" class="border-2 bg-teal-600 text-white hover:bg-transparent hover:text-black transition-all duration-300 px-3 py-1 mr-2">Send Email</a>

                                        @if ($invoice->status == 'unpaid')

                                        <form action="{{ route('invoice.update', $invoice->id) }}" method="POST"
                                            onsubmit="return confirm('Did you get paid?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="border-2 bg-purple-500 text-white hover:bg-transparent hover:text-black transition-all duration-300 px-3 py-1 mr-2">Paid</button>
                                        </form>
                                        @endif

                                        <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST"
                                            onsubmit="return confirm('Do you really want to delete?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="border-2 bg-red-500 text-white hover:bg-transparent hover:text-black transition-all duration-300 px-3 py-1 mr-2">Delete</button>
                                        </form>
                                    </div>



                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td class="border py-2 text-center" colspan="5">No Invoice Found</td>
                            </tr>

                            @endforelse


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
