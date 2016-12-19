<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class Version4
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(
        Database $database
    ) {
        $this->database = $database;
    }

    public function __invoke()
    {
	    $this->updateWebsitesTable();
	    $this->addVarnishesTable();
    }
    
    private function updateWebsitesTable() 
    {
    	$updateQuery = <<<SQL
ALTER TABLE `websites` 
ADD COLUMN `varnish_id` INT(11) UNSIGNED NULL AFTER `user_id`;
SQL;
    	$this->database->exec($updateQuery);
    }
    
    private function addVarnishesTable()
    {
    	$query = <<<SQL
CREATE TABLE `dev_test`.`varnishes` (
  `varnish_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(45) NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`varnish_id`),
  UNIQUE INDEX `varnish_id_UNIQUE` (`varnish_id` ASC)
SQL;
	    $this->database->exec($query);
    }
}