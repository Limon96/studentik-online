<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;

/**
 * Class CatalogAttributeRepository.
 */
class BlogCategoryRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function getModelClass()
    {
        return Model::class;
    }

    public function findSlug($slug)
    {
        $item = $this->startConditions()
            ->whereSlug($slug)
            ->where('status', 1)
            ->first();

        if (is_null($item)) {
            return null;
        }

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
            ->select([
                'id',
                'title',
                'created_at',
                'updated_at',
                'status',
                'slug',
            ])
            ->with(['categories'])
            ->where('blog_category_id', 0)
            ->orderBy('title', 'asc')
            ->get();
    }

    public function forSelect()
    {
        return $this
            ->startConditions()
            ->select([
                'id',
                'title',
            ])
            ->with(['categories'])
            ->where('blog_category_id', 0)
            ->orderBy('title', 'asc')
            ->get();
    }

}
