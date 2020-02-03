<?php

namespace App\Http\Controllers\Api;

use App\Device;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Invoice;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param InvoiceRequest $request
     * @return Response
     */
    public function create(InvoiceRequest $request)
    {
//        $device = Device::all();
        $data = $request->toArray();
        if (!isset($data['secret'])) {
            abort(403);
        }
        if ($data['secret'] != Setting::get('api_secret')) {
            abort(403);
        }

        $device = Device::where('serial_number', $data['device_serial'])->first();
        $request->merge(['amount' => Invoice::countAmount($device, $data)]);
        $invoice = Invoice::create($request->all());

        return response()->json($invoice);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
