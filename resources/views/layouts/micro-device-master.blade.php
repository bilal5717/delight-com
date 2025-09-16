<?php
$publicDisk = \Storage::disk(config('filesystems.default'));
?>
        <!DOCTYPE html>
<html lang="{{ ietfLangTag(config('app.locale')) }}" {!! config('lang.direction') == 'rtl' ? ' dir="rtl"' : '' !!}>

<head>
    <!-- Meta tags for improved SEO -->
    <meta name="robots1" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="yandexbot" content="index, follow">
    <meta name="bingbot" content="index, follow">
    <meta name="yahoobot" content="index, follow">
    <meta name="Baiduspider" content="index, follow">
    <meta name="revisit-after" content="7 days">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="x-dns-prefetch-control" content="on">

    <!-- Include essential meta tags and viewport -->
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.meta-robots', 'common.meta-robots'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-title" content="{{ config('settings.app.app_name') }}">
    <link rel="canonical" href="{{ request()->fullUrl() }}">
    <link rel="prefetch" href="{{ request()->fullUrl() }}">
    <link rel="dns-prefetch" href="{{ request()->fullUrl() }}">
    <link rel="x-dns-prefetch" href="{{ request()->fullUrl() }}">

    <!-- Add links and icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-144-precomposed.png') . getPictureVersion() }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-114-precomposed.png') . getPictureVersion() }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-72-precomposed.png') . getPictureVersion() }}">
    <link rel="apple-touch-icon-precomposed" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-57-precomposed.png') . getPictureVersion() }}">
    <link rel="shortcut icon" href="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}">
    <title>{!! MetaTag::get('title') !!}</title>
    {!! MetaTag::tag('description') !!}{!! MetaTag::tag('keywords') !!}

    <!-- Include additional scripts and stylesheets -->
    @stack('before_styles_stack')
    @yield('before_styles')

    @if (config('lang.direction') == 'rtl')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo|Changa" media="none" onload="if(media!='all')media='all'">
    @endif

    @include('common.structure-mobile-inc', ['pageTitle' => "home", "pageUrl" => ""])

    @stack('after_styles_stack')
    @yield('after_styles')

    @if (config('settings.style.custom_css'))
        <style>{!! printCss(config('settings.style.custom_css')) !!}</style>
    @endif

    @if (config('settings.other.js_code'))
        <script>
            /* Defer non-critical JavaScript */
            function loadDeferredScript() {
                var script = document.createElement('script');
                script.src = 'data:text/javascript;base64,' + btoa('{!! printJs(config('settings.other.js_code')) !!}');
                document.body.appendChild(script);
            }
            window.addEventListener('load', loadDeferredScript);
        </script>
    @endif

</head>

<body class="{{ config('app.skin') }}">
<div id="wrapper">
    @yield('content')

    @section('info')
    @show
</div>

</script>
@section('content')
    <script src="resources/views/layouts/load.js" defer></script>
@endsection


</body>

</html>
