<div class="container">
    <div class="row">
        @if ($fields == '')
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                <div class="error_log alert alert-danger"> <i class="fas fa-exclamation-triangle"></i>
                    <b> {{ t('currently_not_available') }}</b>
                </div>
            </div>
        @else
            <div class="col-md-3 col-form-label">
                <label for="information"> {{ t('information') }}
                </label>
            </div><br />
            <div class="col-md-9 mb-1">
                <input type="text" class="form-control" name="information" id="information"
                    placeholder="{{ t('enter_information') }}">
            </div>

            @foreach ($fields as $field)
                <div class="col-md-3 col-form-label">
                    <label>{{ t($field['label']) }}
                        {{ @$field['help'] ? "<a title='$field->help'>&copy;</a>" : '' }}
                        <sup class="sup">
                            {{ @$field['required'] ? '*' : '' }}
                        </sup>
                    </label>
                </div><br />
                <div class="col-md-9">
                    @if ($field['input_type'] == 'text')
                        <input type="text" class="form-control" name="{{ $field['field_name'] }}"
                            value="{{ $payment ? $payment[$field['field_name']] : '' }}"
                            placeholder="{{ t($field['placeholder']) }}" {{ @$field['required'] ? 'required' : '' }}>
                    @elseif ($field['input_type'] == 'radio')
                        @foreach ($field['options'] as $option)
                            <input type="radio"
                                {{ $payment != '' && $payment[$field['field_name']] == $option['value'] ? 'checked' : (@$option->selected ? 'checked' : '') }}
                                name="{{ $field['field_name'] }}" value="{{ $option['value'] }}">
                            {{ t($option['label']) }}<br />
                        @endforeach
                    @endif
                </div>
            @endforeach
    </div>
    @if ($payments > 0 || $payment)
        <div class="form-group row">
            <div class="col-md-3"></div>
            <div class="col-md-9">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                        {{ $payment && $payment['default_payment'] ? 'checked' : '' }} name="default_payment"
                        value="1" id="flexCheckChecked">
                    <label class="form-check-label" for="flexCheckChecked">
                        {{ t('Set as default Payment') }}
                    </label>
                </div>
            </div>
        </div>
    @else
        <input type="hidden" name="default_payment" id="default_payment" value="true">
    @endif
    @endif
</div>
<style>
    .sup {
        color: red;
    }
</style>
