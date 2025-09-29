<?php
namespace App\Controller\Model;

class eRedirect
{
    const LMS = 1;
}

class Redirect
{
    public $id;

    public $name;
    public $label;
    public $url; // TIMESTAMP
    public $active;

    public function __construct($id, $name, $label, $url, $active)
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->url = $url;
        $this->active = $active;
    }

    public function isActive()
    {
        return $this->active;
    }

}
