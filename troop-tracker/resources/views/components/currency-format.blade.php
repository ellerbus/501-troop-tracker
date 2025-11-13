@props(['value'=>0, 'decimals'=>0])

<span>
  $ {{ $value == 0 ? '-' : number_format($value, $decimals) }}
</span>