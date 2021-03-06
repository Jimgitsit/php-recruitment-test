<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;
use DateTime;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }
    
    public function setLastVisit(Page $page, DateTime $dt) {
	    $pageId = $page->getPageId();
	    $dtStr = $dt->format('Y-m-d H:i:s');
	    /** @var \PDOStatement $statement */
	    $statement = $this->database->prepare('UPDATE pages SET last_visit = :lastVisit WHERE page_id = :pageId');
	    $statement->bindParam(':lastVisit', $dtStr, \PDO::PARAM_STR);
	    $statement->bindParam(':pageId', $pageId, \PDO::PARAM_INT);
	    return $statement->execute();
    }
    
    public function getTotalPagesByUser(User $user)
    {
    	$userId = $user->getUserId();
    	$query = <<<SQL
SELECT COUNT(*) FROM pages AS p
INNER JOIN websites AS w ON w.website_id = p.website_id
WHERE w.user_id = :userId
SQL;
    	$statement = $this->database->prepare($query);
	    $statement->bindParam('userId', $userId, \PDO::PARAM_INT);
	    $statement->execute();
	    return $statement->fetchColumn();
    }
    
    public function getMostRecentlyVisitedPageByUser(User $user)
    {
	    $userId = $user->getUserId();
	    $query = <<<SQL
SELECT w.hostname, p.url FROM pages AS p
INNER JOIN websites AS w ON w.website_id = p.website_id
WHERE w.user_id = :userId
ORDER BY p.last_visit DESC LIMIT 1
SQL;
	    $statement = $this->database->prepare($query);
	    $statement->bindParam('userId', $userId, \PDO::PARAM_INT);
	    $statement->execute();
	    $row = $statement->fetch(\PDO::FETCH_ASSOC);
	    return $row['hostname'] . '/' . $row['url'];
    }
	
	public function getLeastRecentlyVisitedPageByUser(User $user)
	{
		$userId = $user->getUserId();
		$query = <<<SQL
SELECT w.hostname, p.url FROM pages AS p
INNER JOIN websites AS w ON w.website_id = p.website_id
WHERE w.user_id = :userId
ORDER BY p.last_visit ASC LIMIT 1
SQL;
		$statement = $this->database->prepare($query);
		$statement->bindParam('userId', $userId, \PDO::PARAM_INT);
		$statement->execute();
		$row = $statement->fetch(\PDO::FETCH_ASSOC);
		return $row['hostname'] . '/' . $row['url'];
	}
}