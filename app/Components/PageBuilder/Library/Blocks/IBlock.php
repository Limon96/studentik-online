<?php

namespace App\Components\PageBuilder\Library\Blocks;

interface IBlock {

    public function setTitle(string $title);
    public function setType(string $type);
    public function setData(array $data);

}
