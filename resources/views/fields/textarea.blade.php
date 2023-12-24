@php $key = str_replace('-', '.', $field_id) @endphp
<div class="form-group">
	<label for="textarea-{{ $field_id }}">{{ $label }}</label>
	<textarea name="{{ $name }}" id="textarea-{{ $field_id }}" class="form-control @error($key) is-invalid @enderror">{{ old($key, $value) }}</textarea>
</div>
