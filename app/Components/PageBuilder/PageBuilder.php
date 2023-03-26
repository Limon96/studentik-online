<?php

namespace App\Components\PageBuilder;

use App\Components\PageBuilder\Library\PageBuilderBlock;
use App\Components\PageBuilder\Library\PageBuilderComponent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use function Composer\Autoload\includeFile;

class PageBuilder {

    private $components = [];

    /**
     * @return Application|Factory|View
     */
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

        return $blocks;
    }

    /**
     * @param $components
     * @return $this
     */
    public function components($components): PageBuilder
    {
        $this->components = array_map(function ($component) {
                return $this->complete($component, PageBuilderComponent::get($component['type']));
        }, $components);

        return $this;
    }

    /**
     * @param $component
     * @param $data
     * @return array
     */
    private function complete($component, $data): array
    {
        foreach ($component['fields'] as $i => $field) {

            if ($field['type'] == 'select') {
                $component['fields'][$i]['values'] = $data['fields'][$i]['values'];
            }
        }

        return $component;
    }

}
