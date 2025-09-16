{{-- * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard --}}
@extends('layouts.master')

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <div class="main-container">
        <div class="container">
            <div class="row">
                <div class="col-md-3 page-sidebar">
                    @includeFirst([
                        config('larapen.core.customizedViewPath') . 'account.inc.sidebar',
                        'account.inc.sidebar',
                    ])
                </div>
                <!--/.page-sidebar-->

                <div class="col-md-9 page-content">

                    @include('flash::message')

                    @if (isset($errors) and $errors->any())
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>
                            <ul class="list list-check">
                                @foreach ($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="avatarUploadError" class="center-block" style="width:100%; display:none"></div>
                    <div id="avatarUploadSuccess" class="alert alert-success fade show" style="display:none;"></div>


                    <div id="accordion" class="panel-group">
                        <!-- USER -->
                        <div class="card card-default">
                            <div class="card-header mb-2    ">
                                <h4 class="card-title">
                                    <a href="#userPanel" aria-expanded="true" data-toggle="collapse"
                                       data-parent="#accordion">{{ t('company_page') }}</a>
                                </h4>
                            </div>
                            @if (Session::has('success'))
                                <div class="alert alert-success text-center">
                                    {{ Session::get('success') }}
                                </div>
                            @endif
                            <div class="panel-collapse collapse {{ (old('panel') == '' or old('panel') == 'userPanel') ? 'show' : '' }}"
                                 id="userPanel">
                                <div class="card-body">
                                    <form id="companyForm" name="details" class="form-horizontal" role="form" method="POST"
                                          action="{{ url('account/company-profile/create') }}" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <input name="_method" type="hidden" value="POST">
                                        {{-- <input name="panel" type="hidden" value="userPanel"> --}}
                                        <div class="row p-4">
                                            <div class="col-md-12">
                                                {{ t('company_page_test') }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="logo"
                                                   class="col-md-3 col-form-label">{{ t('company_logo') }}
                                                <sup class="sup">*</sup></label>
                                            <div class="col-9 text-center">
                                                <div class="photo-field mb-2 ">
                                                    <div class="file-loading">
                                                        <input id="logo" name="logo" type="file"
                                                               class="file @error('logo') is-invalid @enderror">
                                                        @error('logo')
                                                        <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- name -->
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('company_name') }} <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <input name="name" type="text" id="name"
                                                       value="{{ old('name', Auth::user()->company ? Auth::user()->company->name : '') }}"
                                                       class="form-control @error('name') is-invalid @enderror">
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('company_slug') }}</label>
                                            <div class="col-md-9">
                                                <input name="company_slug" type="text" id="company_slug"
                                                       value="{{ old('company_slug', Auth::user()->company ? Auth::user()->company->company_slug : '') }}"
                                                       class="form-control" placeholder="Auto generated" readonly>
                                            </div>
                                        </div>

                                        <!-- About Business -->
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label" for="description">{{ t('about_business') }} <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                                          rows="5">{{ old('description', Auth::user()->company ? Auth::user()->company->description : '') }}</textarea>
                                                <small id="description" class="form-text text-muted">
                                                    {{ t('Describe what makes your ad unique...') }}
                                                    <span class="float-right input-hint ">{{ t('max_word_description') }}</span>
                                                </small>
                                                @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- social media -->
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('company_email') }} <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                                       value="{{ old('email', Auth::user()->company ? Auth::user()->company->email : '') }}"
                                                       placeholder="example@gmail.com">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('facebook') }}</label>
                                            <div class="col-md-9">
                                                <input name="facebook" type="text" class="form-control"
                                                       value="{{ old('facebook', Auth::user()->company ? Auth::user()->company->facebook : '') }}"
                                                       placeholder="www.facebook.com">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('twitter') }}</label>
                                            <div class="col-md-9">
                                                <input name="twitter" type="text" class="form-control"
                                                       value="{{ old('twitter', Auth::user()->company ? Auth::user()->company->twitter : '') }}"
                                                       placeholder="www.twitter.com">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('instagram') }}</label>
                                            <div class="col-md-9">
                                                <input name="instagram" type="text" class="form-control"
                                                       value="{{ old('instagram', Auth::user()->company ? Auth::user()->company->instagram : '') }}"
                                                       placeholder="www.instagram.com">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('linkedIn') }}</label>
                                            <div class="col-md-9">
                                                <input name="linkedin" type="text" class="form-control"
                                                       value="{{ old('linkedin', Auth::user()->company ? Auth::user()->company->linkedin : '') }}"
                                                       placeholder="www.linkedin.com">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('kvk') }}</label>
                                            <div class="col-md-9">
                                                <input name="kvk" type="text" class="form-control"
                                                       value="{{ old('kvk', Auth::user()->company ? Auth::user()->company->kvk : '') }}"
                                                       placeholder="www.kvk.com">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('wechat') }}</label>
                                            <div class="col-md-9">
                                                <input name="wechat" type="text" class="form-control"
                                                       value="{{ old('wechat', Auth::user()->company ? Auth::user()->company->wechat : '') }}"
                                                       placeholder="www.wechat.com">
                                            </div>
                                        </div>

                                        <!-- phone -->
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('phone') }} <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <input name="phone" type="text" id="phone"
                                                       value="{{ old('phone', Auth::user()->company ? Auth::user()->company->phone : '') }}"
                                                       class="form-control @error('phone') is-invalid @enderror"
                                                       placeholder="Type a phone number">
                                                @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Website -->
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('website') }}</label>
                                            <div class="col-md-9">
                                                <input name="website" type="text" class="form-control"
                                                       value="{{ old('website', Auth::user()->company ? Auth::user()->company->website : '') }}"
                                                       placeholder="@example: www.yourwebsite.com">
                                            </div>
                                        </div>
                                        <!-- Business Category -->
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('business_category') }} <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <select id="category_id" class="form-select form-control"
                                                        name="category_id" aria-label="Default select example">
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                                {{ old('category_id', Auth::user()->company->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                        @foreach ($category->children as $childCategory)
                                                            <option value="{{ $childCategory->id }}"
                                                                    {{ old('category_id', Auth::user()->company->category_id ?? '') == $childCategory->id ? 'selected' : '' }}>
                                                                -- {{ $childCategory->name }}
                                                            </option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Company size - Revenue -->
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ t('company_size') }} <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <select id="revenue" class="form-select form-control" name="revenue"
                                                        aria-label="select example">
                                                    <option value="1" {{ old('revenue', Auth::user()->company->revenue ?? '') == 1 ? 'selected' : '' }}>0 - 10,000 USD</option>
                                                    <option value="2" {{ old('revenue', Auth::user()->company->revenue ?? '') == 2 ? 'selected' : '' }}>10,000 - 100,000 USD</option>
                                                    <option value="3" {{ old('revenue', Auth::user()->company->revenue ?? '') == 3 ? 'selected' : '' }}>100,000 - 500,000 USD</option>
                                                    <option value="4" {{ old('revenue', Auth::user()->company->revenue ?? '') == 4 ? 'selected' : '' }}>500,000 - 1,000,000 USD</option>
                                                    <option value="5" {{ old('revenue', Auth::user()->company->revenue ?? '') == 5 ? 'selected' : '' }}>1,000,000 USD - 10,000,000 USD</option>
                                                    <option value="6" {{ old('revenue', Auth::user()->company->revenue ?? '') == 6 ? 'selected' : '' }}>greater than 10,000,000 USD</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Company Registration number  -->
                                        <div class="form-group row">
                                            <label for="registration_number" class="col-md-3 col-form-label">{{ t('registration_number') }} <sup class="sup">*</sup></label>
                                            <div class="col-md-9">

                                                <input id="registration_number" name="registration_number" type="text" class="form-control @error('registration_number') is-invalid @enderror"
                                                       value="{{ old('registration_number', Auth::user()->company ? Auth::user()->company->registration_number : '') }}">
                                                @error('registration_number')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="registration_number"
                                                   class="col-md-3 col-form-label">{{ t('default_business_type') }}
                                                <sup class="sup">*</sup>
                                            </label>
                                            <div class="col-md-9">
                                                @foreach ($post as $postType)
                                                    <div class="form-check form-check-inline pt-2">
                                                        <input name="default_business_type"
                                                               id="postTypeId-{{ $postType->id }}"
                                                               value="{{ $postType->id }}"
                                                               {{ $company && $company->default_business_type == $postType->id ? 'checked="checked"' : '' }}
                                                               type="radio" class="form-check-input">
                                                        <label class="form-check-label"
                                                               for="postTypeId-{{ $postType->id }}">
                                                            {{ $postType->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-md-3 col-md-9"></div>
                                        </div>

                                        <!-- Button -->
                                        <div class="form-group row">
                                            <div class="offset-md-3 col-md-9">
                                                <button type="submit" id="submitCompanyForm" class="btn btn-primary">{{ t('submit') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--/.row-box End-->
                    </div>
                </div>
                <!--/.page-content-->
            </div>
            <!--/.row-->
        </div>
        <!--/.container-->
    </div>
    <!-- /.main-container -->

    <style>
        .popupk {
            display: none;
            position: fixed;
            padding: 10px;
            width: 280px;
            left: 70%;
            margin-left: -150px;
            height: 190px;
            top: 50%;
            margin-top: -100px;
            background: #FFF;
            border: 3px solid #F04A49;
            z-index: 20;
        }
    </style>
    <div class="popupk" id="popupk">
        <p>{{ trans('company.blacklist_popup_title') }}</p>
        <p>{{ trans('company.blacklist_popup_all') }}</p>
        <p>{{ trans('company.blacklist_popup_all_red') }}</p>
        <a href="#" class="btn btn-success" onclick="removeWords()">{{ trans('company.blacklist_popup_ok_button') }}</a>
        <a href="#" class="btn btn-default" style="width: 45% !important;" onclick="hide()">{{ trans('company.blacklist_popup_close_button') }}</a>
    </div>
@endsection

@section('after_styles')
    <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    @if (config('lang.direction') == 'rtl')
        <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.css"
              integrity="sha512-7uSoC3grlnRktCWoO4LjHMjotq8gf9XDFQerPuaph+cqR7JC9XKGdvN+UwZMC14aAaBDItdRj3DcSDs4kMWUgg=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endif
    <style>
        .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
            box-shadow: 0 0 5px 0 #666666;
        }

        .file-loading:before {
            content: " {{ t('Loading') }}...";
        }

        .sup {
            color: red;
        }
    </style>
    <style>
        .error {
            color: red;
            font-weight: 400;
            display: block;
            padding: 6px 0;
            font-size: 14px;
        }

        /* Avatar Upload */
        .photo-field {
            display: inline-block;
            vertical-align: middle;
        }

        .photo-field .krajee-default.file-preview-frame,
        .photo-field .krajee-default.file-preview-frame:hover {
            margin: 0;
            padding: 0;
            border: none;
            box-shadow: none;
            text-align: center;
        }

        .file-input {
            display: table-cell;
            width: 550px;
        }

        .photo-field .krajee-default.file-preview-frame .kv-file-content {
            width: auto;
            height: auto;
        }

        .kv-reqd {
            color: red;
            font-family: monospace;
            font-weight: normal;
        }

        .file-preview {
            padding: 2px;
        }

        .file-drop-zone {
            margin: 2px;
        }

        .file-drop-zone .file-preview-thumbnails {
            cursor: pointer;
        }

        .krajee-default.file-preview-frame .file-thumbnail-footer {
            height: 30px;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/fileinput/locales/' . config('app.locale') . '.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $('#logo').fileinput({
            showPreview: true,
            showCaption: true,
            initialPreview: [
                @if (isset($company->logo) and !empty($company->logo))
                    '{{ asset('storage/' . $company->logo) }}'
                @endif
            ],
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
        });

        var blacklistWords = <?php echo json_encode($blacklistWords); ?>;
        var timer;
        var formValid = true;
        tinymce.init({
            selector: '#description',
            language: 'en',
            directionality: 'ltr',
            height: 250,
            menubar: false,
            statusbar: false,
            placeholder: "Type descirption of job...",
            plugins: 'lists link table',
            toolbar: 'undo redo | bold italic underline | forecolor backcolor | bullist numlist blockquote table | link unlink | alignleft aligncenter alignright | outdent indent | fontsizeselect',
            setup: function(editor) {
                editor.on('keyup', function() {
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        textFunction();
                    }, 1500);
                });
                function textFunction() {
                    var content = editor.getContent();
                    formValid = true;
                    blacklistWords.forEach(function(item) {
                        if (content.indexOf(item) >= 0) {
                            content = content.split(item).join('<span style="background-color: #e03e2d;">' + item + '</span>');
                            formValid = false;
                        }
                    });
                    editor.setContent(content);
                }
            }
        });
        $(document).ready(function ()
        {
            console.log(formValid);
            $('#submitCompanyForm').on('click', function (e) {
                e.preventDefault();

                if (formValid) {
                    $('#companyForm').submit();
                }
                else{
                    $('html, body').scrollTop($("#name").offset().top);
                    $('.popupk').show();
                }

                console.log(formValid);
                return false;
            });
        });
        function hide()
        {
            $('.popupk').hide();

            $('html, body').scrollTop($("#name").offset().top);
        }

        function removeWords()
        {
            var tinyMCEText =  tinyMCE.activeEditor.getContent();

            tinyMCEText = tinyMCEText.replace(/<span style="background-color: #e03e2d;">(.*?)<\/span>/g, '');

            tinyMCE.activeEditor.setContent(tinyMCEText);

            formValid = true;

            $('.popupk').hide();

            setTimeout(function(){
                $('html, body').scrollTop($("#name").offset().top);
            }, 500);

        }
        tinymce.addI18n('en', {
            "Redo": "Redo",
            "Undo": "Undo",
            "Cut": "Cut",
            "Copy": "Copy",
            "Paste": "Paste",
            "Select all": "Select all",
            "New document": "New document",
            "Ok": "Ok",
            "Cancel": "Cancel",
            "Visual aids": "Visual aids",
            "Bold": "Bold",
            "Italic": "Italic",
            "Underline": "Underline",
            "Strikethrough": "Strikethrough",
            "Superscript": "Superscript",
            "Subscript": "Subscript",
            "Clear formatting": "Clear formatting",
            "Align left": "Align left",
            "Align center": "Align center",
            "Align right": "Align right",
            "Justify": "Justify",
            "Bullet list": "Bullet list",
            "Numbered list": "Numbered list",
            "Decrease indent": "Decrease indent",
            "Increase indent": "Increase indent",
            "Close": "Close",
            "Formats": "Formats",
            "Your browser doesn't support direct access to the clipboard. Please use the Ctrl+X\\\/C\\\/V keyboard shortcuts instead.": "Your browser doesn't support direct access to the clipboard. Please use the Ctrl+X\\\/C\\\/V keyboard shortcuts instead.",
            "Headers": "Headers",
            "Header 1": "Header 1",
            "Header 2": "Header 2",
            "Header 3": "Header 3",
            "Header 4": "Header 4",
            "Header 5": "Header 5",
            "Header 6": "Header 6",
            "Headings": "Headings",
            "Heading 1": "Heading 1",
            "Heading 2": "Heading 2",
            "Heading 3": "Heading 3",
            "Heading 4": "Heading 4",
            "Heading 5": "Heading 5",
            "Heading 6": "Heading 6",
            "Preformatted": "Preformatted",
            "Div": "Div",
            "Pre": "Pre",
            "Code": "Code",
            "Paragraph": "Paragraph",
            "Blockquote": "Blockquote",
            "Inline": "Inline",
            "Blocks": "Blocks",
            "Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.": "Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.",
            "Fonts": "Fonts",
            "Font Sizes": "Font Sizes",
            "Class": "Class",
            "Browse for an image": "Browse for an image",
            "OR": "OR",
            "Drop an image here": "Drop an image here",
            "Upload": "Upload",
            "Block": "Block",
            "Align": "Align",
            "Default": "Default",
            "Circle": "Circle",
            "Disc": "Disc",
            "Square": "Square",
            "Lower Alpha": "Lower Alpha",
            "Lower Greek": "Lower Greek",
            "Lower Roman": "Lower Roman",
            "Upper Alpha": "Upper Alpha",
            "Upper Roman": "Upper Roman",
            "Anchor...": "Anchor...",
            "Name": "Name",
            "Id": "Id",
            "Id should start with a letter, followed only by letters, numbers, dashes, dots, colons or underscores.": "Id should start with a letter, followed only by letters, numbers, dashes, dots, colons or underscores.",
            "You have unsaved changes are you sure you want to navigate away?": "You have unsaved changes are you sure you want to navigate away?",
            "Restore last draft": "Restore last draft",
            "Special character...": "Special character...",
            "Source code": "Source code",
            "Insert\\\/Edit code sample": "Insert\\\/Edit code sample",
            "Language": "Language",
            "Code sample...": "Code sample...",
            "Color Picker": "Color Picker",
            "R": "R",
            "G": "G",
            "B": "B",
            "Left to right": "Left to right",
            "Right to left": "Right to left",
            "Emoticons...": "Emoticons...",
            "Metadata and Document Properties": "Metadata and Document Properties",
            "Title": "Title",
            "Keywords": "Keywords",
            "Description": "Description",
            "Robots": "Robots",
            "Author": "Author",
            "Encoding": "Encoding",
            "Fullscreen": "Fullscreen",
            "Action": "Action",
            "Shortcut": "Shortcut",
            "Help": "Help",
            "Address": "Address",
            "Focus to menubar": "Focus to menubar",
            "Focus to toolbar": "Focus to toolbar",
            "Focus to element path": "Focus to element path",
            "Focus to contextual toolbar": "Focus to contextual toolbar",
            "Insert link (if link plugin activated)": "Insert link (if link plugin activated)",
            "Save (if save plugin activated)": "Save (if save plugin activated)",
            "Find (if searchreplace plugin activated)": "Find (if searchreplace plugin activated)",
            "Plugins installed ({0}):": "Plugins installed ({0}):",
            "Premium plugins:": "Premium plugins:",
            "Learn more...": "Learn more...",
            "You are using {0}": "You are using {0}",
            "Plugins": "Plugins",
            "Handy Shortcuts": "Handy Shortcuts",
            "Horizontal line": "Horizontal line",
            "Insert\\\/edit image": "Insert\\\/edit image",
            "Image description": "Image description",
            "Source": "Source",
            "Dimensions": "Dimensions",
            "Constrain proportions": "Constrain proportions",
            "General": "General",
            "Advanced": "Advanced",
            "Style": "Style",
            "Vertical space": "Vertical space",
            "Horizontal space": "Horizontal space",
            "Border": "Border",
            "Insert image": "Insert image",
            "Image...": "Image...",
            "Image list": "Image list",
            "Rotate counterclockwise": "Rotate counterclockwise",
            "Rotate clockwise": "Rotate clockwise",
            "Flip vertically": "Flip vertically",
            "Flip horizontally": "Flip horizontally",
            "Edit image": "Edit image",
            "Image options": "Image options",
            "Zoom in": "Zoom in",
            "Zoom out": "Zoom out",
            "Crop": "Crop",
            "Resize": "Resize",
            "Orientation": "Orientation",
            "Brightness": "Brightness",
            "Sharpen": "Sharpen",
            "Contrast": "Contrast",
            "Color levels": "Color levels",
            "Gamma": "Gamma",
            "Invert": "Invert",
            "Apply": "Apply",
            "Back": "Back",
            "Insert date\\\/time": "Insert date\\\/time",
            "Date\\\/time": "Date\\\/time",
            "Insert\\\/Edit Link": "Insert\\\/Edit Link",
            "Insert\\\/edit link": "Insert\\\/edit link",
            "Text to display": "Text to display",
            "Url": "Url",
            "Open link in...": "Open link in...",
            "Current window": "Current window",
            "None": "None",
            "New window": "New window",
            "Remove link": "Remove link",
            "Anchors": "Anchors",
            "Link...": "Link...",
            "Paste or type a link": "Paste or type a link",
            "The URL you entered seems to be an email address. Do you want to add the  mailto: prefix?": "The URL you entered seems to be an email address. Do you want to add the  mailto: prefix?",
            "The URL you entered seems to be an external link. Do you want to add the  http:\\\/\\\/ prefix?": "The URL you entered seems to be an external link. Do you want to add the  http:\\\/\\\/ prefix?",
            "Link list": "Link list",
            "Insert video": "Insert video",
            "Insert\\\/edit video": "Insert\\\/edit video",
            "Insert\\\/edit media": "Insert\\\/edit media",
            "Alternative source": "Alternative source",
            "Alternative source URL": "Alternative source URL",
            "Media poster (Image URL)": "Media poster (Image URL)",
            "Paste your embed code below:": "Paste your embed code below:",
            "Embed": "Embed",
            "Media...": "Media...",
            "Nonbreaking space": "Nonbreaking space",
            "Page break": "Page break",
            "Paste as text": "Paste as text",
            "Preview": "Preview",
            "Print...": "Print...",
            "Save": "Save",
            "Find": "Find",
            "Replace with": "Replace with",
            "Replace": "Replace",
            "Replace all": "Replace all",
            "Previous": "Previous",
            "Next": "Next",
            "Find and replace...": "Find and replace...",
            "Could not find the specified string.": "Could not find the specified string.",
            "Match case": "Match case",
            "Find whole words only": "Find whole words only",
            "Spell check": "Spell check",
            "Ignore": "Ignore",
            "Ignore all": "Ignore all",
            "Finish": "Finish",
            "Add to Dictionary": "Add to Dictionary",
            "Insert table": "Insert table",
            "Table properties": "Table properties",
            "Delete table": "Delete table",
            "Cell": "Cell",
            "Row": "Row",
            "Column": "Column",
            "Cell properties": "Cell properties",
            "Merge cells": "Merge cells",
            "Split cell": "Split cell",
            "Insert row before": "Insert row before",
            "Insert row after": "Insert row after",
            "Delete row": "Delete row",
            "Row properties": "Row properties",
            "Cut row": "Cut row",
            "Copy row": "Copy row",
            "Paste row before": "Paste row before",
            "Paste row after": "Paste row after",
            "Insert column before": "Insert column before",
            "Insert column after": "Insert column after",
            "Delete column": "Delete column",
            "Cols": "Cols",
            "Rows": "Rows",
            "Width": "Width",
            "Height": "Height",
            "Cell spacing": "Cell spacing",
            "Cell padding": "Cell padding",
            "Show caption": "Show caption",
            "Left": "Left",
            "Center": "Center",
            "Right": "Right",
            "Cell type": "Cell type",
            "Scope": "Scope",
            "Alignment": "Alignment",
            "H Align": "H Align",
            "V Align": "V Align",
            "Top": "Top",
            "Middle": "Middle",
            "Bottom": "Bottom",
            "Header cell": "Header cell",
            "Row group": "Row group",
            "Column group": "Column group",
            "Row type": "Row type",
            "Header": "Header",
            "Body": "Body",
            "Footer": "Footer",
            "Border color": "Border color",
            "Insert template...": "Insert template...",
            "Templates": "Templates",
            "Template": "Template",
            "Text color": "Text color",
            "Background color": "Background color",
            "Custom...": "Custom...",
            "Custom color": "Custom color",
            "No color": "No color",
            "Remove color": "Remove color",
            "Table of Contents": "Table of Contents",
            "Show blocks": "Show blocks",
            "Show invisible characters": "Show invisible characters",
            "Word count": "Word count",
            "Count": "Count",
            "Document": "Document",
            "Selection": "Selection",
            "Words": "Words",
            "Words: {0}": "Words: {0}",
            "{0} words": "{0} words",
            "File": "File",
            "Edit": "Edit",
            "Insert": "Insert",
            "View": "View",
            "Format": "Format",
            "Table": "Table",
            "Tools": "Tools",
            "Powered by {0}": "Powered by {0}",
            "Rich Text Area. Press ALT-F9 for menu. Press ALT-F10 for toolbar. Press ALT-0 for help": "Rich Text Area. Press ALT-F9 for menu. Press ALT-F10 for toolbar. Press ALT-0 for help",
            "Image title": "Image title",
            "Border width": "Border width",
            "Border style": "Border style",
            "Error": "Error",
            "Warn": "Warn",
            "Valid": "Valid",
            "To open the popup, press Shift+Enter": "To open the popup, press Shift+Enter",
            "Rich Text Area. Press ALT-0 for help.": "Rich Text Area. Press ALT-0 for help.",
            "System Font": "System Font",
            "Failed to upload image: {0}": "Failed to upload image: {0}",
            "Failed to load plugin: {0} from url {1}": "Failed to load plugin: {0} from url {1}",
            "Failed to load plugin url: {0}": "Failed to load plugin url: {0}",
            "Failed to initialize plugin: {0}": "Failed to initialize plugin: {0}",
            "example": "example",
            "Search": "Search",
            "All": "All",
            "Currency": "Currency",
            "Text": "Text",
            "Quotations": "Quotations",
            "Mathematical": "Mathematical",
            "Extended Latin": "Extended Latin",
            "Symbols": "Symbols",
            "Arrows": "Arrows",
            "User Defined": "User Defined",
            "dollar sign": "dollar sign",
            "currency sign": "currency sign",
            "euro-currency sign": "euro-currency sign",
            "colon sign": "colon sign",
            "cruzeiro sign": "cruzeiro sign",
            "french franc sign": "french franc sign",
            "lira sign": "lira sign",
            "mill sign": "mill sign",
            "naira sign": "naira sign",
            "peseta sign": "peseta sign",
            "rupee sign": "rupee sign",
            "won sign": "won sign",
            "new sheqel sign": "new sheqel sign",
            "dong sign": "dong sign",
            "kip sign": "kip sign",
            "tugrik sign": "tugrik sign",
            "drachma sign": "drachma sign",
            "german penny symbol": "german penny symbol",
            "peso sign": "peso sign",
            "guarani sign": "guarani sign",
            "austral sign": "austral sign",
            "hryvnia sign": "hryvnia sign",
            "cedi sign": "cedi sign",
            "livre tournois sign": "livre tournois sign",
            "spesmilo sign": "spesmilo sign",
            "tenge sign": "tenge sign",
            "indian rupee sign": "indian rupee sign",
            "turkish lira sign": "turkish lira sign",
            "nordic mark sign": "nordic mark sign",
            "manat sign": "manat sign",
            "ruble sign": "ruble sign",
            "yen character": "yen character",
            "yuan character": "yuan character",
            "yuan character, in hong kong and taiwan": "yuan character, in hong kong and taiwan",
            "yen\\\/yuan character variant one": "yen\\\/yuan character variant one",
            "Loading emoticons...": "Loading emoticons...",
            "Could not load emoticons": "Could not load emoticons",
            "People": "People",
            "Animals and Nature": "Animals and Nature",
            "Food and Drink": "Food and Drink",
            "Activity": "Activity",
            "Travel and Places": "Travel and Places",
            "Objects": "Objects",
            "Flags": "Flags",
            "Characters": "Characters",
            "Characters (no spaces)": "Characters (no spaces)",
            "{0} characters": "{0} characters",
            "Error: Form submit field collision.": "Error: Form submit field collision.",
            "Error: No form element found.": "Error: No form element found.",
            "Update": "Update",
            "Color swatch": "Color swatch",
            "Turquoise": "Turquoise",
            "Green": "Green",
            "Blue": "Blue",
            "Purple": "Purple",
            "Navy Blue": "Navy Blue",
            "Dark Turquoise": "Dark Turquoise",
            "Dark Green": "Dark Green",
            "Medium Blue": "Medium Blue",
            "Medium Purple": "Medium Purple",
            "Midnight Blue": "Midnight Blue",
            "Yellow": "Yellow",
            "Orange": "Orange",
            "Red": "Red",
            "Light Gray": "Light Gray",
            "Gray": "Gray",
            "Dark Yellow": "Dark Yellow",
            "Dark Orange": "Dark Orange",
            "Dark Red": "Dark Red",
            "Medium Gray": "Medium Gray",
            "Dark Gray": "Dark Gray",
            "Light Green": "Light Green",
            "Light Yellow": "Light Yellow",
            "Light Red": "Light Red",
            "Light Purple": "Light Purple",
            "Light Blue": "Light Blue",
            "Dark Purple": "Dark Purple",
            "Dark Blue": "Dark Blue",
            "Black": "Black",
            "White": "White",
            "Switch to or from fullscreen mode": "Switch to or from fullscreen mode",
            "Open help dialog": "Open help dialog",
            "history": "history",
            "styles": "styles",
            "formatting": "formatting",
            "alignment": "alignment",
            "indentation": "indentation",
            "permanent pen": "permanent pen",
            "comments": "comments",
            "Format Painter": "Format Painter",
            "Insert\\\/edit iframe": "Insert\\\/edit iframe",
            "Capitalization": "Capitalization",
            "lowercase": "lowercase",
            "UPPERCASE": "UPPERCASE",
            "Title Case": "Title Case",
            "Permanent Pen Properties": "Permanent Pen Properties",
            "Permanent pen properties...": "Permanent pen properties...",
            "Font": "Font",
            "Size": "Size",
            "More...": "More...",
            "Spellcheck Language": "Spellcheck Language",
            "Select...": "Select...",
            "Preferences": "Preferences",
            "Yes": "Yes",
            "No": "No",
            "Keyboard Navigation": "Keyboard Navigation",
            "Version": "Version",
            "Anchor": "Anchor",
            "Special character": "Special character",
            "Code sample": "Code sample",
            "Color": "Color",
            "Emoticons": "Emoticons",
            "Document properties": "Document properties",
            "Image": "Image",
            "Insert link": "Insert link",
            "Target": "Target",
            "Link": "Link",
            "Poster": "Poster",
            "Media": "Media",
            "Print": "Print",
            "Prev": "Prev",
            "Find and replace": "Find and replace",
            "Whole words": "Whole words",
            "Spellcheck": "Spellcheck",
            "Caption": "Caption",
            "Insert template": "Insert template"
        });
    </script>
@endsection
