@php $key = str_replace('-', '.', $field_id) @endphp
<div class="form-group">
    <label class="form-control-label"
           for="textarea-{{ $field_id }}">Полное описание</label>

    <div id="standalone-{{ $field_id }}">
        <div id="toolbar-{{ $field_id }}">
                            <span class="ql-formats">
                              <select class="ql-font"></select>
                              <select class="ql-size"></select>
                            </span>
            <span class="ql-formats">
                              <button class="ql-bold"></button>
                              <button class="ql-italic"></button>
                              <button class="ql-underline"></button>
                              <button class="ql-strike"></button>
                            </span>
            <span class="ql-formats">
                              <select class="ql-color"></select>
                              <select class="ql-background"></select>
                            </span>
            <span class="ql-formats">
                              <button class="ql-script" value="sub"></button>
                              <button class="ql-script" value="super"></button>
                            </span>
            <span class="ql-formats">
                              <button class="ql-header" value="2"></button>
                                <button class="ql-header tx-bold"
                                        value="3">H<small>3</small></button>
                              <button class="ql-blockquote"></button>
                              <button class="ql-code-block"></button>
                            </span>
            <span class="ql-formats">
                              <button class="ql-list" value="ordered"></button>
                              <button class="ql-list" value="bullet"></button>
                              <button class="ql-indent" value="-1"></button>
                              <button class="ql-indent" value="+1"></button>
                            </span>
            <span class="ql-formats">
                              <button class="ql-direction" value="rtl"></button>
                              <select class="ql-align"></select>
                            </span>
            <span class="ql-formats">
                              <button class="ql-link"></button>
                              <button class="ql-image"></button>
                              <button class="ql-video"></button>
                              <button class="ql-formula"></button>
                            </span>
            <span class="ql-formats">
                              <button class="ql-clean"></button>
                            </span>
        </div>

        @php $value = old($key, $value ?? '') @endphp
        <div id="editor-{{ $field_id }}" class=" @error($key) is-invalid @enderror">{!! $value !!}</div>
        <textarea class="form-control @error($key) is-invalid @enderror" style="display: none" id="{{ $field_id }}" name="{{ $name }}">{{ $value }}</textarea>
    </div>
</div>
<script>

    $(document).ready(function () {
        var container = document.getElementById('editor-{{ $field_id }}');

        var options = {
            modules: {
                formula: true,
                syntax: true,
                imageResize: {},
                imageDrop: true,
                imageUploader: {
                    upload: (file) => {
                        return new Promise((resolve, reject) => {
                            const formData = new FormData();
                            formData.append("file", file);
                            formData.append("_token", "{{ csrf_token() }}");

                            fetch(
                                "/load/image",
                                {
                                    method: "POST",
                                    body: formData
                                }
                            )
                                .then((response) => response.json())
                                .then((result) => {
                                    console.log(result);
                                    resolve(result.image);
                                })
                                .catch((error) => {
                                    reject("Upload failed");
                                    console.error("Error:", error);
                                });
                        });
                    }
                },
                toolbar: '#toolbar-{{ $field_id }}'
            },
            placeholder: 'Начните вводить текст...',
            theme: 'snow',
            scrollingContainer: 'html'
        };

        var quill = new Quill(container, options);

        quill.on('text-change', function(delta, oldDelta, source) {
            $('#{{ $field_id }}').val($('#editor-{{ $field_id }} .ql-editor').html());
        });

    });

</script>
