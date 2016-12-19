<?php

namespace Snowdog\DevTest\Model;

class Varnish
{

    public $varnish_id;
    public $ip;
    
    public function __construct()
    {
        $this->varnish_id = intval($this->varnish_id);
    }

    /**
     * @return int
     */
    public function getVarnishId()
    {
        return $this->varnish_id;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
}