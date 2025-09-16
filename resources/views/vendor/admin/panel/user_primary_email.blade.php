@extends('admin::layouts.master')

@section('header')
    <div class="row page-titles">
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="{{ admin_url() }}">{{ trans('admin.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ admin_url('users') }}"
                        class="text-capitalize">{{ trans('admin.users') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('company-admin.edit_user_primary_email') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <div class="main-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12 page-content">

                    @include('flash::message')

                    @if (isset($errors) and $errors->any())
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>
                            <ul class="list list-check">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="avatarUploadError" class="center-block" style="width:100%; display:none"></div>
                    <div id="avatarUploadSuccess" class="alert alert-success fade show" style="display:none;"></div>

                    <div id="accordion" class="panel-group">
                        <!-- USER -->
                        <div class="card card-default">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <a href="#emailPanel" aria-expanded="true" data-toggle="collapse"
                                        data-parent="#accordion">{{ trans('company-admin.user_primary_email') }}</a>
                                </h4>
                            </div>
                            @if (Session::has('success'))
                                <div class="alert alert-success text-center m-3">
                                    {{ Session::get('success') }}
                                </div>
                            @endif
                            <div class="panel-collapse {{ (old('panel') == '' or old('panel') == 'emailPanel') ? 'show' : '' }}"
                                id="emailPanel">
                                <div class="card-body">
                                    <form name="details" class="form-horizontal" role="form" method="post"
                                        action="{{ url('admin/update-primary-email', ['id' => $user->id]) }}"
                                        enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <input name="_method" type="hidden" value="PUT">

                                        <div class="form-group row">
                                            <div class="col-9">
                                                @if ($user->company)
                                                    <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                                    <div class="form-group row required primaryEmail">
                                                        <label class="col-md-3 col-form-label">
                                                            {{ trans('company-admin.select_primary_email') }}</label>
                                                        <div class="col-md-9">

                                                            <div class="form-check form-check-inline pt-2">

                                                                <input name="primary_email" id="primary_email1"
                                                                    value="{{ $user->email }}"
                                                                    class="form-check-input{{ $emailError }}"
                                                                    type="radio"
                                                                    {{ $user->primary_email == $user->email ? 'checked="checked"' : '' }}>
                                                                <label class="label form-check-label" for="primary_email1">
                                                                    {{ $user->email }}
                                                                </label> &nbsp;&nbsp;&nbsp;

                                                                <input name="primary_email" id="primary_email2"
                                                                    value="{{ $user->company ? $user->company->email : '' }}"
                                                                    class="form-check-input{{ $emailError }}"
                                                                    type="radio"
                                                                    {{ $user->primary_email == $user->company->email ? 'checked="checked"' : '' }}>
                                                                <label class="label form-check-label" for="primary_email2">
                                                                    {{ $user->company ? $user->company->email : '' }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row ">
                                                        <div class="offset-md-3 col-md-9">
                                                            <button type="submit"
                                                                class="btn btn-primary mt-3">{{ trans('company.submit') }}</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-md-3 col-md-9"></div>
                                        </div>

                                        <!-- Button -->

                                    </form>
                                </div>
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
@endsection

@section('after_styles')
    <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    @if (config('lang.direction') == 'rtl')
        <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
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

        .label {
            padding: 0 !important;
            margin: 0 !important;
        }


        [type=radio]:checked+label:after {
            visibility: hidden;
        }

        [type=radio]:not(:checked)+label:after,
        [type=radio]:not(:checked)+label:before {
            visibility: hidden;
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
    </script>

    <script type="text/javascript">
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
        });
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
