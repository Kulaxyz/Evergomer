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
        </h2>
    </section>
@endsection

@section('content')
            <div class="row">
                {{-- UPDATE INFO FORM --}}
                <div class="col-md-8 bold-labels">
                    <!-- Default box -->


                    <form method="post" action="{{ route('payment.pdf.settings') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        <div class="card">
                            <div class="card-body row">


                                <div class="row py-4 container-fluid">
                                    <div class="col-lg-12 mx-auto d-flex justify-content-center flex-wrap">
                                        <!-- Uploaded image area-->
                                        <div class="image-area mt-4 col-lg-12">
                                            <img id="imageResult"
                                                 src="{{ \App\CustomSettings::get('payment_pdf_logo') ?? '/vendor/invoices/sample-logo.png' }}"
                                                 alt=""
                                                 style="width: 200px"
                                                 class="img-fluid rounded shadow-sm mx-auto d-block">
                                        </div>
                                        <!-- Upload image input-->
                                        <input id="upload" type="file" name="payment_pdf_logo" onchange="readURL(this);"
                                               class="form-control border-0" hidden>
                                        <label for="upload" class="btn btn-light m-0 rounded-pill px-4"> <i
                                                class="fa fa-cloud-upload mr-2 text-muted"></i><small
                                                class="text-uppercase font-weight-bold text-muted">Choose Logo</small></label>
                                    </div>
                                </div>

                                <!-- load the view from type and view_namespace attribute if set -->
                                <!-- text input -->
                                <div class="form-group col-sm-12">
                                    <label>Invoice Prefix</label>
                                    <input type="text" name="prefix" value="{{ \App\CustomSettings::get('payment_pdf_prefix') ?? 'INR' }}" class="form-control">
                                </div> <!-- text input -->
                                <div class="form-group col-sm-12">
                                    <label>GST Tax Rate</label>
                                    <input type="text" name="tax" value="{{ \App\CustomSettings::get('payment_pdf_tax') ?? '15' }}" class="form-control">
                                </div> <!-- text input -->
                                <div class="form-group col-sm-12">
                                    <label style="width: 100%" for="toggle-event">Promo (Discount)<br></label>
                                    <input name="promo" id="toggle-event" @if(\App\CustomSettings::get('payment_pdf_promo')) checked @endif class="toggle-element" type="checkbox" data-toggle="toggle">
                                </div> <!-- text input -->

                            </div>
                        </div>





                        <div id="saveActions" class="form-group">

                            <input type="hidden" name="save_action" value="save_and_back">

                            <div class="btn-group" role="group">

                                <button type="submit" class="btn btn-success">
                                    <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                                    <span data-value="save_and_back">Save and back</span>
                                </button>

{{--                                <div class="btn-group" role="group">--}}
{{--                                    <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>--}}
{{--                                </div>--}}

                            </div>
                            <a class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
                        </div>
                    </form>
                </div>
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
    <script src="{{ asset('js/imageUpload.js') }}"></script>
@endsection
