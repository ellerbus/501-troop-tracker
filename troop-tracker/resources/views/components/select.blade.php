@props(['disabled' => false,'options' => [],'selected_value' => '','optional' => false,'property' =>''])
@php($haserror = $errors->has($property))

<select name="{{ $property }}"
        id="{{ $property }}"
        {{$disabled?'disabled':''}}
        {!!$attributes->merge(['class' => 'form-select' . ($haserror ? ' is-invalid' : '')])!!}>
  {{ $slot }}
  @if($optional)
  <option></option>
  @endif
  @foreach ($options as $key => $option)
  <option value="{{ $key }}"
          @selected($key==$selected_value)>
    {{ $option }}
  </option>
  @endforeach
</select>