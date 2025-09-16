<!-- checkbox field -->
<div @include('admin::panel.inc.field_wrapper_attributes') >
	@include('admin::panel.fields.inc.translatable_icon')
    <div class="checkbox">
    	<label class="custom-switch-box">
    	  <input type="hidden" name="{{ $field['name'] }}" value="0">
    	  <input type="checkbox" value="1"

          name="{{ $field['name'] }}"

          @if (isset($field['value']))
            @if( ((int) $field['value'] == 1 || old($field['name']) == 1) && old($field['name']) !== '0' )
             checked="checked"
            @endif
          @elseif (isset($field['default']) && $field['default'])
            checked="checked"
          @endif

          @if (isset($field['attributes']))
              @foreach ($field['attributes'] as $attribute => $value)
    			{{ $attribute }}="{{ $value }}"
        	  @endforeach
          @endif
          > <span class="custom-switch-slider custom-switch-round"></span>
    	</label>
      {!! $field['label'] !!}

        {{-- HINT --}}
        @if (isset($field['hint']))
			<br>
			<small class="form-control-feedback">{!! $field['hint'] !!}</small>
        @endif
    </div>
</div>

<style>.custom-switch-box{position:relative;display:inline-block;width:38px;height:17px}.custom-switch-box input{opacity:0;width:0;height:0}.custom-switch-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;-webkit-transition:.4s;transition:.4s}.custom-switch-slider:before{position:absolute;content:"";height:13px;width:13px;left:2px;bottom:2px;background-color:#fff;-webkit-transition:.4s;transition:.4s}.custom-switch-box input:checked+.custom-switch-slider{background-color:#2196f3}.custom-switch-box input:focus+.custom-switch-slider{box-shadow:0 0 1px #2196f3}.custom-switch-box input:checked+.custom-switch-slider:before{-webkit-transform:translateX(20px);-ms-transform:translateX(20px);transform:translateX(20px)}.custom-switch-slider.custom-switch-round{border-radius:17px}.custom-switch-slider.custom-switch-round:before{border-radius:50%}</style>