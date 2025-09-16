@if (isset($customFields) and $customFields->count() > 0)
    <div class="row" id="cfContainer">
        <div class="col-xl-12">
            <div class="row pl-2 pr-2">
                <?php
                $preFilledMessage = '?text=' . rawurlencode(t('whatsapp_pre_filled_message', ['title' => $post->title, 'appName' => config('app.name')]));
                $socialButtons = ['Line' => '', 'Telegram' => '', 'Whatsapp' => strToDigit($post->phone), 'Viber' => strToDigit($post->phone)];
                foreach ($customFields as $field) {
                    if ($field->type === 'text' && strpos($field->name, 'Line') !== false) {
                        $socialButtons['Line'] = $field->default_value;
                    } elseif ($field->type === 'text' && strpos($field->name, 'Telegram') !== false) {
                        $socialButtons['Telegram'] = $field->default_value;
                    } elseif ($field->type === 'text' && strpos($field->name, 'Whatsapp') !== false) {
                        $socialButtons['Whatsapp'] = $field->default_value;
                    }
                }
                $class =  isset($isSidebar) && $isSidebar ? 'text-center btn-block' : '';
                ?>
                @foreach ($customFields as $field)
                    <?php
                    if (in_array($field->type, ['checkbox'])) {
                        $field->default_value = $field->default_value == 1 ? t('Yes') : t('No');
                    }
                    ?>
                    @if (is_array($field->default_value) and $field->type == 'checkbox_multiple')
                        @if (count($field->default_value) > 0 && getChatWithSellerToEn($field->id) == 'Chat with seller')
                            <div class="detail-line col-xl-12 pb-2 pl-1 pr-1">
                                <div class="rounded-small p-1 pt-2"
                                    style="{{ isset($isSidebar) && $isSidebar ? 'background:none' : '' }}">
                                    <h4 class=" {{ $class }}">{{ $field->name }}:</h4>
                                    <div class="row m-0 pt-2"
                                        style="{{ isset($isSidebar) && $isSidebar ? 'background:none' : '' }}">
                                        @foreach ($field->default_value as $valueItem)
                                            @continue(!isset($valueItem->value))
                                            @if ($valueItem->value == 'Whatsapp' && $socialButtons[$valueItem->value] != '')
                                                <a href="https://wa.me/{{ $socialButtons[$valueItem->value] }}{{ $preFilledMessage }}"
                                                    target="_blank"
                                                    class="btn btn-success btn-whatsapp mb-0 {{ $class }}">
                                                    <i class="fab fa-whatsapp"></i>&nbsp;{{ $valueItem->value }}
                                                </a>
                                            @elseif ($valueItem->value == 'Line' &&
                                                $socialButtons[$valueItem->value] != '')
                                                <a href="https://line.me/ti/p/{{ $socialButtons[$valueItem->value] }}{{ $preFilledMessage }}"
                                                    target="_blank"
                                                    class="btn btn-linechat mb-0 {{ $class }}"><i
                                                        class="fab fa-line"></i>
                                                    {{ $valueItem->value }}</a>
                                            @elseif ($valueItem->value == 'Telegram' && $socialButtons[$valueItem->value] != "")
                                                <a href="https://telegram.me/{{ $socialButtons[$valueItem->value] }}{{ $preFilledMessage }}"
                                                    target="_blank"
                                                    class="btn btn-telegram mb-0 {{ $class }}">
                                                    <i class="fab fa-telegram"></i>
                                                    {{ $valueItem->value }}
                                                </a>
                                            @elseif ($valueItem->value == 'Viber' &&
                                                $socialButtons[$valueItem->value] != "")
                                                <a href="viber://add?number={{ $socialButtons[$valueItem->value] }}{{ $preFilledMessage }}"
                                                    target="_blank"
                                                    class="btn btn-viber mb-0 {{ $class }}">
                                                    <i class="fab fa-viber"></i>&nbsp;{{ $valueItem->value }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif

<style>
    .btn-linechat {
        background: #06c755;
        color: #FFF !important;
        border-radius: 50px;
    }
    .btn-whatsapp {
        align-items: center;
        display: flex;
        justify-content: center;
        color: #FFF !important;
        border-radius: 50px;
    }

    .btn-viber {
        background: #7a549c;
        color: #FFF !important;
        align-items: center;
        justify-content: center;
        display: flex;
        border-radius: 50px;
    }

    .btn-telegram {
        background-color: #36a6d6;
        color: #FFF !important;
        border-radius: 50px;
    }

    .btn-telegram:hover,
    .btn-linechat:hover,
    .btn-viber:hover,
    .btn-telegram:focus,
    .btn-linechat:focus,
    .btn-viber:focus {
        color: #FFF !important;
        opacity: 0.7;
    }

</style>
