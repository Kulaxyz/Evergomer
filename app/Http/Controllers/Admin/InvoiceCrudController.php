<?php

namespace App\Http\Controllers\Admin;

use App\Charge;
use App\Device;
use App\Invoice;
use App\Models\BackpackUser;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\InvoiceRequest as StoreRequest;
use App\Http\Requests\InvoiceRequest as UpdateRequest;
use Carbon\Carbon;


class InvoiceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        $this->crud->setModel(Invoice::class);
        $this->crud->setEntityNameStrings('Invoice', 'Invoices');
        $this->crud->setRoute(backpack_url('invoice'));
//        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        if (!backpack_user()->hasRole('admin') && !backpack_user()->can('edit_invoices')) {
            if (backpack_user()->hasRole('user')) {
                $this->crud->addClause('where', 'user_rfid', backpack_user()->rfid);
            } elseif (backpack_user()->hasRole('owner')) {
                foreach (backpack_user()->stations as $device) {
                    $this->crud->addClause('where', 'device_serial', $device->serial_number);
                }
            } elseif (backpack_user()->can('view_invoices')) {
                //
            } else {
                abort(403);
            }
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('delete');
//            $this->crud->denyAccess('create');
        }
    }

    public function setupListOperation()
    {
//        if (!backpack_user()->can('edit_invoices')) {
//            if (!backpack_user()->can('view_invoices')) {
//                abort(403);
//            }
//        }
        $this->crud->setColumns([
            [
                'name' => 'finished_at',
                'label' => 'Date',
                'type' => 'datetime',
            ],
            [
                // run a function on the CRUD model and show its return value
                'name' => "device_serial",
                'label' => "Device", // Table column heading
                'type' => "model_function",
                'function_name' => 'deviceLink', // the method in your Model
                 'limit' => 1000, // Limit the number of characters shown
            ],
            [
                // run a function on the CRUD model and show its return value
                'name' => "user_rfid",
                'label' => "User", // Table column heading
                'type' => "model_function",
                'function_name' => 'userLink', // the method in your Model
                 'limit' => 1000, // Limit the number of characters shown
            ],
//            [
//                // run a function on the CRUD model and show its return value
//                'name' => "charge_id",
//                'label' => "Session ID", // Table column heading
//                'type' => "model_function",
//                'function_name' => 'charge_info', // the method in your Model
//                 'limit' => 1000, // Limit the number of characters shown
//            ],
            [
                'name' => 'amount',
                'label' => 'Amount',
                'type' => 'string',
                'prefix' => config('app.currency')
            ],
//            [
//                'name' => 'charge_power',
//                'label' => 'Consumed Power',
//                'type' => 'string',
//                'suffix' => ' kWh'
//            ],
//            [
//                'name' => 'auth_type',
//                'label' => 'Auth Type',
//                'type' => 'string',
//            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'boolean',
                'options' => [1 => 'Charging', 2 => 'Completed']
            ],
//            [
//                'name' => 'started_at',
//                'label' => 'Charging Start',
//                'type' => 'datetime',
//            ],
            [
                'name' => 'charge_duration',
                'label' => 'Charge Duration',
                'type' => 'string',
                'suffix' => ' minutes'
            ],
//            [
//                'name' => 'charge_hours',
//                'label' => 'Charge Hours',
//                'type' => 'model_function',
//                'function_name' => 'charge_hours_text',
//                'limit' => 1000, // Limit the number of characters shown
//            ],
//            [
//                'name' => 'port_number', // The db column name
//                'label' => "Port", // Table column heading
//                'type' => 'number',
//            ],
//            [
//                'name' => 'paid_at',
//                'label' => 'Paid At',
//                'type' => 'date',
//            ],
        ]);

        $this->crud->enableExportButtons();
        if (backpack_user()->hasRole('user')) {
            $this->crud->addButtonFromModelFunction('line', 'pay', 'payInvoice', 'beginning');
        }
    }

    public function setupShowOperation()
    {
        $this->crud->set('show.contentClass', 'col-md-12');
        $this->setupListOperation();
    }

    public function setupCreateOperation()
    {
        $this->crud->addFields([
            [
                // 1-n relationship
                'label'     => 'Device Serial Number', // Table column heading
                'type'      => 'select2',
                'name'      => 'device_serial', // the column that contains the ID of that connected entity;
                'entity'    => 'device', // the method that defines the relationship in your Model
                'attribute' => 'serial_number', // foreign key attribute that is shown to user
                'model'     => Device::class, // foreign key model
            ],
            [
                // 1-n relationship
                'label'     => 'User ID', // Table column heading
                'type'      => 'select2',
                'name'      => 'user_rfid', // the column that contains the ID of that connected entity;
                'entity'    => 'user', // the method that defines the relationship in your Model
                'attribute' => 'id', // foreign key attribute that is shown to user
                'model'     => BackpackUser::class, // foreign key model
            ],
            [
                'label'     => 'Session ID',
                'type'      => 'number',
                'name'      => 'charge_id'
            ],
        ]);
        $this->addDeviceFields();
        $this->crud->setValidation(StoreRequest::class);

    }

    public function setupUpdateOperation()
    {
        $this->addDeviceFields();
        $this->crud->setValidation(UpdateRequest::class);
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->crud->request = $this->crud->validateRequest();
        $this->crud->unsetValidation(); // validation has already been run
        $this->crud->request = $this->addAmount($this->crud->request);
        $this->crud->request = $this->handleTime($this->crud->request);
        $this->crud->request = $this->handleSession($this->crud->request);

        return $this->traitStore();
    }

    /**
     * Update the specified resource in the database.
     *
     * @return \Backpack\CRUD\app\Http\Controllers\Operations\Response
     */
    public function update()
    {
        $this->crud->request = $this->crud->validateRequest();
        $this->crud->unsetValidation(); // validation has already been run
        $this->crud->request = $this->addAmount($this->crud->request);
        $this->crud->request = $this->handleTime($this->crud->request);

        return $this->traitUpdate();
    }

    protected function addDeviceFields()
    {
        $this->crud->addFields([
            [
                'name' => 'auth_type',
                'label' => 'Auth Type',
                'type' => 'text',
            ],
            [
                'name' => 'charge_duration',
                'label' => 'Charge Duration',
                'type' => 'number',
                'attributes' => ["step" => "0.1"], // allow decimals
                'suffix' => ' minutes'
            ],
            [
                'name' => 'charge_power',
                'label' => 'Consumed Power',
                'type' => 'number',
                'attributes' => ["step" => "0.1"], // allow decimals
                'suffix' => ' kWh'
            ],
//            [
//                'name' => 'status',
//                'label' => 'Status',
//                'type' => 'boolean',
//                'options' => [0 => 'Waiting', 1 => 'Paid']
//            ],
            [
                'name' => 'port_number', // The db column name
                'label' => "Used Port", // Table column heading
                'type' => 'number',
            ],
            [
                'name' => 'charge_hours',
                'label' => 'Charge Hours (Leave empty if "Auto")',
                'type' => 'number',
            ],
            [   // radio
                'name'        => 'status', // the name of the db column
                'label'       => 'Status', // the input label
                'type'        => 'radio',
                'options'     => [
                    // the key will be stored in the db, the value will be shown as label;
                    1 => "Charging",
                    2 => "Completed",
                    3 => 'Aborted (Error)'
                ],
                // optional
                //'inline'      => false, // show the radios all on the same line?
            ],
            [   // Date
                'name'  => 'finished_at',
                'label' => 'Charging End',
                'type'  => 'datetime_picker',
                // optional:
                'date_picker_options' => [
                    'todayBtn' => true,
                    'format'   => 'dd-mm-yyyy hh:ii',
                    'language' => 'en',
                    'clearBtn' => true,
                ],
                // 'wrapperAttributes' => ['class' => 'col-md-6'],
            ],
            [
                'name' => 'amount',
                'type' => 'hidden',
                'value' => 0
            ],
            [
                'name' => 'started_at',
                'type' => 'hidden',
                'value' => Carbon::now(),
            ],
        ]);
    }

    public function handleSession($request)
    {
        if ($request->charge_id) {
            $charge = Charge::where('custom_id', $request->charge_id)->first();
            if ($charge) {
                $request->request->set('charge_id', $charge->id);
            }
        }
        return $request;
    }

    public function handleTime($request)
    {
        $start = Carbon::parse($request->finished_at)->subMinutes($request->charge_duration);
        $request->request->set('started_at', $start);

        return $request;
    }

    public function addAmount($request)
    {
        $invoice = $this->crud->getCurrentEntry();
        if (!$invoice || (isset($invoice) && isset($invoice->charge_power) && $invoice->charge_power != $request->post('charge_power'))) {
            $device = $request->post('device_serial') ?
                Device::find($request->post('device_serial')) :
                Device::where('serial_number', $invoice->device_serial)->first();
            $amount = Invoice::countAmount($device, $request->toArray());
            $request->request->set('amount', $amount);
        }

        return $request;
    }
}
