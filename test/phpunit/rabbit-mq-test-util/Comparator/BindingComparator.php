<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\RabbitMqTestUtils\Comparator;

use Doctrine\Common\Collections\Collection;
use RabbitMQ\Management\Entity\Binding;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Class BindingComparator
 * @package OldTown\EventBus\PhpUnit\RabbitMqTestUtils\Comparator
 */
class  BindingComparator extends Comparator
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
        $flag = false;
        if ($actual instanceof Collection  && is_array($expected)) {
            $flag = true;
            foreach ($actual as $candidate) {
                if (!$candidate instanceof Binding) {
                    $flag = false;
                }
            }
        }
        return $flag;
    }

    /**
     * @param array $expected
     * @param Collection|Binding[] $actual
     * @param float $delta
     * @param bool|false $canonicalize
     * @param bool|false $ignoreCase
     *
     * @throws \RuntimeException
     */
    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false)
    {
        $countExpected = count($expected);
        $countActual = count($actual);
        if ($countExpected !== $countActual) {
            throw new ComparisonFailure(
                $countExpected,
                $countActual,
                $countExpected,
                $countActual,
                false,
                sprintf(
                    'Не совпадает колличество ключей. Ожидается %s , вместо %s',
                    $this->exporter->export($countExpected),
                    $this->exporter->export($countActual)

                )
            );
        }

        $actualNormalize = [];
        foreach ($actual as $actualBinding) {
            if (array_key_exists($actualBinding->routing_key, $actualNormalize)) {
                $errMsg = sprintf('Дубликат связи. Связь с ключем %s уже существует', $actualBinding->routing_key);
                throw new \RuntimeException($errMsg);
            }
            $actualNormalize[$actualBinding->routing_key] = $actualBinding;
        }

        foreach ($expected as $expectedBindingKey) {
            if (!array_key_exists($expectedBindingKey, $actualNormalize)) {
                throw new ComparisonFailure(
                    $expectedBindingKey,
                    '',
                    // no diff is required
                    $expectedBindingKey,
                    '',
                    false,
                    sprintf(
                        'Отсутствует связь с именем %s',
                        $this->exporter->export($expectedBindingKey)
                    )
                );
            }
        }
    }
}
