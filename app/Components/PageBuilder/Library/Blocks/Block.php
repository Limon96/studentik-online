<?php

namespace App\Components\PageBuilder\Library\Blocks;

use App\Components\PageBuilder\Library\PageBuilderBlock;

class Block implements IBlock {

    protected $title;
    protected $type;
    protected $data = [];

    /**
     * @param string $title
     * @param string $type
     * @param array $data
     */
    public function __construct(string $title = '', string $type = '', array $data = [])
    {
        $this->title = $title;
        $this->type = $type;

        $this->setData($data);
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void {
        if (!empty($data)) {
            foreach ($data as $field) {
                if ($field['type'] == 'fields') {
                    $this->data['fields'] = PageBuilderBlock::convert($field['fields']);
                } else {
                    $this->data[$field['name']] = $field['value'] ?? '';
                }
            }
        }
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'data' => $this->data,
        ];
    }

}
