<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;

/**
 * Class CatalogAttributeRepository.
 */
class BlogPostRepository extends CoreRepository
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
            ->where('publish_at', '<', date('Y.m.d H:i:s'))
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

    public function paginate($limit = 20)
    {
        return $this
            ->startConditions()
            ->select([
                'id', 'title', 'intro', 'slug', 'image', 'views', 'publish_at'
            ])
            ->where('publish_at', '<', date('Y.m.d H:i:s'))
            ->where('status', 1)
            ->orderBy('publish_at', 'desc')
            ->paginate($limit);
    }

    public function fromCategory($blog_category_ids = [], $limit = 20)
    {
        return $this
            ->startConditions()
            ->select([
                'id', 'title', 'intro', 'slug', 'image', 'views', 'publish_at'
            ])
            ->where('publish_at', '<', date('Y.m.d H:i:s'))
            ->where('status', 1)
            ->whereIn('blog_category_id', $blog_category_ids)
            ->orderBy('publish_at', 'desc')
            ->paginate($limit);
    }

    public function latest($limit = 20)
    {
        return $this
            ->startConditions()
            ->select([
                'id', 'title', 'slug', 'image', 'views', 'publish_at'
            ])
            ->where('publish_at', '<', date('Y.m.d H:i:s'))
            ->where('status', 1)
            ->orderBy('publish_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function all()
    {
        return $this
            ->startConditions()
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function popular($limit = 20, $ignoreId = 0)
    {
        return $this
            ->startConditions()
            ->select([
                'id', 'title', 'slug', 'image', 'views', 'publish_at'
            ])
            ->where('publish_at', '<', date('Y.m.d H:i:s'))
            ->where('status', 1)
            ->where('id', '<>', $ignoreId)
            ->orderByDesc('views')
            ->limit($limit)
            ->get();
    }


}
