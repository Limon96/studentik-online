@php $key = str_replace('-', '.', $field_id) @endphp
<div class="card bd mt-3 mb-3  wd-250">
    <div class="card-header bd-b">{{ $label }}</div>
    <div class="card-body">
        <div id="preview-{{ $field_id }}">
            @if($value)
                <button type="button" onclick="resetFile{{ $field_id }}();"
                        class="btn btn-outline-dark w-100 mb-3">Сбросить
                </button>
                <img src="{{ image(old($key, $value)) }}" class="img-thumbnail mb-3"
                     alt="Preview">
            @endif
            <input type="hidden" name="{{ $name }}" value="{{ old($key, $value) }}">

            <label for="file-image-{{ $field_id }}" class="btn btn-dark w-100">
                <input type="file" id="file-image-{{ $field_id }}" name="{{ $name }}"
                       class="form-control-file" accept="image/*"
                       onchange="loadFile{{ $field_id }}(event)" style="display: none">
                Загрузить
            </label>
        </div>
    </div>
</div>
<script>
    var loadFile{{ $field_id }} = function (event) {
        var src = URL.createObjectURL(event.target.files[0])

        if (!$('#preview-{{ $field_id }} img').length) {
            $('#preview-{{ $field_id }}').prepend('<img class="img-thumbnail mb-3">');
        }

        if (!$('#preview-{{ $field_id }} button').length) {
            $('#preview-{{ $field_id }}').prepend('<button type="button" onclick="resetFile{{ $field_id }}();" class="btn btn-outline-dark w-100 mb-3">Сбросить</button>');
        }

        $('#preview-{{ $field_id }} img').attr('src', src);

        $('#preview-{{ $field_id }} img').onload = function () {
            URL.revokeObjectURL(src) // free memory
        }
    };

    var resetFile{{ $field_id }} = function () {
        $('#preview-{{ $field_id }} button').remove();
        $('#preview-{{ $field_id }} img').remove();
        $('#preview-{{ $field_id }} input').val('');
    };
</script>
