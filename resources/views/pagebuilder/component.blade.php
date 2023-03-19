@include('pagebuilder.new_block')
<div class="card bd-primary mt-2 mb-2" data-type="{{ $type }}">
    <div class="card-header bg-primary tx-white">
        {{ $title }}
        <a class="float-right remove-block" onclick="removeBlock(this);" title="Удалить"><i class="fa fa-minus"></i></a>
        <a class="float-right up-block" onclick="upBlock(this);" title="Переместить вверх"><i class="fa fa-chevron-up"></i></a>
        <a class="float-right down-block" onclick="downBlock(this);" title="Переместить вниз"><i class="fa fa-chevron-down"></i></a>
    </div>
    <div class="card-body">
        @foreach($fields as $field)
            @if(isset($field['type']))
                @includeIf('pagebuilder.components.' . $field['type'], $field)
            @endif
        @endforeach
    </div><!-- card-body -->
</div>
