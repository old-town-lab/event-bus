<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\RabbitMqTestUtils;

use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\Comparator\BindingComparator;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\Comparator\ExchangeComparator;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\Comparator\QueueComparator;
use PHPUnit_Framework_TestListener;
use PHPUnit_Framework_Test;
use Exception;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_TestSuite;
use SebastianBergmann\Comparator\Factory as ComparatorFactory;

/**
 * Class RabbitMqTestListener
 * @package OldTown\EventBus\PhpUnit\RabbitMqTestUtils
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
    protected $defaultTestVirtualHost = 'test_event_bus';

    /**
     * Имя хоста используемого для тестирования
     *
     * @var string
     */
    protected $testVirtualHost;

    /**
     * Коннект для тестирования
     *
     * @var array
     */
    protected $rabbitMqConnectionForTest;

    /**
     * Конфиг соеденения с API кролика по умолчанию
     *
     * @var array
     */
    protected static $defaultConnection = [
        RabbitMqTestManager::HOST     => 'localhost',
        RabbitMqTestManager::PORT_API     => '15672',
        RabbitMqTestManager::LOGIN    => 'test_event_bus',
        RabbitMqTestManager::PASSWORD => 'test_event_bus'
    ];

    /**
     * Конфиг соеденения с кроликом по умолчанию
     *
     * @var array
     */
    protected static $defaultRabbitMqConnectionForTest = [
        RabbitMqTestManager::HOST     => 'localhost',
        RabbitMqTestManager::PORT     => '5672',
        RabbitMqTestManager::LOGIN    => 'test_event_bus',
        RabbitMqTestManager::PASSWORD => 'test_event_bus',
        RabbitMqTestManager::VHOST => 'test_event_bus'
    ];

    /**
     * Настройки листенера
     *
     * @var array
     */
    protected $options = [];

    /**
     * Флаг определяющий был ли инциализированы Comparator
     *
     * @var boolean
     */
    protected static $flagInitComparator = false;

    /**
     * @inheritDoc
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
        $this->initComparator();
    }

    /**
     * Иниициируем возможность работы с assert'ами
     *
     */
    protected function initComparator()
    {
        if (false === static::$flagInitComparator) {
            $factory = ComparatorFactory::getInstance();

            $exchangeComparator = new ExchangeComparator();
            $factory->register($exchangeComparator);

            $queueComparator = new QueueComparator();
            $factory->register($queueComparator);

            $bindingComparator = new BindingComparator();
            $factory->register($bindingComparator);

            static::$flagInitComparator = true;
        }
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
     *
     * @throws \InvalidArgumentException
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        if ($test instanceof RabbitMqTestCaseInterface && !$test->hasRabbitMqTestManager()) {
            $manager = $this->getRabbitMqTestManager();
            $test->setRabbitMqTestManager($manager);
        }
        if ($test instanceof RabbitMqTestCaseInterface && !$test->hasTestVirtualHost()) {
            $manager = $this->getTestVirtualHost();
            $test->setTestVirtualHost($manager);
        }
        if ($test instanceof RabbitMqTestCaseInterface && !$test->hasRabbitMqConnectionForTest()) {
            $connectionForTest = $this->getRabbitMqConnectionForTest();
            $test->setRabbitMqConnectionForTest($connectionForTest);
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
     *
     * @throws \InvalidArgumentException
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

        foreach (static::$defaultConnection as $key => $value) {
            $connection[$key] = array_key_exists($key, $options) ? $options[$key] : $value;
        }
        $this->connection = $connection;

        return $this->connection;
    }

    /**
     * @return array
     */
    public function getRabbitMqConnectionForTest()
    {
        if ($this->rabbitMqConnectionForTest) {
            return $this->rabbitMqConnectionForTest;
        }
        $connection = [];
        $options = $this->getOptions();

        foreach (static::$defaultRabbitMqConnectionForTest as $key => $value) {
            $connection[$key] = array_key_exists($key, $options) ? $options[$key] : $value;
        }
        $this->rabbitMqConnectionForTest = $connection;

        return $this->rabbitMqConnectionForTest;
    }
    /**
     *
     *
     * @return RabbitMqTestManager
     *
     * @throws \InvalidArgumentException
     */
    protected function createTestManager()
    {
        $connectionConfig = $this->getConnection();
        $virtualHost = $this->getTestVirtualHost();
        $connectionForTest = $this->getRabbitMqConnectionForTest();

        $manager = new RabbitMqTestManager($connectionConfig, $connectionForTest, $virtualHost);

        return $manager;
    }
}
