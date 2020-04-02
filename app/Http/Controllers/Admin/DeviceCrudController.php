<?php
namespace App\Http\Controllers\Admin;

use App\Device;
use App\Models\BackpackUser;
use App\Models\BillingCategory;
use App\Models\ConnectionMethod;
use App\Models\InstalledPlace;
use App\Models\PurchaseType;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\DeviceRequest as StoreRequest;
use App\Http\Requests\DeviceRequest as UpdateRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class DeviceCrudController extends CrudController
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
        $this->crud->setModel(Device::class);
        $this->crud->setEntityNameStrings('Device', 'Devices');
        $this->crud->setRoute(backpack_url('device'));
//        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');
        if (!backpack_user()->hasRole('admin') && !backpack_user()->can('edit_devices')) {
            if (backpack_user()->hasRole('owner')) {
                $this->crud->addClause('where', 'owner_id', backpack_user()->id);
            } elseif (backpack_user()->can('view_devices')) {
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
        $this->crud->setColumns([
            [
                'name' => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type' => 'text',
            ],
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'text',
            ],
            [
                'name' => 'serial_number',
                'label' => 'Serial Number',
                'type' => 'text',
            ],
            [
                'name' => 'IMEI_number',
                'label' => 'IMEI Number',
                'type' => 'text',
            ],
            [
                'name' => 'phone_number',
                'label' => 'Phone Number',
                'type' => 'text',
            ],
            [
                // 1-n relationship
                'label'     => 'Connection Method', // Table column heading
                'type'      => 'select',
                'name'      => 'connection_method_id', // the column that contains the ID of that connected entity;
                'entity'    => 'connectionMethod', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => ConnectionMethod::class, // foreign key model
            ],
            [
                // 1-n relationship
                'label'     => 'Installed Place', // Table column heading
                'type'      => 'select',
                'name'      => 'installed_place_id', // the column that contains the ID of that connected entity;
                'entity'    => 'installedPlace', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => InstalledPlace::class, // foreign key model
            ],
            [
                // 1-n relationship
                'label'     => 'Purchase Type', // Table column heading
                'type'      => 'select',
                'name'      => 'purchase_type_id', // the column that contains the ID of that connected entity;
                'entity'    => 'purchaseType', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => PurchaseType::class, // foreign key model
            ],
            [
                'name' => 'installed_at',
                'label' => 'Installation Date',
                'type' => 'date',
            ],
            [
                'name' => 'activated_at',
                'label' => 'Activation Date',
                'type' => 'date',
            ],
            [
                'name' => 'expires_at',
                'label' => 'Expiration Date',
                'type' => 'date',
            ],
            [
                'name' => 'ports', // The db column name
                'label' => "Ports", // Table column heading
                'type' => 'number',
            ],
            [
                'name' => 'place',
                'label' => 'Place',
                'type' => 'text',
            ],
            [
                'name' => 'comments',
                'label' => 'Comment',
                'type' => 'text',
            ],
            [
                'name' => 'installed_by',
                'label' => 'Installed By',
                'type' => 'text',
            ],
            [
                'name' => 'installation_photo', // The db column name
                'label' => "Photo", // Table column heading
                'type' => 'image',
            ],
//            [
//                // 1-n relationship
//                'label'     => 'Purchase Type', // Table column heading
//                'type'      => 'select',
//                'name'      => 'purchase_type', // the column that contains the ID of that connected entity;
//                'entity'    => 'purchaseType', // the method that defines the relationship in your Model
//                'attribute' => 'name', // foreign key attribute that is shown to user
//                'model'     => PurchaseType::class, // foreign key model
//            ],
            [
                // 1-n relationship
                'label'     => 'Billing Category', // Table column heading
                'type'      => 'select',
                'name'      => 'billing_category_id', // the column that contains the ID of that connected entity;
                'entity'    => 'billingCategory', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => BillingCategory::class, // foreign key model
            ],
            [
                // run a function on the CRUD model and show its return value
                'name' => "price",
                'label' => "Tariff Price", // Table column heading
                'type' => "model_function",
                'function_name' => 'tariffPrice', // the method in your Model
                // 'limit' => 100, // Limit the number of characters shown
            ],
            [
                // run a function on the CRUD model and show its return value
                'name' => "charging_time",
                'label' => "Charging Times", // Table column heading
                'type' => "model_function",
                'function_name' => 'chargingTimes', // the method in your Model
                // 'limit' => 100, // Limit the number of characters shown
            ],
            //TODO: Уточнить про стоимость углуг % или нет, billing category, maps, place(twice), owner address, charging time
            //TODO: add charging time model+migration
            //TODO: add owner fields and accessors in Model

        ]);
        $this->crud->enableDetailsRow();
        $this->crud->setDetailsRowView('vendor.backpack.additional.details_row.device_owner');
        $this->crud->enableExportButtons();
        $this->crud->addButtonFromModelFunction('line', 'open_google_maps', 'openGoogleMaps', 'beginning');
    }

    public function setupShowOperation()
    {
        $this->crud->set('show.contentClass', 'col-md-12');
        $this->setupListOperation();
        $this->crud->addColumn([
            // run a function on the CRUD model and show its return value
            'name' => "owner_info",
            'label' => "Owner Info", // Table column heading
            'type' => "model_function",
            'function_name' => 'ownerInfo', // the method in your Model
            // 'limit' => 100, // Limit the number of characters shown
        ]);
    }

    public function setupCreateOperation()
    {
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

        return $this->traitStore();
    }

    /**
     * Update the specified resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $this->crud->request = $this->crud->validateRequest();
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitUpdate();
    }

    protected function addDeviceFields()
    {
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type' => 'text',
            ],
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'text',
            ],
            [
                'name' => 'serial_number',
                'label' => 'Serial Number',
                'type' => 'text',
            ],
            [
                'name' => 'IMEI_number',
                'label' => 'IMEI Number',
                'type' => 'text',
            ],
            [
                'name' => 'phone_number',
                'label' => 'Phone Number',
                'type' => 'text',
            ],
            [
                // 1-n relationship
                'label'     => 'Connection Method', // Table column heading
                'type'      => 'select2',
                'name'      => 'connection_method_id', // the column that contains the ID of that connected entity;
                'entity'    => 'connectionMethod', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => ConnectionMethod::class, // foreign key model
            ],
            [
                // 1-n relationship
                'label'     => 'Installed Place', // Table column heading
                'type'      => 'select',
                'name'      => 'installed_place_id', // the column that contains the ID of that connected entity;
                'entity'    => 'installedPlace', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => InstalledPlace::class, // foreign key model
            ],
            [
                // 1-n relationship
                'label'     => 'Purchase Type', // Table column heading
                'type'      => 'select',
                'name'      => 'purchase_type_id', // the column that contains the ID of that connected entity;
                'entity'    => 'purchaseType', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => PurchaseType::class, // foreign key model
            ],
            [   // Date
                'name'  => 'installed_at',
                'label' => 'Installation Date',
                'type'  => 'date_picker',
                // optional:
                'date_picker_options' => [
                    'todayBtn' => true,
                    'format'   => 'dd-mm-yyyy',
                    'language' => 'en',
                ],
                // 'wrapperAttributes' => ['class' => 'col-md-6'],
            ],
            [   // Date
                'name'  => 'expires_at',
                'label' => 'Expiration Date',
                'type'  => 'date_picker',
                // optional:
                'date_picker_options' => [
                    'todayBtn' => true,
                    'format'   => 'dd-mm-yyyy',
                    'language' => 'en',
                ],
                // 'wrapperAttributes' => ['class' => 'col-md-6'],
            ],
            [   // Date
                'name'  => 'activated_at',
                'label' => 'Activation Date',
                'type'  => 'date_picker',
                // optional:
                'date_picker_options' => [
                    'todayBtn' => true,
                    'format'   => 'dd-mm-yyyy',
                    'language' => 'en',
                ],
                // 'wrapperAttributes' => ['class' => 'col-md-6'],
            ],
            [
                'name' => 'ports', // The db column name
                'label' => "Ports", // Table column heading
                'type' => 'number',
            ],
            [
                'name' => 'place',
                'label' => 'Place',
                'type' => 'text',
            ],
            [
                'name' => 'comments',
                'label' => 'Comment',
                'type' => 'text',
            ],
            [
                'name' => 'installed_by',
                'label' => 'Installed By',
                'type' => 'text',
            ],
            [
                'name' => 'installation_photo', // The db column name
                'label' => "Photo", // Table column heading
                'type' => 'image',
                'upload' => true,
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 0, // ommit or set to 0 to allow any aspect ratio
            ],
//            [
//                // 1-n relationship
//                'label'     => 'Purchase Type', // Table column heading
//                'type'      => 'select',
//                'name'      => 'purchase_type', // the column that contains the ID of that connected entity;
//                'entity'    => 'purchaseType', // the method that defines the relationship in your Model
//                'attribute' => 'name', // foreign key attribute that is shown to user
//                'model'     => PurchaseType::class, // foreign key model
//            ],
            [
                // 1-n relationship
                'label'     => 'Billing Category', // Table column heading
                'type'      => 'select',
                'name'      => 'billing_category_id', // the column that contains the ID of that connected entity;
                'entity'    => 'billingCategory', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => BillingCategory::class, // foreign key model
            ],
            [
                // 1-n relationship
                'label'     => 'Owner', // Table column heading
                'type'      => 'select2',
                'name'      => 'owner_id', // the column that contains the ID of that connected entity;
                'entity'    => 'owner', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     =>  BackpackUser::class, // foreign key model
            ],
            [
                'name' => 'hour_cost',
                'label' => 'Price',
                'type' => 'number',
                // optionals
                'attributes' => ["step" => "0.01"], // allow decimals
                'prefix' => "INR ",
                'suffix' => 'INR/kWh',
            ],
            [
                'name' => 'owner_cost',
                'label' => 'Owner charge',
                'type' => 'number',
                // optionals
                'attributes' => ["step" => "0.01"], // allow decimals
                'prefix' => "%",
            ],
            [
                'name' => 'service_cost',
                'label' => 'Service charge',
                'type' => 'number',
                // optionals
                'attributes' => ["step" => "0.01"], // allow decimals
                'prefix' => "%",
            ],
            [
                'name' => "charging_time",
                'label' => "Charging Times", // Table column heading
                'type' => "select_and_order",
                'options' => config('backpack.custom.charging_times'),
            ],
        ]);
    }
}
