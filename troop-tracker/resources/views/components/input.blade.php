@props(['disabled' => false, 'property' => ''])
@php
$haserror = $errors->has($property);
$bracketed = preg_replace('/\.(\d+)/', '[$1]', $property);
$bracketed = preg_replace('/\.(\w+)/', '[$1]', $bracketed);
@endphp
<input name="{{ $bracketed }}"
       id="{{ $property }}"
       value="{{ old($property) }}"
       {{$disabled?'disabled':''}}
       {!!$attributes->merge(['class' => 'form-control' . ($haserror ? ' is-invalid' : '')])!!} />
<x-input-error :property="$property" />