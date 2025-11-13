@props(['type'=>'info'])

<div class="alert alert-{{$type}} text-center border border-{{$type}} rounded-2 py-3 px-4 my-2">
  <i class="fa fa-fw fa-exclamation-circle"></i>
  {{ $slot }}
</div>