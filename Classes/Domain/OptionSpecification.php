<?php
declare(strict_types=1);

namespace Sitegeist\PaperTiger\Domain;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class OptionSpecification implements \JsonSerializable
{
    public function __construct(
        public readonly string  $label,
        public readonly string $value,
        public readonly bool $isSelected
    ) {
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['label'],
            $array['value'],
            $array['isSelected']
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'isSelected' => $this->isSelected
        ];
    }
}
