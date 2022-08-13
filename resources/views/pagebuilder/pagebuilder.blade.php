<div id="page-builder" class="alert alert-primary bd bd-primary pd-25 mg-b-20">
    <div class="content">
        @if($components)
            @foreach($components as $component)
                @includeIf('pagebuilder.component', $component)
            @endforeach
        @endif
        @include('pagebuilder.new_block')
    </div>
</div>

