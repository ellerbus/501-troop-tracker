@props(['disabled' => false, 'haserror' => false])

<input {{$disabled?'disabled':''}}
       {{$attributes->merge(['class' => 'form-control' . ($haserror ? ' is-invalid' : '')]) }}/>