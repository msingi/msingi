<?php

namespace Msingi\Cms\Model;

class PageFragment
{
    public $id;
    public $content;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->content = (isset($data['content'])) ? $data['content'] : null;
    }

}