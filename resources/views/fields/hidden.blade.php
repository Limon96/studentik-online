<input type="hidden" id="input-{{ $field_id }}" class="form-control" name="{{ $name }}" value="{{ old(str_replace('-', '.', $field_id), $value) }}">
