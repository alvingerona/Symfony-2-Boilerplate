<?php

namespace Generic\BehatBundle\Context;

use Youwe\Bundle\BehatBundle\Context\Commands;

/**
 * Class ProjectFunctionContext
 * @package Generic\BehatBundle\Context
 */
class ProjectFunctionContext extends FeatureContext
{
    /**
     * @Given I clear the database
     */
    public function iClearTheDatabase()
    {
        $commands = new Commands();
        throw new \Exception("Choose the propel or doctrine database setup");
//        $commands->propelDatabaseSetup();
//        $commands->doctrineDatabaseSetup();
    }
}