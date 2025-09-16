<style>
    .help-panel {
        position: fixed;
        top: 87px;
        right: -400px; /* Initially off-screen for LTR */
        height: 100%;
        width: 300px;
        transition: right 0.4s ease;
        z-index: 1050;
        border-left: 1px solid #dee2e6;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        background: linear-gradient(to bottom right, #ffffff, #f8f9fa);
    }
    .help-panel.open {
        right: 0; /* Show panel */
    }
    .help-panel.rtl {
        right: auto;
        left: -400px; /* Initially off-screen for RTL */
    }
    .help-panel.rtl.open {
        left: 0; /* Show panel for RTL */
    }
    .helpBtn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .helpBtn:hover {
        background-color: #ccc;
    }
    .help-content {
        max-height: 85vh;
        overflow-y: auto;
        padding: 1rem;
    }
    .bubble {
        width: 100%;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .bubble-title {
        margin-bottom: 0.5rem;
        font-size: 1.15em;
        color: #4682b4;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .bubble p {
        font-size: 0.9em;
    }
    .close {
        color: #4682b4;
        float: right;
    }
</style>

<div class="col-1 d-flex align-items-center justify-content-end help-container flex-column">
    <!-- Help Button -->
    <span class="btn helpBtn" id="helpBtn-{{$post->id}}" data-post="{{$post->id}}">
    <i class="material-icons">help_outline</i>
  </span>
</div>

<div id="helpPanel-{{$post->id}}" class="help-panel {{ app()->getLocale() === 'ar' ? 'rtl' : '' }} shadow-lg">
    <div class="help-content">

        <button type="button" class="close" aria-label="Close" onclick="document.getElementById('helpPanel-{{$post->id}}').classList.remove('open');">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="title-3"><i class="icon-help-circled"></i> {{t('configuration_help_text')}} </h4>
        <br>

        @foreach ($helpItems as $item)
            <div class="bubble">
                <h2 class="bubble-title">
                    {{ $item['heading'] }}
                    <i class="material-icons bubble-icon">{{ $item['icon'] }}</i>
                </h2>
                <p>{!! $item['content'] !!} </p>
            </div>
        @endforeach
    </div>
</div>