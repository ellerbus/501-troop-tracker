@props(['value', 'optional' => false])

<label {{$attributes->merge(['class' => 'ps-2 mb-1'])}}>
  {{ $value ?? $slot }}
</label>