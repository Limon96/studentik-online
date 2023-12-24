<div class="form-group">
    <label for="">{{ $label }}</label>
    @dump($item->{$name})
    @foreach($item->{$name} as $relationItem)

        @foreach($fields as $field)
            {{ $field->render($relationItem->{$field->name} ?? '') }}
        @endforeach
    @endforeach
</div>
