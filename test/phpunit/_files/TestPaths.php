<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\TestData;

class TestPaths
{
    /**
     * Пути до директории с тестовыми сообщениями
     * @return string
     */
    public static function getPathToTestMessageDir()
    {
        return  __DIR__ . '/Messages/';
    }
    /**
     * Пути до директории с тестовыми данными
     * @return string
     */
    public static function getPathToTestDataDir()
    {
        return  __DIR__;
    }

}
