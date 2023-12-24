@php $key = str_replace('-', '.', $field_id) @endphp
<div class="form-group">
	<label for="input-{{ $field_id }}">{{ $label }}</label>
	<input type="text" id="input-{{ $field_id }}" class="form-control @error($key) is-invalid @enderror" name="{{ $name }}" value="{{ old($key, $value) }}">
</div>
