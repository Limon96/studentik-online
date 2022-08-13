<div class="card bd-primary mt-3 mb-2" data-type="fields">
    <div class="card-header bg-primary tx-white">
        {{ $title }}
        <a class="float-right remove-block" onclick="removeBlock(this);" title="Удалить"><i class="fa fa-minus"></i></a>
        <a class="float-right up-block" onclick="upBlock(this);" title="Переместить вверх"><i class="fa fa-chevron-up"></i></a>
        <a class="float-right down-block" onclick="downBlock(this);" title="Переместить вниз"><i class="fa fa-chevron-down"></i></a>
    </div>
    <div class="card-body">
        @foreach($fields as $component)
            @includeIf('pagebuilder.component', $component)
        @endforeach
        @include('pagebuilder.components.part.new_block')
    </div><!-- card-body -->
</div>
