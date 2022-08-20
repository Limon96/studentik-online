<div class="card bd">
    <div class="card-header bd-b">Изображение</div>
    <div class="card-body">
        <div class="form-group">
            <div id="preview">
                @if($item->image)
                <img src="{{ thumbnail($item->image, 480) }}" class="img-thumbnail" alt="Preview">
                @endif
                <input type="hidden" name="image" value="{{ $item->image }}">
                <label for="file-image" class="btn btn-dark w-100 mt-3">
                    <input type="file" id="file-image" name="image" class="form-control-file" accept="image/*" onchange="loadFile(event)" style="display: none">
                    Загрузить
                </label>
            </div>
        </div>
    </div>
</div>
