<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class VarnishManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByUser(User $user)
    {
	    $userId = $user->getUserId();
	    /** @var \PDOStatement $query */
	    $query = $this->database->prepare('SELECT * FROM varnishes WHERE user_id = :user');
	    $query->bindParam(':user', $userId, \PDO::PARAM_INT);
	    $query->execute();
	    return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function getWebsites(Varnish $varnish)
    {
    	$varnishId = $varnish->getVarnishId();
        $statement = $this->database->prepare('SELECT website_id from websites AS w INNER JOIN varnishes AS v ON v.varnish_id = w.varnish_id WHERE v.varnish_id = :varnishId');
        $statement->bindParam(':varnishId', $varnishId);
	    $statement->execute();
	    return $statement->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    public function getByWebsite(Website $website)
    {
        // TODO: add logic here
	    return [];
    }

    public function create(User $user, $ip)
    {
	    $userId = $user->getUserId();
	    /** @var \PDOStatement $statement */
	    $statement = $this->database->prepare('INSERT INTO varnishes (ip, user_id) VALUES (:ip, :userId)');
	    $statement->bindParam(':ip', $ip, \PDO::PARAM_STR);
	    $statement->bindParam(':userId', $userId);
	    $statement->execute();
	    return $this->database->lastInsertId();
    }

    public function link($varnishId, $websiteId)
    {
        $statement = $this->database->prepare('UPDATE websites SET varnish_id = :varnishId WHERE website_id = :websiteId');
        $statement->bindParam('varnishId', $varnishId);
        $statement->bindParam('websiteId', $websiteId);
	    $statement->execute();
    }

    public function unlink($websiteId)
    {
	    $statement = $this->database->prepare('UPDATE websites SET varnish_id = NULL WHERE website_id = :websiteId');
	    $statement->bindParam('websiteId', $websiteId);
	    $statement->execute();
    }

}