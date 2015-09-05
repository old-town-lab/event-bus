<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest;

use PHPUnit_Framework_TestListener;
use PHPUnit_Framework_Test;
use Exception;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_TestSuite;

/**
 * Class RabbitMqTestListener
 * @package OldTown\EventBuss\PhpUnitTest
 *
 */
class  RabbitMqTestListener implements PHPUnit_Framework_TestListener
{
    /**
     * @var RabbitMqTestManager
     */
    protected $rabbitMqTestManager;

    /**
     * Имя хоста используемого по умолчанию для тестирования
     *
     * @var string
     */
    protected $testVirtualHostDefault = 'test_event_buss';

    /**
     * Имя хоста используемого для тестирования
     *
     * @var string
     */
    protected $testVirtualHost;

    /**
     * Конфиг соеденения с кроликом по умолчанию
     *
     * @var array
     */
    protected $defaultConnection = [
        'host'     => 'localhost',
        'port'     => '15672',
        'login'    => 'test_event_buss',
        'password' => 'test_event_buss'
    ];
    /**
     * Конфиг соеденения с кроликом
     *
     * @var array
     */
    protected $connection;

    /**
     * @inheritDoc
     */
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addError() method.
    }

    /**
     * @inheritDoc
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        // TODO: Implement addFailure() method.
    }

    /**
     * @inheritDoc
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addIncompleteTest() method.
    }

    /**
     * @inheritDoc
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addRiskyTest() method.
    }

    /**
     * @inheritDoc
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addSkippedTest() method.
    }

    /**
     * @inheritDoc
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {

    }

    /**
     * @inheritDoc
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {

    }

    /**
     * @inheritDoc
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        if ($test instanceof RabbitMqTestCaseInterface && !$test->hasRabbitMqTestManager() instanceof RabbitMqTestManager) {
            $manager = $this->getRabbitMqTestManager();
            $test->setRabbitMqTestManager($manager);
        }
        if ($test instanceof RabbitMqTestCaseInterface && !$test->hasTestVirtualHost() instanceof RabbitMqTestManager) {
            $manager = $this->getTestVirtualHost();
            $test->setTestVirtualHost($manager);
        }
    }

    /**
     * @inheritDoc
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {

        // TODO: Implement endTest() method.
    }

    /**
     * Возвращает имя виртуального хоста кролика, используемого для тестирования
     *
     * @return string
     */
    public function getTestVirtualHost()
    {
        return $this->testVirtualHost;
    }

    /**
     *
     * @return RabbitMqTestManager
     */
    public function getRabbitMqTestManager()
    {
        if ($this->rabbitMqTestManager) {
            return $this->rabbitMqTestManager;
        }

        $this->rabbitMqTestManager = $this->createTestManager();

        $this->rabbitMqTestManager;

        return $this->rabbitMqTestManager;
    }

    /**
     * @return array
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     *
     *
     * @return RabbitMqTestManager
     */
    protected function createTestManager()
    {
        $connectionConfig = $this->getConnection();
        $manager = new RabbitMqTestManager($connectionConfig);

        return $manager;
    }
}
