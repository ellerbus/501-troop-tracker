@if ($flash_messages)
<div class="space-content">
  @foreach ($flash_messages as $type => $messages)
  @foreach ($messages as $message)
  <div class="top-message {{ $type }}">{{ $message }}</div>
  @endforeach
  @endforeach
</div>
@endif

@if ($errors->any())
<div class="space-content">
  @foreach($errors->all() as $error)
  <div class="top-message danger">{{ $error }}</div>
  @endforeach
</div>
@endif