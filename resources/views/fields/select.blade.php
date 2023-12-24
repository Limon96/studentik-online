@php $key = str_replace('-', '.', $field_id) @endphp
<div class="form-group">
    <label for="select-{{ $field_id }}"
           class="form-control-label">{{ $label }}</label>
    <select name="{{ $name }}" id="select-{{ $field_id }}" class="form-control select2">
        @foreach($options as $option)
            <option value="{{ $option['value'] }}" @if($option['value'] == old($key, $value)) selected @endif>{{ $option['name'] }}</option>
        @endforeach
    </select>
</div>
