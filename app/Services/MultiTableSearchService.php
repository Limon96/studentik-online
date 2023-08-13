<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MultiTableSearchService
{

    /**
     * @param array $models
     */
    public function __construct(
        protected array $models = [],
    )
    {
        //
    }

    /**
     * @param string $modelClass
     * @param string $selectRaw
     * @return void
     */
    public function setModel(string $modelClass, string $selectRaw): void
    {
        $this->models[$modelClass] = $selectRaw;
    }

    public function search(string $search)
    {
        if (count($this->models) < 1) {
            return false;
        }

        $unions = null;

        foreach ($this->models as $modelClass => $selectRaw) {
            $union = DB::table((new $modelClass)->getTable())
                ->selectRaw($selectRaw)
                ->having('description', 'LIKE', "%" . $search . "%")
                ->orHaving('title', 'LIKE', "%" . $search . "%");

            if ($unions) {
                $unions = $unions->union($union);
            } else {
                $unions = $union;
            }
        }

        return $unions->orderByDesc('date_added')
            ->limit(500)
            ->paginate(10);
    }

}
