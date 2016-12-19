<?php

namespace Snowdog\DevTest\Model;

use DateTime;

class Page
{

    public $page_id;
    public $url;
    public $website_id;
    public $last_visit;
    
    public function __construct()
    {
        $this->website_id = intval($this->website_id);
        $this->page_id = intval($this->page_id);
        if ($this->last_visit) {
            $this->last_visit = new DateTime($this->last_visit);
        }
    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }
	
	/**
	 * @return DateTime
	 */
    public function getLastVisit() 
    {
    	return $this->last_visit;
    }
	
	/**
	 * @return string
	 */
    public function getFormattedLastVisit() {
    	$dt = $this->getLastVisit();
    	if ($dt) {
		    return $this->getLastVisit()->format('Y-m-d H:i:s');
	    }
    	return '';
    }
}