@extends('backpack::layouts.top_left')

@section('after_styles')
    <style media="screen">
        .backpack-profile-form .required::after {
            content: ' *';
            color: red;
        }
    </style>
    <link rel="stylesheet" href="{{asset('/css/fileUpload.css')}}">
@endsection

@php
    $breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        trans('backpack::base.my_account') => false,
    ];
@endphp

@section('header')
    <section class="content-header">
        <div class="container-fluid mb-3">
            <h1>{{ trans('backpack::base.my_account') }}</h1>
        </div>
    </section>
@endsection

@section('content')
    <div class="row">

        @if (session('success'))
            <div class="col-lg-8">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->count())
            <div class="col-lg-8">
                <div class="alert alert-danger">
                    <ul class="mb-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if(!backpack_user()->phone_verified_at)
            <div class="col-lg-8">
                <form id="phone_verification" class="form" method="post">
                    @csrf
                    <div class="card padding-10">
                        <div class="card-header">
                            Verify Phone Number
                        </div>

                        <div class="card-body backpack-profile-form bold-labels">
                            <div class="col-md-6 form-group">
                                <label class="required">Your Phone:</label>
                                <input required class="form-control" type="text" readonly name="phone_num"
                                       value="******{{ substr(backpack_user()->phone, 6) }}">
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="button" onclick="sendCode()" class="btn btn-success"><i
                                    class="fa fa-save"></i>Send me SMS-code!</button>

                            <a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
                        </div>
                    </div>

                </form>
            </div>
        @endif
        {{-- UPDATE INFO FORM --}}
        <div class="col-lg-8">
            <form class="form" action="{{ route('backpack.account.info') }}" method="post"
                  enctype="multipart/form-data">

                {!! csrf_field() !!}

                <div class="card padding-10">

                    <div class="card-header">
                        {{ trans('backpack::base.update_account_info') }}
                    </div>
                    <div class="form-group">
                        <div class="row py-4">
                            <div class="col-lg-12 mx-auto d-flex justify-content-center flex-wrap">
                                <!-- Uploaded image area-->
                                <div class="image-area mt-4 col-lg-12 avatar-IMG"><img id="imageResult"
                                                                                       src="{{ $user->getAvatarSrc() }}"
                                                                                       alt=""
                                                                                       class="img-fluid rounded shadow-sm mx-auto d-block">
                                </div>
                                <!-- Upload image input-->
                                <input id="upload" type="file" name="avatar" onchange="readURL(this);"
                                       class="form-control border-0" hidden>
                                <label for="upload" class="btn btn-light m-0 rounded-pill px-4"> <i
                                        class="fa fa-cloud-upload mr-2 text-muted"></i><small
                                        class="text-uppercase font-weight-bold text-muted">Choose Avatar</small></label>
                            </div>
                        </div>
                    </div>

                    <div class="card-body backpack-profile-form bold-labels">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                @php
                                    $label = trans('backpack::base.name');
                                    $field = 'name';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="text" name="{{ $field }}"
                                       value="{{ old($field) ? old($field) : $user->$field }}">
                            </div>

                            <div class="col-md-6 form-group">
                                @php
                                    $label = config('backpack.base.authentication_column_name');
                                    $field = backpack_authentication_column();
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control"
                                       type="{{ backpack_authentication_column()=='email'?'email':'text' }}"
                                       name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i
                                class="fa fa-save"></i> {{ trans('backpack::base.save') }}</button>
                        <a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
                    </div>
                </div>

            </form>
        </div>

        {{-- CHANGE PASSWORD FORM --}}
        <div class="col-lg-8">
            <form class="form" action="{{ route('backpack.account.password') }}" method="post">

                {!! csrf_field() !!}

                <div class="card padding-10">

                    <div class="card-header">
                        {{ trans('backpack::base.change_password') }}
                    </div>

                    <div class="card-body backpack-profile-form bold-labels">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                @php
                                    $label = trans('backpack::base.old_password');
                                    $field = 'old_password';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input autocomplete="new-password" required class="form-control" type="password"
                                       name="{{ $field }}" id="{{ $field }}" value="">
                            </div>

                            <div class="col-md-4 form-group">
                                @php
                                    $label = trans('backpack::base.new_password');
                                    $field = 'new_password';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input autocomplete="new-password" required class="form-control" type="password"
                                       name="{{ $field }}" id="{{ $field }}" value="">
                            </div>

                            <div class="col-md-4 form-group">
                                @php
                                    $label = trans('backpack::base.confirm_password');
                                    $field = 'confirm_password';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input autocomplete="new-password" required class="form-control" type="password"
                                       name="{{ $field }}" id="{{ $field }}" value="">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i
                                class="fa fa-save"></i> {{ trans('backpack::base.change_password') }}</button>
                        <a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
                    </div>

                </div>

            </form>
        </div>

    </div>
@endsection
@section('after_scripts')
    @if(!backpack_user()->phone_verified_at)
    <script>
        $(document).ready(function () {
            swal("Attention!", "You must verify your phone number", "warning");
        });
    </script>
    <script>
        let sendPost = (val) => {
            $.post({
                url: '{{ route('verify.otp') }}',
                data: {code: val},
            }).done((response) => {
                $('#phone_verification').remove();
                swal("Success!", response.message, "success");
            }).fail((errors) => {
                let res = errors.responseJSON;
                $('#verification_error').text(res.message+' Please, try again.');
            });
        };
        let sendCode = () => {
            $.get("{{ route('send.verification.code') }}").then((response) => {
                console.log('yes');
            });
            let inner = document.createElement('div');
            let inp = document.createElement("input");
            let err = document.createElement('p');
            err.setAttribute('id', 'verification_error');
            err.setAttribute('style', 'color: red');
            inp.setAttribute('placeholder', 'ex: 1234');
            inp.setAttribute('type', 'number');
            inp.setAttribute('class', 'swal-content__input');
            inner.appendChild(inp);
            inner.appendChild(err);
            swal({
                closeOnClickOutside: false,
                buttons: {
                    confirm: {
                        text: 'Verify',
                        id: 'verify',
                        closeModal: false,
                    },
                    cancel: true,
                },
                text: 'Please enter the code from SMS:',
                content: inner,
            }).then((val) => {
                if (val) {
                    sendPost(val, this);
                }
            }).then((value) => {

            });
        }
    </script>
    @endif
    <script src="{{ asset('js/imageUpload.js') }}"></script>
@stop
