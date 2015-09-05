<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest;


/**
 * Interface RabbitMqTestCaseInterface
 * @package OldTown\EventBuss\PhpUnitTest
 */
interface  RabbitMqTestCaseInterface
{

    /**
     * @return RabbitMqTestManager
     */
    public function getRabbitMqTestManager();

    /**
     * @param RabbitMqTestManager $rabbitMqTestManager
     * @return $this
     */
    public function setRabbitMqTestManager( RabbitMqTestManager $rabbitMqTestManager);

    /**
     * Определяет был ли установлен виртуальных хост на котором происходит тестирование
     *
     * @return bool
     */
    public function hasTestVirtualHost();

    /**
     * Определяет был ли установлен менеджер для работы с кроликом
     *
     * @return bool
     */
    public function hasRabbitMqTestManager();

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getTestVirtualHost();

    /**
     * @param string $testVirtualHost
     * @return $this
     */
    public function setTestVirtualHost($testVirtualHost);
}
