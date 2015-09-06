<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnit\RabbitMqTestUtils\Comparator;

use RabbitMQ\Management\Entity\Queue;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Class QueueComparator
 * @package OldTown\EventBuss\PhpUnit\RabbitMqTestUtils\Comparator
 */
class  QueueComparator extends Comparator
{
    /**
     * @var array
     */
    protected static $map = [
        'name' => 'name',
    ];

    /**
     * @inheritDoc
     */
    public function accepts($expected, $actual)
    {
        $flag = $actual instanceof Queue && is_array($expected);

        return $flag;
    }

    /**
     * @param array $expected
     * @param Queue $actual
     * @param float $delta
     * @param bool|false $canonicalize
     * @param bool|false $ignoreCase
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
