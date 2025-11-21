@props(['property', 'disabled'=>false, 'value'=>''])
@php
$haserror = $errors->has($property);
$bracketed = preg_replace('/\.(\d+)/', '[$1]', $property);
$bracketed = preg_replace('/\.(\w+)/', '[$1]', $bracketed);
@endphp
<input type="text"
       name="{{ $bracketed }}"
       id="{{ $property }}"
       value="{{ old($property, $value) }}"
       {{$disabled?'disabled':''}}
       {!!$attributes->merge(['class'=>'form-control' . ($haserror ? ' is-invalid' : '')])!!} />
<x-input-error :property="$property" />