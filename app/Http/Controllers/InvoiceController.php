<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceEmail;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\jobs\InoviceEmailJob;

class InvoiceController extends Controller
{

    /**
     * Method index
     *
     * @return all invoices
     */
    public function index(Request $request)
    {
        $invoices = Invoice::with('client')->latest();

        if( !empty($request->client_id) ){
            $invoices = $invoices->where('client_id',$request->client_id);
        }

        if( !empty($request->status) ){
            $invoices = $invoices->where('status',$request->status);
        }

        if( !empty($request->emailsent) ){
            $invoices = $invoices->where('email_sent',$request->emailsent);
        }

        $invoices = $invoices->paginate(10);

        return view('invoice.index')->with([
            'clients' => Client::where('user_id',Auth::user()->id)->get(),
            'invoices' => $invoices,
        ]);
    }

    /**
     * Method create
     *
     * @param Request $request
     *
     * @return collections of tasks and clients
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
     * Method update
     *Update invoice status to paid
     * @param Request $request
     * @param Invoice $invoice [explicite description]
     *
     * @return void
     */
    public function update(Request $request,Invoice $invoice)
    {
        $invoice->update([
            'status'    => 'paid'
        ]);
        return redirect()->route('invoice.index')->with('success', 'Invoice Payment marked as paid!');
    }


    /**
     * Method destroy
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function destroy(Invoice $invoice)
    {
        Storage::delete('public/invoices/'.$invoice->download_url);
        $invoice->delete();
        return redirect()->route('invoice.index')->with('success','Invoice Deleted');
    }



    /**
     * Method getInvoiceData
     *
     * @param Request $request
     *
     * @return void
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
     * Method inovice
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function inovice(Request $request)
    {
        if( !empty($request->generate) && $request->generate == 'yes' ){
            $this->generate($request);
            return redirect()->route('invoice.index')->with('success', 'Invocie Created');
        }
        if( !empty($request->preview) && $request->preview == 'yes' ){

           if( !empty($request->discount) && !empty($request->discount_type) ){
               $discount = $request->discount;
               $discount_type = $request->discount_type;
           }else{
                $discount = 0;
                $discount_type = '';
           }

            $tasks = Task::whereIn('id',$request->invoice_ids)->get();

            return view('invoice.preview')->with([
                'invoice_no'  => 'INVO_'.rand(23435252,235235326532),
                'user'  => Auth::user(),
                'tasks' => $tasks,
                'discount' => $discount,
                'discount_type' => $discount_type,
            ]);
        }
    }


    /**
     * Method generate
     *
     * @param Request $request
     *
     * @return void
     */
    public function generate(Request $request)
    {

        if( !empty($request->discount) && !empty($request->discount_type) ){
            $discount = $request->discount;
            $discount_type = $request->discount_type;
        }else{
             $discount = 0;
             $discount_type = '';
        }


        $invo_no  ='INVO_'.rand(23435252,235235326532);
        $tasks = Task::whereIn('id',$request->invoice_ids)->get();
        $data = [
            'invoice_no'  => $invo_no ,
            'user'  => Auth::user(),
            'tasks' => $tasks,
            'discount' => $discount,
            'discount_type' => $discount_type,
        ];

        // Generation PDF
        $pdf = PDF::loadView('invoice.pdf', $data);

        // Store PDF in Storage
        Storage::put('public/invoices/'.$invo_no.'.pdf', $pdf->output());

        // Insert Invoice data
        Invoice::create([
            'invoice_id'    => $invo_no,
            'client_id'     => $tasks->first()->client->id,
            'user_id'       => Auth::user()->id,
            'status'        => 'unpaid',
            'amount'        => $tasks->sum('price'),
            'download_url'  => $invo_no.'.pdf'
        ]);


    }

    /**
     * Method sendEmail
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function sendEmail(Invoice $invoice)
    {
        $data = [
            'user'          => Auth::user(),
            'invoice_id'    => $invoice->invoice_id,
            'invoice'       => $invoice,
        ];

        // InoviceEmailJob::dispatch($data);
        dispatch(new InoviceEmailJob($data));

        $invoice->update([
            'email_sent'    => 'yes'
        ]);

        return redirect()->route('invoice.index')->with('success','Email sent');
    }

}
