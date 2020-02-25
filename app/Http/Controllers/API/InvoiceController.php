<?php

namespace App\Http\Controllers\Api;

use App\Device;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Invoice;
use App\User;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function create(InvoiceRequest $request) : JsonResponse
    {
        //TODO: notify user on session finish!!!
        $data = $request->toArray();
        $device = Device::where('serial_number', $data['device_serial'])->first();
        $amount = Invoice::countAmount($device, $data);
        $user = User::where('rfid', $data['user_rfid'])->first();

        if ($user->balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough money on wallet! Required:' . $amount . '$',
            ], 200);
        }
        $user->balance -= $amount;
        $user->save();

        $request->merge(['amount' => $amount]);
        $invoice = Invoice::create($request->all());
        $invoice->make_paid();

        return response()->json([
            'success' => true,
            'invoice' => $invoice,
        ], 200);
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
