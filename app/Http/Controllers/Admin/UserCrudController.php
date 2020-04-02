<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\PermissionManager\app\Http\Requests\UserStoreCrudRequest as StoreRequest;
use Backpack\PermissionManager\app\Http\Requests\UserUpdateCrudRequest as UpdateRequest;
use Illuminate\Support\Facades\Hash;

class UserCrudController extends CrudController
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
        if (!backpack_user()->can('edit_users')) {
            if (!backpack_user()->can('view_users')) {
                abort(403);
//                dd(backpack_guard_name());
            } else {
                $this->crud->denyAccess('create');
                $this->crud->denyAccess('delete');
                $this->crud->denyAccess('update');
            }
        }
        $this->crud->setModel(config('backpack.permissionmanager.models.user'));
        $this->crud->setEntityNameStrings(trans('backpack::permissionmanager.user'), trans('backpack::permissionmanager.users'));
        $this->crud->setRoute(backpack_url('user'));
        $this->crud->allowAccess('show');
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
                'name' => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type' => 'email',
            ],
            [
            'name' => 'phone', // The db column name
            'label' => "Phone number", // Table column heading
            'type' => 'phone',
            ],
            [
                'name' => 'address',
                'label' => 'Address',
                'type' => 'text',
            ],
            [
                'name' => 'balance',
                'label' => 'Balance',
                'type' => 'string',
                // optionals
//                 'attributes' => ["step" => "0.01"], // allow decimals
                 'prefix' => "INR ",
            ],
            [
                'name' => 'verified',
                'label' => 'Approved',
                'type' => 'boolean',
                // optionally override the Yes/No texts
                 'options' => [0 => 'Unapproved', 1 => 'Approved']
            ],
            [
                'name' => 'rfid',
                'label' => 'RFID',
                'type' => 'string',
                // optionals
//                 'attributes' => ["step" => "0.01"], // allow decimals

            ],
            [
                'name' => 'identity_document', // The db column name
                'label' => "Document", // Table column heading
                'type' => 'image',
                // 'prefix' => 'folder/subfolder/',
                // optional width/height if 25px is not ok with you
                // 'height' => '30px',
                // 'width' => '30px',
            ],
            [
                'name' => 'avatar', // The db column name
                'label' => "Avatar", // Table column heading
                'type' => 'image',
            ],
            [ // n-n relationship (with pivot table)
                'label' => trans('backpack::permissionmanager.roles'), // Table column heading
                'type' => 'select_multiple',
                'name' => 'roles', // the method that defines the relationship in your Model
                'entity' => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => config('permission.models.role'), // foreign key model
            ],
            [ // n-n relationship (with pivot table)
                'label' => 'Permissions', // Table column heading
                'type' => 'select_multiple',
                'type' => 'select_multiple',
                'name' => 'permissions', // the method that defines the relationship in your Model
                'entity' => 'permissions', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => config('permission.models.permission'), // foreign key model
            ],
        ]);

        // Role Filter
        $this->crud->addFilter([
            'name' => 'role',
            'type' => 'dropdown',
            'label' => 'Role',
        ],
            config('permission.models.role')::all()->pluck('name', 'id')->toArray(),
            function ($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'roles', function ($query) use ($value) {
                    $query->where('role_id', '=', $value);
                });
            });

        // Extra Permission Filter
        $this->crud->addFilter([
            'name' => 'permissions',
            'type' => 'select2',
            'label' => 'Extra Permission',
        ],
            config('permission.models.permission')::all()->pluck('name', 'id')->toArray(),
            function ($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'permissions', function ($query) use ($value) {
                    $query->where('permission_id', '=', $value);
                });
            });
    }

    public function setupShowOperation()
    {
//        $this->crud->set('show.contentClass', 'col-md-12');
        $this->setupListOperation();
        $devicesList = [
            'name' => "devices_list",
            'label' => "Devices", // Table column heading
            'type' => "model_function",
            'function_name' => 'devicesList', // the method in your Model
        ];

        if ($this->crud->getCurrentEntry()->hasRole('owner')) {
            $this->crud->addColumn($devicesList)->afterColumn('name');
        }
    }


    public function setupCreateOperation()
    {
        $this->addUserFields();
        $this->crud->setValidation(StoreRequest::class);
    }

    public function setupUpdateOperation()
    {
        $this->addUserFields();
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
        $this->crud->request = $this->handlePasswordInput($this->crud->request);
        $this->crud->unsetValidation(); // validation has already been run

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
        $this->crud->request = $this->handlePasswordInput($this->crud->request);
        $this->crud->request = $this->handleRoles($this->crud->request);
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitUpdate();
    }

    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }

    protected function handleRoles($request)
    {
        $roles = $request->post('roles');
        foreach ($roles as $key => $role) {
            if ($role == config('backpack.custom.default_role') && count($roles) > 1) {
                unset($roles[$key]);
                $request->request->remove('roles');
                $request->request->set('roles', $roles);
                break;
            }
        }
        return $request;
    }

    protected function addUserFields()
    {
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type' => 'email',
            ],
            [
                'name' => 'balance',
                'label' => 'Balance',
                'type' => 'number',
                // optionals
                 'attributes' => ["step" => "0.01"], // allow decimals
                'prefix' => "$",
            ],
            [
                'name' => 'rfid',
                'label' => 'RFID',
                'type' => 'text',
                // optionals
//                 'attributes' => ["step" => "0.01"], // allow decimals

            ],
            [
                'label' => "Document",
                'name' => "identity_document",
                'type' => 'image',
                'upload' => true,
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 0, // ommit or set to 0 to allow any aspect ratio
                // 'disk' => 's3_bucket', // in case you need to show images from a different disk
                // 'prefix' => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
            ],
            [
                'name' => 'phone',
                'label' => 'Phone',
                'type' => 'text',
            ],
            [
                'name' => 'address',
                'label' => 'Address',
                'type' => 'text',
            ],
            [   // radio
                'name'        => 'verified', // the name of the db column
                'label'       => 'Approved', // the input label
                'type'        => 'radio',
                'options'     => [
                    // the key will be stored in the db, the value will be shown as label;
                    0 => "Unapproved",
                    1 => "Approved"
                ],
                // optional
                //'inline'      => false, // show the radios all on the same line?
            ],
            [
                'name' => 'password',
                'label' => trans('backpack::permissionmanager.password'),
                'type' => 'password',
            ],
            [
                'name' => 'password_confirmation',
                'label' => trans('backpack::permissionmanager.password_confirmation'),
                'type' => 'password',
            ],
            [
                // two interconnected entities
                'label' => trans('backpack::permissionmanager.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type' => 'checklist_dependency',
                'name' => ['roles', 'permissions'],
                'subfields' => [
                    'primary' => [
                        'label' => trans('backpack::permissionmanager.roles'),
                        'name' => 'roles', // the method that defines the relationship in your Model
                        'entity' => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute' => 'name', // foreign key attribute that is shown to user
                        'model' => config('permission.models.role'), // foreign key model
                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3, //can be 1,2,3,4,6
                    ],
                    'secondary' => [
                        'label' => ucfirst(trans('backpack::permissionmanager.permission_singular')),
                        'name' => 'permissions', // the method that defines the relationship in your Model
                        'entity' => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute' => 'name', // foreign key attribute that is shown to user
                        'model' => config('permission.models.permission'), // foreign key model
                        'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3, //can be 1,2,3,4,6
                    ],
                ],
            ],
        ]);

//        if (backpack_user()->hasRole('owner')) {
//            $this->crud->addField([])->afterField('name');
//        }
    }
}
