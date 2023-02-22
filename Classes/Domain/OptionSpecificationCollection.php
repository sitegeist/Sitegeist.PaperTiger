<?php
declare(strict_types=1);

namespace Sitegeist\PaperTiger\Domain;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class OptionSpecificationCollection implements \IteratorAggregate, \JsonSerializable, \Countable
{
    public readonly array $options;
    public function __construct(OptionSpecification ...$options)
    {
        $this->options = $options;
    }

    public static function fromArray(array $array): self
    {
        return new self(...array_map(function (array $item): OptionSpecification {
            return OptionSpecification::fromArray($item);
        }, $array));
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->options);
    }

    public function count(): int
    {
        return count($this->options);
    }


    public function jsonSerialize(): array
    {
        return $this->options;
    }
}
