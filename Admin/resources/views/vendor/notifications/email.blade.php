@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Olá!')
@endif
@endif

{{-- Intro Lines --}}
@lang('Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha de sua conta.')

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@lang('Este link de redefinição de senha irá expirar em 60 minutos.')<br>
@lang('Se você não solicitou uma redefinição de senha, nenhuma ação adicional será necessária.')

{{-- Salutation --}}
@lang('Atenciosamente,')<br>
@lang('Conciflex')<br>

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(

    "Se você tiver problemas para clicar no botão Redefinir senha, copie e cole o URL abaixo em seu navegador:",
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
