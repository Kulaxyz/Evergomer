<?php

namespace App\Http\Controllers\Admin;

use App\Device;
use App\Invoice;
use App\Models\BackpackUser;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\InvoiceRequest as StoreRequest;
use App\Http\Requests\InvoiceRequest as UpdateRequest;


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
            $this->crud->denyAccess('create');
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
            [
                'name' => 'amount',
                'label' => 'Amount',
                'type' => 'string',
                'prefix' => 'INR '
            ],
            [
                'name' => 'charge_duration',
                'label' => 'Charge Duration',
                'type' => 'string',
                'suffix' => ' minutes'
            ],
            [
                'name' => 'charge_power',
                'label' => 'Consumed Power',
                'type' => 'string',
                'suffix' => ' kWh'
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'boolean',
                'options' => [0 => 'Waiting', 1 => 'Paid']
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'type' => 'date',
            ],
            [
                'name' => 'port_number', // The db column name
                'label' => "Used Port", // Table column heading
                'type' => 'number',
            ],
            [
                'name' => 'paid_at',
                'label' => 'Paid At',
                'type' => 'date',
            ],
            [
                'name' => 'payment_method',
                'label' => 'Payment Method',
                'type' => 'text',
            ],
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
                'label'     => 'User RFID', // Table column heading
                'type'      => 'select2',
                'name'      => 'user_rfid', // the column that contains the ID of that connected entity;
                'entity'    => 'user', // the method that defines the relationship in your Model
                'attribute' => 'rfid', // foreign key attribute that is shown to user
                'model'     => BackpackUser::class, // foreign key model
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

        return $this->traitUpdate();
    }

    protected function addDeviceFields()
    {
        $this->crud->addFields([
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
                'name' => 'payment_method',
                'label' => 'Payment Method',
                'type' => 'text',
            ],
            [   // radio
                'name'        => 'status', // the name of the db column
                'label'       => 'Status', // the input label
                'type'        => 'radio',
                'options'     => [
                    // the key will be stored in the db, the value will be shown as label;
                    0 => "Waiting",
                    1 => "Paid"
                ],
                // optional
                //'inline'      => false, // show the radios all on the same line?
            ],
            [   // Date
                'name'  => 'paid_at',
                'label' => 'Paid At',
                'type'  => 'date_picker',
                // optional:
                'date_picker_options' => [
                    'todayBtn' => true,
                    'format'   => 'dd-mm-yyyy',
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
        ]);
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
