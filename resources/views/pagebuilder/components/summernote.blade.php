@php
$hash = md5(microtime(true) . rand(10000, 999999));
@endphp
<div class="form-group">
    <label class="form-control-label"
           for="textarea-text-{{ $hash }}">Текст</label>

    <div id="standalone-container-{{ $hash }}">
        <div id="toolbar-container-{{ $hash }}">
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
                                                <button class="ql-header tx-bold" value="3">H<small>3</small></button>
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
        <div id="editor-text-{{ $hash }}">{!! $value ?? '' !!}</div>
        <textarea class="form-control"
                  style="display: none"
                  id="textarea-text-{{ $hash }}"
                  name="text"
                  cols="30"
                  rows="7">{!! $value ?? '' !!}</textarea>
    </div>


</div>
<script>
    var container{{ $hash }} = document.getElementById('editor-text-{{ $hash }}');

    var options{{ $hash }} = {
        modules: {
            formula: true,
            syntax: true,
            toolbar: '#toolbar-container-{{ $hash }}'
        },
        placeholder: 'Начните вводить текст...',
        theme: 'snow'
    };

    var quill{{ $hash }} = new Quill(container{{ $hash }}, options{{ $hash }});

    quill{{ $hash }}.on('text-change', function(delta, oldDelta, source) {
        $('#textarea-text-{{ $hash }}').val($('#editor-text-{{ $hash }} .ql-editor').html());
    });
</script>
