@props(['property', 'disabled'=>false, 'value'=>''])
@php
$haserror = $errors->has($property);
$bracketed = to_bracket_name( $property);
@endphp
<input type="text"
       name="{{ $bracketed }}"
       id="{{ $property }}"
       value="{{ old($property, $value) }}"
       @disabled($disabled)
       {{$attributes->merge(['class'=>'form-control' . ($haserror ? ' is-invalid' : '')])}} />
<x-input-error :property="$property" />