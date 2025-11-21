@props(['property', 'value'=>''])
@php
$bracketed = preg_replace('/\.(\d+)/', '[$1]', $property);
$bracketed = preg_replace('/\.(\w+)/', '[$1]', $bracketed);
@endphp
<input type="hidden"
       name="{{ $bracketed }}"
       id="{{ $property }}"
       value="{{ old($property, $value) }}" />