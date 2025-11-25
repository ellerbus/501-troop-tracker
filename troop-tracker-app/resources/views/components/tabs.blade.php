@props(['tabs'=>[]])

@if (count($tabs) > 0)
{{-- Desktop Tabs --}}
<nav class="d-none d-md-block">
  <ul class="nav nav-tabs">
    @foreach ($tabs as $tab)
    <li class="nav-item">
      <a class="nav-link {{ $tab['active'] ?? false ? 'active' : '' }}"
         href="{{ route($tab['route'], $tab['parms'] ?? []) }}">
        {{ $tab['label'] }}
      </a>
    </li>
    @endforeach
  </ul>
</nav>

{{-- Mobile Select --}}
<div class="d-md-none">
  <select name="tab-picker"
          class="form-select"
          onchange="location.href=this.options[this.selectedIndex].dataset.href">
    @foreach ($tabs as $tab)
    <option value="{{ $tab['label'] }}"
            data-href="{{ route($tab['route'], $tab['parms'] ?? []) }}"
            {{$tab['active']??false?'selected':''}}>
      {{ $tab['label'] }}
    </option>
    @endforeach
  </select>
</div>
@endif