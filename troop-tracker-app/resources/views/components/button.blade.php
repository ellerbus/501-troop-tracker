@props(['uid' => 'button-' . uniqid()])

<button type="button"
        {!!$attributes->merge(['class' => 'btn'])!!}>
  {{ $slot }}
</button>