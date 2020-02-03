@extends('backpack::layouts.top_left')

@section('content')
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body p-3 d-flex align-items-center"><i class="fa fa-cogs bg-primary p-3 font-2xl mr-3"></i>
                <div>
                    <div class="text-value-sm text-primary">${{backpack_user()->balance}}</div>
                    <div class="text-muted text-uppercase font-weight-bold small">Balance</div>
                </div>
            </div>
            <div class="card-footer px-3 py-2"><a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#"><span class="small font-weight-bold">Add Funds</span><i class="fa fa-angle-right"></i></a></div>
        </div>
    </div>
@endsection
