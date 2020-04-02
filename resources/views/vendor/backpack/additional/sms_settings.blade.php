@extends(backpack_view('layouts.top_left'))
@section('head_style')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

@endsection

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">settings</span>
            <small>Edit setting.</small>

            <small><a href="http://station/cabinet/setting" class="hidden-print font-sm"><i class="fa fa-angle-double-left"></i> Back to all  <span>settings</span></a></small>
        </h2>
    </section>
@endsection

@section('content')
            <div class="row">
                <div class="col-md-8 bold-labels">
                    <!-- Default box -->


                    <form method="post" action="{{ route('msg.settings') }}">
                        @csrf
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        <div class="card">
                            <div class="card-body row">
                                <!-- load the view from type and view_namespace attribute if set -->
                                <!-- text input -->
                                <div class="form-group col-sm-12">
                                    <label>MSG91 Authentication Key</label>
                                    <input type="text" name="auth_key" value="{{ $msg->auth_key }}" class="form-control">
                                </div> <!-- text input -->
                                <div class="form-group col-sm-12">
                                    <label>MSG91 Sender ID</label>
                                    <input type="text" name="sender" value="{{ $msg->sender }}" class="form-control">
                                </div> <!-- text input -->
                                <div class="form-group col-sm-12">
                                    <label>MSG91 Route</label>
                                    <input type="text" name="route" value="{{ $msg->route }}" class="form-control">
                                </div> <!-- text input -->
                                <div class="form-group col-sm-12">
                                    <label>MSG91 Country Code</label>
                                    <input type="text" name="country_code" value="{{ $msg->country_code }}" class="form-control">
                                </div>
                            </div>
                        </div>





                        <div id="saveActions" class="form-group">

                            <input type="hidden" name="save_action" value="save_and_back">

                            <div class="btn-group" role="group">

                                <button type="submit" class="btn btn-success">
                                    <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                                    <span data-value="save_and_back">Save and back</span>
                                </button>

                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">▼</span></button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item" href="javascript:void(0);" data-value="save_and_edit">Save and edit this item</a>
                                    </div>
                                </div>

                            </div>
                            <a href="http://station/cabinet/setting" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">

                <!-- THE ACTUAL CONTENT -->
                    <div class="col-md-8 bold-labels">

                        <div class="overflow-hidden mt-2">

                            <table id="crudTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($settings as $setting)
                                    <tr>

                                        <td>{{ $setting->name }}</td>
                                        <td><input id="toggle-event" @if($setting->value) checked @endif class="toggle-element" data-id="{{$setting->id}}" type="checkbox" data-toggle="toggle">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>

                                </tr>
                                </tfoot>
                            </table>
                        </div><!-- /.box-body -->

                    </div><!-- /.box -->
                </div>

    <!-- Default box -->

@endsection

@section('after_styles')
    <!-- DATA TABLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/list.css') }}">

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection

@section('after_scripts')
    <script>
        $(document).ready(function () {
            $('.toggle-element').parent().on('click', (function () {
                let enabled = $(this).hasClass('off');
                let id = $(this.firstChild).data('id');
                $.post('/notify-settings', {enabled: enabled, id:id}).then((response) => {
                    console.log(response);
                });
            }));
        });
    </script>
    {{--    @include('crud::inc.datatables_logic')--}}

{{--    <script src="{{ asset('packages/backpack/crud/js/crud.js') }}"></script>--}}
{{--    <script src="{{ asset('packages/backpack/crud/js/form.js') }}"></script>--}}
{{--    <script src="{{ asset('packages/backpack/crud/js/list.js') }}"></script>--}}

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
{{--    @stack('crud_list_scripts')--}}
@endsection
