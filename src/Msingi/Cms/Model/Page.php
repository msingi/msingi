<?php

namespace Msingi\Cms\Model;

class Page
{
    public $id;
    public $type;
    public $path;
    public $template;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
        $this->path = (isset($data['path'])) ? $data['path'] : null;
        $this->template = (isset($data['template'])) ? $data['template'] : null;
    }

}