<?php

namespace Msingi\Cms\Model;

class Menu
{
    public $id;
    public $route;
    public $label;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->route = (isset($data['route'])) ? $data['route'] : null;
        $this->label = (isset($data['label'])) ? $data['label'] : null;
    }

}