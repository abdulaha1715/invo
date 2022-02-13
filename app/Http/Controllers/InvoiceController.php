<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    /**
     * Function Index
     * Display invoices
     */

    public function index()
    {
        return view('invoice.index')->with([
            'invoices' => Invoice::with('client')->latest()->paginate(10),
        ]);
    }

    /**
     * Funcation Create
     * @param request
     * Method Get
     * Search query
     *
     */
    public function create(Request $request)
    {

        $tasks = false;
        // If client id and status is not empty
        if(!empty($request->client_id) && !empty($request->status)){

            $request->validate([
                'client_id' => ['required','not_in:none'],
                'status' => ['required','not_in:none'],
            ]);
            $tasks = $this->getInvoiceData($request);
        }

        // Return
        return view('invoice.create')->with([
            'clients' => Client::where('user_id',Auth::user()->id)->get(),
            'tasks' => $tasks
        ]);
    }


    /**
     * Function Update
     * @param Request, Invoice
     * Update invoice status to paid
     */
    public function update(Request $request,Invoice $invoice)
    {
        $invoice->update([
            'status'    => 'paid'
        ]);
        return redirect()->route('invoice.index')->with('success', 'Invoice Payment marked as paid!');
    }

    /**
     * Function Destroy
     * Delete invoice info
     */
    public function destroy(Invoice $invoice)
    {
        Storage::delete('public/invoices/'.$invoice->download_url);
        $invoice->delete();
        return redirect()->route('invoice.index')->with('success','Invoice Deleted');
    }


    /**
     * Function Get Invoice Data
     * return tasks
     */
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


    /**
     * Function Preview
     * preview invoice
     */
    public function preview(Request $request)
    {

        return view('invoice.preview')->with([
            'invoice_no'  => 'INVO_'.rand(23435252,235235326532),
            'user'  => Auth::user(),
            'tasks' => $this->getInvoiceData($request),
        ]);
    }

    /**
     * Function Generate
     * PDF generation
     * Invoice Insert
     *
     */
    public function generate(Request $request)
    {
        $invo_no  ='INVO_'.rand(23435252,235235326532);
        $data = [
            'invoice_no'  => $invo_no ,
            'user'  => Auth::user(),
            'tasks' => $this->getInvoiceData($request),
        ];

        // Generation PDF
        $pdf = PDF::loadView('invoice.pdf', $data);

        // Store PDF in Storage
        Storage::put('public/invoices/'.$invo_no.'.pdf', $pdf->output());

        // Insert Invoice data
        Invoice::create([
            'invoice_id'    => $invo_no,
            'client_id'     => $request->client_id,
            'user_id'       => Auth::user()->id,
            'status'        => 'unpaid',
            'download_url'  => $invo_no.'.pdf'
        ]);


        return redirect()->route('invoice.index')->with('success', 'Invocie Created');
    }

    public function sendEmail(Invoice $invoice)
    {

        $pdf = Storage::get('public/invoices/'.$invoice->download_url);


        $data = [
            'user'   => Auth::user(),
            'invoice_id'   => $invoice->invoice_id,
            'client'   => $invoice->client
        ];

        Mail::send('emails.invoice', $data, function ($message) use($invoice,$pdf) {
            $message->from(Auth::user()->email, Auth::user()->name);
            $message->to($invoice->client->email, $invoice->client->name);
            $message->subject('Pixcafe - '. $invoice->invoice_id);
            $message->attachData($pdf, $invoice->download_url, [
                'mime'  => 'application/pdf'
            ]);
        });

        return redirect()->route('invoice.index')->with('success','Email sent');
    }

}
