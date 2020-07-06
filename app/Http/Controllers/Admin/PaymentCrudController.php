<?php

namespace App\Http\Controllers\Admin;

use App\Device;
use App\Invoice;
use App\Models\BackpackUser;
use App\Payment;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\PaymentRequest as StoreRequest;
use App\Http\Requests\PaymentUpdateRequest as UpdateRequest;


class PaymentCrudController extends CrudController
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
        $this->crud->setModel(Payment::class);
        $this->crud->setEntityNameStrings('Wallet Invoice', 'Wallet Invoices');
        $this->crud->setRoute(backpack_url('payment'));
        $this->crud->denyAccess('delete');
//        $this->crud->denyAccess('update');
        $this->crud->allowAccess('show');

        if (!backpack_user()->hasRole('admin') && !backpack_user()->can('edit_invoices')) {
            if (backpack_user()->hasRole('user')) {
                $this->crud->addClause('where', 'user_id', backpack_user()->id);
            } elseif (backpack_user()->can('view_invoices')) {
                //
            } else {
                abort(403);
            }
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('delete');
            $this->crud->denyAccess('create');
        }
        $this->crud->addButtonFromModelFunction('line', 'open_pdf', 'open_pdf', 'beginning');
    }

    public function setupListOperation()
    {
        $this->crud->setColumns([
            [
                // run a function on the CRUD model and show its return value
                'name' => "user_id",
                'label' => "User", // Table column heading
                'type' => "model_function",
                'function_name' => 'userLink', // the method in your Model
                 'limit' => 1000, // Limit the number of characters shown
            ],
            [
                'name' => 'amount',
                'label' => 'Amount',
                'type' => 'string',
                'prefix' => config('app.currency')
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'boolean',
                'options' => [0 => 'DUE', 1 => 'PAID']
            ],
            [
                'name' => 'by_admin',
                'label' => 'Invoice By',
                'type' => 'model_function',
                'function_name' => 'invoice_by',
            ],
            [
                'name' => 'payment_method',
                'label' => 'Payment Method',
                'type' => 'text',
            ],
            [
                'name' => 'reference',
                'label' => 'Payment Reference',
                'type' => 'text',
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'type' => 'date',
            ],
            [
                'name' => 'paid_at',
                'label' => 'Paid At',
                'type' => 'date',
            ],
        ]);

        $this->crud->enableExportButtons();
//        if (backpack_user()->hasRole('user')) {
//            $this->crud->addButtonFromModelFunction('line', 'pay', 'payInvoice', 'beginning');
//        }
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
                'label'     => 'User', // Table column heading
                'type'      => 'select2',
                'name'      => 'user_id', // the column that contains the ID of that connected entity;
                'entity'    => 'user', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     =>  BackpackUser::class, // foreign key model
            ],
            [
                'name' => 'custom_id',
                'type' => 'number',
                'label' => 'Payment ID',
                // optionals
            ],
            [
                'name' => 'payment_method',
                'label' => 'Payment Method (Cash/Bank/Credit/Payment Gateway/etc.)',
                'type' => 'text',
            ],
            [
                'name' => 'reference',
                'label' => 'Payment Reference',
                'type' => 'text',
            ],
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'hidden',
            ],
            [
                'name' => 'by_admin',
                'label' => 'Label',
                'type' => 'hidden',
            ],
            [   // radio
                'name'        => 'status', // the name of the db column
                'label'       => 'Status', // the input label
                'type'        => 'radio',
                'options'     => [
                    // the key will be stored in the db, the value will be shown as label;
                    0 => "Due",
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
                'type' => 'number',
                // optionals
                'attributes' => ["step" => "0.01"], // allow decimals
                'prefix' => config('app.currency'),
            ],
        ]);
        $this->crud->setValidation(StoreRequest::class);

    }

    public function setupUpdateOperation()
    {
        $this->setupCreateOperation();
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


    public function addAmount($request)
    {
        $request->merge(['type' => 'wallet']);
        $request->merge(['by_admin' => backpack_user()->id]);

        if ((bool) $request->status && $request->paid_at) {
            $user = User::find($request->user_id);
            $user->balance += $request->amount;
            $user->save();
        }


        return $request;
    }
}
