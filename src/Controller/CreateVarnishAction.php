<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;

class CreateVarnishAction
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
        $ip = $_POST['ip'];
	
	    if (isset($_SESSION['login'])) {
		    $user = $this->userManager->getByLogin($_SESSION['login']);
		    
		    if (empty($ip)) {
			    $_SESSION['flash'] = 'IP cannot be empty!';
		    }
		    else {
		    	// TODO: Prevent duplicate IPs
		    	if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
				    $_SESSION['flash'] = 'Invalid IP!';
			    }
			    else {
				    if ($this->varnishManager->create($user, $ip)) {
					    $_SESSION['flash'] = 'Varnish ' . $ip . ' added!';
				    }
			    }
		    }
	    }

        header('Location: /varnishes');
    }
}