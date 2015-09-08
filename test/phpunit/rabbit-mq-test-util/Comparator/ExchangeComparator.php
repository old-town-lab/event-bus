<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\RabbitMqTestUtils\Comparator;

use RabbitMQ\Management\Entity\Exchange;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Class ExchangeComparator
 * @package OldTown\EventBus\PhpUnit\RabbitMqTestUtils\Comparator
 */
class  ExchangeComparator extends Comparator
{
    /**
     * @var array
     */
    protected static $map = [
        'name' => 'name',
        'type' => 'type',
        'durable' => 'durable'
    ];

    /**
     * @inheritDoc
     */
    public function accepts($expected, $actual)
    {
        $flag = $actual instanceof Exchange && is_array($expected);

        return $flag;
    }

    /**
     * @param array $expected
     * @param Exchange $actual
     * @param float $delta
     * @param bool|false $canonicalize
     * @param bool|false $ignoreCase
     *
     * @throws \SebastianBergmann\Comparator\ComparisonFailure
     * @throws \InvalidArgumentException
     */
    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false)
    {
        foreach ($expected as $key => $expectedValue) {
            if (!array_key_exists($key, static::$map)) {
                $errMsg = sprintf('Неподдерживаемый параметр %s', $key);
                throw new \InvalidArgumentException($errMsg);
            }
            $property = static::$map[$key];
            $actualValue = $actual->{$property};


            if ($expectedValue !== $actualValue) {
                $actualValueString = (string)$actualValue;
                throw new ComparisonFailure(
                    $expectedValue,
                    $actualValue,
                    // no diff is required
                    $expectedValue,
                    $actualValueString,
                    false,
                    sprintf(
                        'Обменник не соответствует запрашиваемым критериям. Свойство %s имеет значение %s, вместо %s',
                        $key,
                        $this->exporter->export($actualValue),
                        $this->exporter->export($expectedValue)
                    )
                );
            }
        }
    }
}
