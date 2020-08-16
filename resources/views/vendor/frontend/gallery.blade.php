@extends(backpack_view('layouts.top_left'))
@section('head_style')
    {{--    <link rel="stylesheet" href="/packages/backpack/crud/css/crud.css">--}}
    {{--    <link rel="stylesheet" href="/packages/backpack/crud/css/form.css">--}}
    {{--    <link rel="stylesheet" href="/packages/backpack/crud/css/create.css">--}}
    {{--    <link rel="stylesheet" type="text/css" href="/packages/bootstrap-iconpicker/icon-fonts/font-awesome-4.7.0/css/font-awesome.min.css">--}}
    {{--    <!-- Bootstrap-Iconpicker -->--}}
    {{--    <link rel="stylesheet" href="/packages/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>--}}

    <!-- CRUD FORM CONTENT - crud_fields_styles stack -->
    <!-- no styles -->
    <style type="text/css">
        .repeatable-element {
            border: 1px solid rgba(0, 40, 100, .12);
            border-radius: 5px;
            background-color: #f0f3f94f;
        }

        .container-repeatable-elements .delete-element {
            z-index: 2;
            position: absolute !important;
            margin-left: -24px;
            margin-top: 0px;
            height: 30px;
            width: 30px;
            border-radius: 15px;
            text-align: center;
            background-color: #e8ebf0 !important;;
        }
    </style>

    <!-- include select2 css-->
    <link href="/packages/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- include select2 css-->
    <link href="/packages/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- include select2 css-->
    <link href="/packages/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- include select2 css-->
    <link href="/packages/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- include select2 css-->
    <link href="/packages/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- include select2 css-->
    <link href="/packages/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <style type="text/css">
        .select2-selection__clear::after {
            content: ' Clear';
        }
    </style>
    <!-- include select2 css-->
    <link href="/packages/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <style>
        .select_and_order_all,
        .select_and_order_selected {
            min-height: 120px;
            list-style-type: none;
            max-height: 220px;
            overflow: scroll;
            overflow-x: hidden;
            padding: 0px 5px 5px 5px;
            border: 1px solid #e6e6e6;
            width: 48%;
        }

        .select_and_order_all {
            border: none;
        }

        .select_and_order_all li,
        .select_and_order_selected li {
            border: 1px solid #eee;
            margin-top: 5px;
            padding: 5px;
            font-size: 1em;
            overflow: hidden;
            cursor: grab;
            border-style: dashed;
        }

        .select_and_order_all li {
            background: #fbfbfb;
            color: grey;
        }

        .select_and_order_selected li {
            border-style: solid;
        }

        .select_and_order_all li.ui-sortable-helper,
        .select_and_order_selected li.ui-sortable-helper {
            color: #3c8dbc;
            border-collapse: #3c8dbc;
            z-index: 9999;
        }

        .select_and_order_all .ui-sortable-placeholder,
        .select_and_order_selected .ui-sortable-placeholder {
            border: 1px dashed #3c8dbc;
            visibility: visible !important;
        }

        .ui-sortable-handle {
            -ms-touch-action: none;
            touch-action: none;
        }

    </style>
    <!-- include browse server css -->
    <link href="/packages/jquery-colorbox/example2/colorbox.css" rel="stylesheet" type="text/css"/>
    <style>
        #cboxContent, #cboxLoadedContent, .cboxIframe {
            background: transparent;
        }
    </style>

    <link href="/packages/jquery-colorbox/example2/colorbox.css" rel="stylesheet" type="text/css"/>
    <style>
        #cboxContent, #cboxLoadedContent, .cboxIframe {
            background: transparent;
        }
    </style>
    <link href="/packages/cropperjs/dist/cropper.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .container-repeatable-elements {
            width: 100% !important;
        }
        .hide {
            display: none;
        }

        .image .btn-group {
            margin-top: 10px;
        }

        img {
            max-width: 100%; /* This rule is very important, please do not ignore this! */
        }

        .img-container, .img-preview {
            width: 100%;
            text-align: center;
        }

        .img-preview {
            float: left;
            margin-right: 10px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .preview-lg {
            width: 263px;
            height: 148px;
        }

        .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
    </style>
    <link href="/packages/cropperjs/dist/cropper.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .hide {
            display: none;
        }

        .image .btn-group {
            margin-top: 10px;
        }

        img {
            max-width: 100%; /* This rule is very important, please do not ignore this! */
        }

        .img-container, .img-preview {
            width: 100%;
            text-align: center;
        }

        .img-preview {
            float: left;
            margin-right: 10px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .preview-lg {
            width: 263px;
            height: 148px;
        }

        .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
    </style>
    <style type="text/css">
        .editor-toolbar {
            border: 1px solid #ddd;
            border-bottom: none;
        }
    </style>
    <!-- include summernote css-->
    <link href="/packages/summernote/dist/summernote-bs4.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .note-editor.note-frame .note-status-output, .note-editor.note-airframe .note-status-output {
            height: auto;
        }
    </style>
    <link rel="stylesheet" href="/packages/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css"/>

    <style media="screen">
        .video-previewSuffix {
            border: 0;
            min-width: 68px;
        }

        .video-noPadding {
            padding: 0;
        }

        .video-preview {
            display: none;
        }

        .video-previewLink {
            color: #fff;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            text-align: center;
            float: left;
        }

        .video-previewLink.youtube {
            background: #DA2724;
        }

        .video-previewLink.vimeo {
            background: #00ADEF;
        }

        .video-previewIcon {
            transform: translateY(7px);
        }

        .video-previewImage {
            float: left;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            background-size: cover;
            background-position: center center;
        }
    </style>
    {{--    <link rel="stylesheet" type="text/css"--}}
    {{--          href="/packages/bootstrap-iconpicker/icon-fonts/font-awesome-5.12.0-1/css/all.min.css">--}}

    {{--    <link rel="stylesheet" type="text/css"--}}
    {{--          href="/packages/bootstrap-iconpicker/icon-fonts/font-awesome-5.12.0-1/css/all.min.css">--}}
    <!-- Bootstrap-Iconpicker -->
    {{--    <link rel="stylesheet" href="/packages/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>--}}

@endsection

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">Gallery Images</span>
            <small>Edit</small>
        </h2>
    </section>
@endsection




@section('after_styles')
    <!-- DATA TABLES -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/list.css') }}">

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection

@section('content')
    <div class="container">
        <form method="post"  action="{{ route('backpack.gallery.edit') }}">
            @csrf
            <div class="bg-white p-3 row pl-3 pr-3">
                <div class="container-repeatable-elements">
                    @if(!is_null($images))
                    @forelse($images as $image)
                        <div class="col-md-12 well repeatable-element row m-1 p-2">
                            <button onclick="remove_repeatable_parent(this)" type="button" class="close delete-element"><span aria-hidden="true">×</span></button>
                            <div class="form-group col-sm-12 cropperImage" data-aspectratio="1" data-crop="1"
                                 data-field-name="image" data-initialized="true">
                                <label>Gallery Image</label>
                                <div><img style="max-height: 300px" class="preview_img" src="{{ $image }}" alt=""></div>
                                <div class="btn-group">
                                    <div class="btn mt-2 btn-light btn-sm btn-file">
                                        <input type="hidden" class="preview_img" name="gallery_img[]" value="{{ $image }}">
                                        Choose file
                                        <input type="file" accept="image/*" data-preview="img-preview-1"
                                               data-handle="uploadImage" onchange="previewImg(this)"
                                               class="hide">
                                    </div>

                                </div>


                            </div>
                        </div>
                    @empty
                        <div class="col-md-12 well repeatable-element row m-1 p-2">
                            <button onclick="remove_repeatable_parent(this)" type="button" class="close delete-element"><span aria-hidden="true">×</span></button>
                            <div class="form-group col-sm-12 cropperImage" data-aspectratio="1" data-crop="1"
                                 data-field-name="image" data-initialized="true">
                                <label>Gallery Image</label>
                                <div><img style="max-height: 300px" class="preview_img" src="" alt=""></div>
                                <div class="btn-group">
                                    <div class="btn mt-2 btn-light btn-sm btn-file">
                                        <input type="hidden" class="preview_img" name="gallery_img[]" value="">
                                        Choose file
                                        <input type="file" accept="image/*" data-preview="img-preview-1"
                                               data-handle="uploadImage" onchange="previewImg(this)"
                                               class="hide">
                                    </div>

                                </div>


                            </div>
                        </div>
                    @endforelse
                    @endif
                </div>
                <button type="button" onclick="add_gallery_item(this)" class="btn btn-outline-primary btn-sm ml-1 add-repeatable-element-button">+ New Item</button>
            </div>

            <div class="btn-group" role="group">

                <button type="submit" class="btn mt-4 btn-success">
                    <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                    <span data-value="save_and_back">Save and back</span>
                </button>

            </div>
        </form>
    </div>
@endsection

@section('after_scripts')
    <script src="{{ asset('js/imageUpload.js') }}"></script>
    <script>
        let previewImg = function (inp) {
            inp = $(inp);
            let preview = inp.parent().parent().prev().children('.preview_img');
            console.log(preview);
            readURL(inp[0], preview[0], true);
        };
    </script>

    <script>
        let gallery_item = '<div class="col-md-12 well repeatable-element row m-1 p-2">\n' +
            '                            <button onclick="remove_repeatable_parent(this)" type="button" class="close delete-element"><span aria-hidden="true">×</span></button>\n' +
            '                            <div class="form-group col-sm-12 cropperImage" data-aspectratio="1" data-crop="1"\n' +
            '                                 data-field-name="image" data-initialized="true">\n' +
            '                                <label>Gallery Image</label>\n' +
            '                                <div><img style="max-height: 300px" class="preview_img" src="" alt=""></div>\n' +
            '                                <div class="btn-group">\n' +
            '                                    <div class="btn mt-2 btn-light btn-sm btn-file">\n' +
            '                                        <input type="hidden" class="preview_img" name="gallery_img[]" value="">\n' +
            '                                        Choose file\n' +
            '                                        <input type="file" accept="image/*" data-preview="img-preview-1"\n' +
            '                                               data-handle="uploadImage" onchange="previewImg(this)"\n' +
            '                                               class="hide">\n' +
            '                                    </div>\n' +
            '\n' +
            '                                </div>\n' +
            '\n' +
            '\n' +
            '                            </div>\n' +
            '                        </div>';

        let add_gallery_item = function (el) {
            console.log(el);
            cont = $(el).siblings('.container-repeatable-elements');
            cont.append(gallery_item);
        };

        let remove_repeatable_parent = function (el) {
            $(el).parent().remove();
        }
    </script>
@endsection
