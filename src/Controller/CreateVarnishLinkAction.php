<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\Website;

class CreateVarnishLinkAction
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
    }

    public function execute()
    {
    	if (!isset($_POST['varnish_id']) && filter_var($_POST['varnish_id'], FILTER_VALIDATE_INT)) {
    		echo(json_encode(array('error' => 'Missing or invalid varnish-id')));
    		return;
	    }
	
	    if (!isset($_POST['website_id']) && filter_var($_POST['website_id'], FILTER_VALIDATE_INT)) {
		    echo(json_encode(array('error' => 'Missing or invalid website-id')));
		    return;
	    }
	    
    	$varnishId = $_POST['varnish_id'];
    	$websiteId = $_POST['website_id'];
    	
    	$assoc = filter_var($_POST['assoc'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    	if ($assoc === null) {
		    echo(json_encode(array('error' => 'Invalid input.')));
		    return;
	    }
        
	    if ($assoc) {
		    $this->varnishManager->link($varnishId, $websiteId);
	    }
	    else {
		    $this->varnishManager->unlink($websiteId);
	    }
		
	    echo(json_encode('success'));
    }
}