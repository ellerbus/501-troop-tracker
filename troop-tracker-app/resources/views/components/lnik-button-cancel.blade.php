@props(['url'])

<a href="{{ $url }}"
   {!!$attributes->merge(['class'=>'btn btn-outline-secondary px-4'])!!}>
  Cancel
</a>