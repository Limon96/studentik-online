@php if (!isset($prefix)) { $prefix = ''; } @endphp
<option value="{{ $option->id }}" @if($selected_id == $option->id) selected @endif>{{ $prefix }}{{ $option->title }}</option>
@if($option->categories)
    @foreach($option->categories as $category)
        @include('components.admin.select_option_categories', [
            'option' => $category,
            'selected_id' => $selected_id,
            'prefix' => '--' . $prefix
        ])
    @endforeach
@endif
