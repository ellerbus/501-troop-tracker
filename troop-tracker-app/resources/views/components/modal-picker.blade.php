@props(['id'=>'modal-picker', 'label'=>'TITLE'])

<div class="modal fade modal-picker"
     id="{{ $id }}"
     tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          {{ $label }}
        </h5>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <x-loading />
      </div>
    </div>
  </div>
</div>