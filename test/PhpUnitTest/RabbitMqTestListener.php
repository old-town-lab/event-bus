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
    protected $defaultTestVirtualHost = 'test_event_buss';

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
        RabbitMqTestManager::HOST     => 'localhost',
        RabbitMqTestManager::PORT     => '15672',
        RabbitMqTestManager::LOGIN    => 'test_event_buss',
        RabbitMqTestManager::PASSWORD => 'test_event_buss'
    ];

    /**
     * Настройки листенера
     *
     * @var array
     */
    protected $options = [];

    /**
     * @inheritDoc
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

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
    }

    /**
     * @inheritDoc
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    /**
     * @inheritDoc
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * @inheritDoc
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * @inheritDoc
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
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
        $this->getRabbitMqTestManager()->clearRabbitMqVirtualHost();
    }

    /**
     * @inheritDoc
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
    }

    /**
     * Возвращает имя виртуального хоста кролика, используемого для тестирования
     *
     * @return string
     */
    public function getTestVirtualHost()
    {
        if ($this->testVirtualHost) {
            return $this->testVirtualHost;
        }
        $options = $this->getOptions();
        $testVirtualHost = array_key_exists(RabbitMqTestManager::VHOST, $options) ? $options[RabbitMqTestManager::VHOST] : $this->defaultTestVirtualHost;
        $this->testVirtualHost = $testVirtualHost;

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
        if ($this->connection) {
            return $this->connection;
        }
        $connection = [];
        $options = $this->getOptions();

        foreach ($this->defaultConnection as $key => $value) {
            $connection[$key] = array_key_exists($key, $options) ? $options[$key] : $value;
        }
        $this->connection = $connection;

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
        $virtualHost = $this->getTestVirtualHost();
        $manager = new RabbitMqTestManager($connectionConfig, $virtualHost);

        return $manager;
    }
}
