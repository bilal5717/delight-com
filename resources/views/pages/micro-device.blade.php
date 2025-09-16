@extends('layouts.micro-device-master')

@section('content')
    <div class="main-container inner-page" {!! config('lang.direction') == 'rtl' ? 'dir="rtl"' : '' !!}>
        <div class="container">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12 page-content">
                        <div class="inner-box relative">
                            <div class="row">
                                <div class="col-sm-12 page-content">
                                    <div class="text-content text-left from-wysiwyg">
                                        @if (!empty($page->picture))
                                            <img src="storage/{{$page->picture}}" alt="">
                                        @endif
                                        <h3 class="text-center" style="margin-top:20px;color: {!! $page->title_color !!};">{{ $page->title }}</h3>
                                        {!! $page->content !!}
                                        @if(Request::is('/'))
                                            <div class="switch-toggle">
                                                <form id="toggle-view-form" method="POST" action="{{ route('toggle-view') }}">
                                                    @csrf
                                                    <input
                                                        class="switch-toggle-checkbox"
                                                        type="checkbox"
                                                        id="pricing-plan-switch"
                                                        name="view"
                                                        onchange="this.form.submit()"
                                                        {{ session('view') === 'simple' ? 'checked' : '' }}
                                                    />
                                                    <label class="switch-toggle-label" for="pricing-plan-switch">
                                                        <span>{{ t('Main') }}</span>
                                                        <span>{{ t('Simple') }}</span>
                                                    </label>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #wrapper {
            min-height: calc(100vh - 100px); /* Adjust height to accommodate header/footer */
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .main-container {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .page-content {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .inner-box {
            background: none;
            border: none;
            padding: 50px;
            max-width: 1200px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .text-content img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 20px;
            color: {!! $page->title_color !!};
            font-size: 2rem;
            text-align: center;
        }

        .text-content {
            font-size: 1rem;
            line-height: 1.6;
        }

        .switch-toggle {
            position: relative;
            background: rgba(227, 229, 232, 0.5);
            border-radius: var(--radius);
            margin-left: 10px;
            direction: {{ config('lang.direction') == 'rtl' ? 'rtl' : 'ltr' }};
        }

        .switch-toggle input[type="checkbox"] {
            cursor: pointer;
            position: absolute;
            inset: 0;
            appearance: none;
            z-index: 2;
        }

        .switch-toggle input[type="checkbox"]:checked + label.switch-toggle-label:before {
            transform: translateX({{ config('lang.direction') == 'rtl' ? '-100%' : '100%' }});
        }

        .switch-toggle-label {
            position: relative;
            inset: 0;
            grid-auto-flow: column;
            place-items: center;
            cursor: pointer;
            display: grid;
        }

        .switch-toggle-label:before {
            content: "";
            position: absolute;
            width: 50%;
            inset: 0;
            background: #fff;
            border-radius: calc(var(--radius) - var(--offset));
            transition: transform 250ms ease;
        }

        .switch-toggle-label span {
            position: relative;
            transition: 200ms linear;
            padding: 10px;
        }

        .switch-toggle-label span:nth-child(1) {
            color: #1a1a1a;
        }

        .switch-toggle-label span:nth-child(2) {
            color: gray;
        }
    </style>
@endsection
