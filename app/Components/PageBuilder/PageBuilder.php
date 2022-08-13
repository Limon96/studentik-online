<?php

namespace App\Components\PageBuilder;

use App\Components\PageBuilder\Library\PageBuilderBlock;
use function Composer\Autoload\includeFile;

class PageBuilder {

    private $components = [];

    public function run()
    {
        return view('pagebuilder.pagebuilder', [
            'components' => $this->components
        ]);
    }

    /**
     * @return array
     */
    public function toBlocks(): array
    {
        $blocks = PageBuilderBlock::convert($this->components);
        //dd($blocks);

        return $blocks;
    }

    public function components($components): PageBuilder
    {
        $this->components = $components;

        return $this;
    }

}
