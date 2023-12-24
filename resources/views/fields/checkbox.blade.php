@php $key = str_replace('-', '.', $field_id) @endphp
<div class="form-group">
    <label class="ckbox" id="input-checkbox-{{ $field_id }}">
        <input  type="checkbox" id="input-checkbox-{{ $field_id }}" name="{{ $name }}" @if(old($key, $value) == 1) checked @endif value="{{ $value }}">
        <span>{{ $label }}</span>
    </label>
</div>
