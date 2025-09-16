{{-- TinyMCE --}}
@if (config('settings.single.wysiwyg_editor') == 'tinymce')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
        <?php
        $editorI18n = \Lang::get('tinymce', [], config('app.locale'));
        $editorI18nJson = '';
        if (!empty($editorI18n)) {
            $editorI18nJson = collect($editorI18n)->toJson();
            // Convert UTF-8 HTML to ANSI
            $editorI18nJson = convertUTF8HtmlToAnsi($editorI18nJson);
        }
        ?>
    <script type="text/javascript">
        var timer;
        var formValid = true;
        tinymce.init({
            selector: '#description',
            language: '{{ !empty($editorI18nJson) ? config('app.locale') : 'en' }}',
            directionality: '{{ config('lang.direction') == 'rtl' ? 'rtl' : 'ltr' }}',
            height: 350,
            menubar: false,
            statusbar: true,
            elementpath: false,
            branding: false,
            placeholder: "{{t('Describe your job')}}",
            plugins: 'lists link table wordcount',
            toolbar: 'wordcount | undo redo | bold italic underline | forecolor backcolor | bullist numlist blockquote table | link unlink | alignleft aligncenter alignright | outdent indent | fontsizeselect',
            setup: function(editor) {
                editor.on('keyup', function() {
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        textFunction();
                    }, 1500);
                });
                function textFunction() {
                    var content = editor.getContent();
                    setSummary("#summary", content);
                    formValid = true;
                    blacklistWords.forEach(function(item) {
                        if (content.indexOf(item) >= 0) {
                            content = content.split(item).join('<span style="background-color: #e03e2d;">' + item + '</span>');
                            formValid = false;
                        }
                    });
                    editor.setContent(content);
                    setSummary("#summary", content);
                }
            }
        });

        @if (!empty($editorI18nJson))
        tinymce.addI18n('{{ config('app.locale') }}', <?php echo $editorI18nJson; ?>);
        @endif

        $("#title").on("keyup change", function(e) {
            var titleText = $("#title").val();

            blacklistWords.forEach(function(item) {
                if (titleText.indexOf(item) >= 0) {
                    formValid = false;
                }
            });
        });

        $('#submitPostForm').on('click', function (e) {
            e.preventDefault();

            if (formValid) {
                $('#postForm').submit();
            }

            return false;
        });
    </script>
@endif

{{-- CKEditor --}}
@if (config('settings.single.wysiwyg_editor') == 'ckeditor')
    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
        <?php
        $editorLocale = '';
        if (file_exists(public_path() . '/assets/plugins/ckeditor/translations/' . ietfLangTag(config('app.locale')) . '.js')) {
            $editorLocale = ietfLangTag(config('app.locale'));
        }
        if (empty($editorLocale)) {
            if (file_exists(public_path() . '/assets/plugins/ckeditor/translations/' . ietfLangTag(config('lang.locale')) . '.js')) {
                $editorLocale = ietfLangTag(config('lang.locale'));
            }
        }
        if (empty($editorLocale)) {
            if (file_exists(public_path() . '/assets/plugins/ckeditor/translations/' . strtolower(ietfLangTag(config('lang.locale'))) . '.js')) {
                $editorLocale = strtolower(ietfLangTag(config('lang.locale')));
            }
        }
        if (empty($editorLocale)) {
            $editorLocale = 'en';
        }
        ?>
    @if ($editorLocale != 'en')
        <script src="{{ asset('assets/plugins/ckeditor/translations/' . $editorLocale . '.js') }}"></script>
    @endif
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            ClassicEditor.create(document.querySelector('#description'), {
                language: '{{ $editorLocale }}',
                toolbar: {
                    items: [
                        'undo',
                        'redo',
                        '|',
                        'bold',
                        'italic',
                        '|',
                        'fontColor',
                        'fontBackgroundColor',
                        '|',
                        'bulletedList',
                        'numberedList',
                        'blockQuote',
                        'alignment',
                        '|',
                        'insertTable',
                        'link',
                        '|',
                        'heading',
                        '|',
                        'indent',
                        'outdent',
                        '|',
                        'removeFormat'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                }
            }).then(editor => {
                window.editor = editor;
                editor.model.document.on('change:data', (evt, data) => {
                    setSummary("#summary", editor.getData());
                });
            }).catch(error => {
                console.error('Oops, something gone wrong!');
                console.error(
                    'Please, report the following error in the https://github.com/ckeditor/ckeditor5 with the build id and the error stack trace:'
                );
                console.warn('Build id: v28nci2fjq9h-1yblopey8x43');
                console.error(error);
            });
        });
    </script>
@endif

{{-- Summernote --}}
@if (config('settings.single.wysiwyg_editor') == 'summernote')
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
        <?php
        $editorLocale = '';
        if (file_exists(public_path() . '/assets/plugins/summernote/lang/summernote-' . ietfLangTag(config('app.locale')) . '.js')) {
            $editorLocale = ietfLangTag(config('app.locale'));
        }
        if (empty($editorLocale)) {
            if (file_exists(public_path() . '/assets/plugins/summernote/lang/summernote-' . ietfLangTag(config('lang.locale')) . '.js')) {
                $editorLocale = ietfLangTag(config('lang.locale'));
            }
        }
        if (empty($editorLocale)) {
            if (file_exists(public_path() . '/assets/plugins/summernote/lang/summernote-' . strtolower(ietfLangTag(config('lang.locale'))) . '.js')) {
                $editorLocale = strtolower(ietfLangTag(config('lang.locale')));
            }
        }
        if (empty($editorLocale)) {
            $editorLocale = 'en-US';
        }
        ?>
    @if ($editorLocale != 'en-US')
        <script src="{{ url('assets/plugins/summernote/lang/summernote-' . $editorLocale . '.js') }}" type="text/javascript">
        </script>
    @endif
    <script type="text/javascript">
        $(document).ready(function() {
            $('#description').summernote({
                lang: '{{ $editorLocale }}',
                placeholder: '{{ t('describe_what_makes_your_ad_unique') }}...',
                tabsize: 2,
                height: 350,
                callbacks: {
                    onKeyup: function(contents, $editable) {
                        setSummary("#summary", contents.currentTarget.innerText);
                    }
                },
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']]
                ]
            });
        });

    </script>
@endif

{{-- Simditor --}}
@if (config('settings.single.wysiwyg_editor') == 'simditor')
    <script src="{{ asset('assets/plugins/simditor/scripts/mobilecheck.js') }}"></script>
    <script src="{{ asset('assets/plugins/simditor/scripts/module.js') }}"></script>
    <script src="{{ asset('assets/plugins/simditor/scripts/hotkeys.js') }}"></script>
    <script src="{{ asset('assets/plugins/simditor/scripts/dompurify.js') }}"></script>
    <script src="{{ asset('assets/plugins/simditor/scripts/simditor.js') }}"></script>
        <?php
        $editorI18n = \Lang::get('simditor', [], config('app.locale'));
        $editorI18nJson = '';
        if (!empty($editorI18n)) {
            $editorI18nJson = collect($editorI18n)->toJson();
            // Convert UTF-8 HTML to ANSI
            $editorI18nJson = convertUTF8HtmlToAnsi($editorI18nJson);
        }
        ?>
    <script type="text/javascript">
        @if (!empty($editorI18nJson))
            Simditor.i18n = {
            '{{ config('app.locale') }}': <?php echo $editorI18nJson; ?>
        };
        @endif

                <?php /* Fake Code Separator */ ?>

        (function() {
            $(function() {
                @if (!empty($editorI18nJson))
                    Simditor.locale = '{{ config('app.locale') }}';
                @endif

                var $preview, editor, mobileToolbar, toolbar, allowedTags;

                toolbar = ['bold', 'italic', 'underline', '|', 'fontScale', 'color', '|', 'ul', 'ol',
                    'blockquote', '|', 'table', 'link', '|', 'alignment', 'indent', 'outdent'
                ];
                mobileToolbar = ["bold", "italic", "underline", "ul", "ol"];
                if (mobilecheck()) {
                    toolbar = mobileToolbar;
                }
                allowedTags = ['br', 'span', 'a', 'img', 'b', 'strong', 'i', 'strike', 'u', 'font', 'p',
                    'ul', 'ol', 'li', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'hr', 'table'
                ];

                /* Init */
                editor = new Simditor({
                    textarea: $('#description'),
                    placeholder: '{{ t('describe_what_makes_your_ad_unique') }}...',
                    toolbar: toolbar,
                    allowedTags: allowedTags,
                    defaultImage: '{{ asset('assets/plugins/simditor/images/image.png') }}',
                    pasteImage: false,
                    upload: false
                });

                $preview = $('#preview');
                if ($preview.length > 0) {
                    return editor.on('valuechanged', function(e) {
                        return $preview.html(editor.getValue());
                    });
                }
            });
        }).call(this);
    </script>
@endif
<script>
    function convertToPlain(html) {
        var tempDivElement = document.createElement("div");
        tempDivElement.innerHTML = html;
        return tempDivElement.textContent || tempDivElement.innerText || "";
    }

    function setSummary(fieldName, html) {
        var maxLength = $(fieldName).attr("maxLength");
        $(fieldName).val(convertToPlain(html).substr(0, maxLength));
    }
</script>
