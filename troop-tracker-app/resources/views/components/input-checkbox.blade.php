@props(['property', 'label', 'disabled'=>false, 'value'=>'1', 'checked'=>false, 'spinner'=>null])
@php
$haserror = $errors->has($property);
$bracketed = preg_replace('/\.(\d+)/', '[$1]', $property);
$bracketed = preg_replace('/\.(\w+)/', '[$1]', $bracketed);
@endphp
<div class="form-check">
  <input type="checkbox"
         name="{{ $bracketed }}"
         id="{{ $property }}"
         value="{{ $value }}"
         @checked($checked?'checked':'')
         {{$disabled?'disabled':''}}
         {!!$attributes->merge(['class'=>'form-check-input' . ($haserror ? ' is-invalid' : '')])!!}/>
  <label class="form-check-label"
         for="{{ $property }}">
    {{ $label }}
    @if(!empty($spinner))
    <x-spinner :id="$spinner" />
    @endif
  </label>
</div>
<x-input-error :property="$property" />