<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('invoice.index')->with([
            'invoices' => Invoice::with('client')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('invoice.create')->with([
            'clients' => Client::where('user_id',Auth::user()->id)->get(),
            'tasks' => false
        ]);
    }

    public function edit()
    {

    }
    public function update(Request $request,Invoice $invoice)
    {

    }
    public function store(Request $request)
    {

    }
    public function show(Invoice $invoice)
    {

    }
    public function destroy(Invoice $invoice)
    {

    }



    public function search(Request $request)
    {
        $request->validate([
            'client_id' => ['required','not_in:none'],
            'status' => ['required','not_in:none'],
        ]);



        return view('invoice.create')->with([
            'clients' => Client::where('user_id',Auth::user()->id)->get(),
            'tasks' => $this->getInvoiceData($request),
        ]);


    }


    public function getInvoiceData(Request $request)
    {
        $tasks = Task::latest();

        if( !empty($request->client_id) ){
            $tasks = $tasks->where('client_id', '=', $request->client_id);
        }

        if( !empty($request->status) ){
            $tasks = $tasks->where('status', '=', $request->status);
        }

        if( !empty($request->fromDate) ){
            $tasks = $tasks->whereDate('created_at', '>=', $request->fromDate);
        }
        if( !empty($request->endDate) ){
            $tasks = $tasks->whereDate('created_at', '<=', $request->endDate);
        }

        return $tasks->get();

    }


    // Preview
    public function preview(Request $request)
    {
        return view('invoice.preview')->with([
            'invocie_no'  => 'INVO_'.rand(23435252,235235326532),
            'user'  => Auth::user(),
            'tasks' => $this->getInvoiceData($request),
        ]);
    }

}
