@props(['disabled' => false, 'property' => ''])
@php($haserror = $errors->has($property))

<input name="{{ $property }}"
       id="{{ $property }}"
       value="{{ old($property) }}"
       {{$disabled?'disabled':''}}
       {!!$attributes->merge(['class' => 'form-control' . ($haserror ? ' is-invalid' : '')])!!} />
<x-input-error :property="$property" />