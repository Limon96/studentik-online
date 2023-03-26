<div class="form-group">
    <label for="">{{ $label }}</label>
    <select name="{{ $name }}" class="form-control">
        <option value="">-- Не выбрано --</option>
        @foreach($values as $item)
        <option value="{{ $item['value'] }}" @if($value == $item['value']) selected @endif>{{ $item['name'] }}</option>
        @endforeach
    </select>
</div>
