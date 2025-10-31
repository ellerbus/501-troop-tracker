@props(['id' => rand(10000, 99999)])

<span id="spinner-{{ $id }}"
      class="htmx-indicator"
      style="margin-left: 8px;">
  <i class="fa fa-spinner fa-spin"></i>
</span>