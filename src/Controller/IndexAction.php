<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class IndexAction
{

    /**
     * @var WebsiteManager
     */
    private $websiteManager;
	
	/**
	 * @var PageManager
	 */
    private $pageManager;

    /**
     * @var User
     */
    private $user;

    public function __construct(UserManager $userManager, WebsiteManager $websiteManager, PageManager $pageManager)
    {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    protected function getWebsites()
    {
        if($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        } 
        return [];
    }
    
    protected function getTotalPages()
    {
    	return $this->pageManager->getTotalPagesByUser($this->user);
    }
    
    protected function getMostRecentlyVisitedPage()
    {
    	return $this->pageManager->getMostRecentlyVisitedPageByUser($this->user);
    }
    
    protected function getLeastRecentlyVisitedPage()
    {
    	return $this->pageManager->getLeastRecentlyVisitedPageByUser($this->user);
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }
}