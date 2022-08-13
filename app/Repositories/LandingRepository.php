<?php

namespace App\Repositories;

use App\Components\PageBuilder\PageBuilder;
use App\Models\Landing as Model;

/**
 * Class CatalogAttributeRepository.
 */
class LandingRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function getModelClass()
    {
        return Model::class;
    }

    public function getBySlug($slug)
    {
        $item = $this->startConditions()
            ->whereSlug($slug)
            ->where('status', 1)
            ->first();

        if (is_null($item)) {
            return null;
        }

        $item->components = json_decode($item->components, true);

        $item->blocks = app(PageBuilder::class)
            ->components($item->components)
            ->toBlocks();

        return $item;
    }

    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    public function getPagination($limit)
    {
        return $this
            ->startConditions()
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function all()
    {
        return $this
            ->startConditions()
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getTypeWorkPages()
    {
        return $this
            ->startConditions()
            ->where('type_page', '1')
            ->where('status', '1')
            ->orderBy('menu_title', 'asc')
            ->get();
    }

    public function getSubjectPages()
    {
        return $this
            ->startConditions()
            ->where('type_page', '1')
            ->where('status', '1')
            ->orderBy('menu_title', 'asc')
            ->get();
    }

}
