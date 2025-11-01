@props(['property' => ''])

@error($property)
<div class="top-message danger"
     style="margin-top: 2px; justify-content: left; border-radius: 4px;">{{ $message }}</div>
@enderror