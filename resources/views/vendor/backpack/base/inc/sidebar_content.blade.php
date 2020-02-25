<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@hasrole('admin')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
@endhasrole
@if(!backpack_user()->hasRole('admin'))
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('wallet') }}"><i class="fa fa-dashboard nav-icon"></i>Wallet</a></li>
@endif

@if(backpack_user()->can('edit_users') || backpack_user()->can('view_users'))
    <!-- Users, Roles, Permissions -->
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user').'?role='.config('backpack.custom.default_role') }}"><i class="nav-icon fa fa-user"></i>
            <span>Users</span>
            @php
                $count = App\User::where('verified', 0)->count();
            @endphp
            @if($count)
                <span class="badge badge-pill badge-danger">{{ $count }}</span>
            @endif
        </a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('user').'?role='.config('backpack.custom.owner_role') }}'><i class='nav-icon fa fa-user-secret'></i>Owners</a></li>
@endif
@if(backpack_user()->can('edit_devices') || backpack_user()->can('have_station') || backpack_user()->can('view_devices'))
    <!-- Devices, Connection Methods, Purchase Types, Installed Places -->
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('device') }}"><i class="nav-icon fa fa-cogs"></i><span>Devices</span></a></li>
@endif

@if(backpack_user()->can('view_invoices') || backpack_user()->can('edit_invoices') || backpack_user()->hasAnyRole('owner', 'user', 'admin'))
    <!-- Devices, Connection Methods, Purchase Types, Installed Places -->
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('invoice') }}"><i class="nav-icon fa fa-dollar"></i><span>Invoices</span>
            @php
                $count = App\Invoice::where('status', 0)->where('user_rfid', backpack_user()->rfid)->count();
            @endphp
            @if($count && backpack_user()->hasRole('user'))
                <span class="badge badge-pill badge-danger">{{ $count }}</span>
            @endif
        </a></li>
@endif
@can('view_staff')
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('staff')}}'><i class='nav-icon fa fa-users'></i>Staff</a></li>
@endcan
<!-- Settings -->
@can('edit_settings')
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-cogs"></i>Settings</a>
        <ul class="nav-dropdown-items">
            @can('edit_devices')
                <li style="padding-left: 0.8rem;" class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i>Device Attributes</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('connection_method') }}"><i class="nav-icon fa fa-user"></i><span>Connection Methods</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('installed_place') }}"><i class="nav-icon fa fa-group"></i> <span>Installed Places</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('purchase_type') }}"><i class="nav-icon fa fa-key"></i> <span>Purchase Types</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('billing_category') }}"><i class="nav-icon fa fa-key"></i> <span>Billing Category</span></a></li>
                    </ul>
                </li>
            @endcan
            @can('edit_users')
                <li style="padding-left: 0.8rem;" class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i>Members & Permissions</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon fa fa-group"></i> <span>Roles</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon fa fa-key"></i> <span>Permissions</span></a></li>
                    </ul>
                </li>
            @endcan
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('setting') }}"><i class="nav-icon fa fa-user"></i><span>General</span></a></li>
        </ul>
    </li>

@endcan