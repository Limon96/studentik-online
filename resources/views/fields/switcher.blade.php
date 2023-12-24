@php $key = str_replace('-', '.', $field_id) @endphp
<div class="form-group">
    <label class="ckbox" id="input-checkbox-{{ $field_id }}">
        <input type="hidden" id="input-checkbox-{{ $field_id }}" name="{{ $name }}" value="0">
        <input  type="checkbox" id="input-checkbox-{{ $field_id }}" name="{{ $name }}" @if(old($key, $value)) checked @endif value="1">
        <span>{{ $label }}</span>
    </label>
</div>
