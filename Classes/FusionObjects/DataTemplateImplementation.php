<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\FusionObjects;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionResponse;
use Neos\Fusion\Form\Runtime\Domain\ActionInterface;
use Neos\Fusion\Form\Runtime\Domain\ActionResolver;
use Neos\Fusion\Form\Runtime\Domain\ConfigurableActionInterface;
use Neos\Fusion\FusionObjects\AbstractFusionObject;
use Neos\Utility\Arrays;
use Psr\Http\Message\UploadedFileInterface;

class DataTemplateImplementation extends AbstractFusionObject
{
    public function getTemplate(): string
    {
        return (string) $this->fusionValue('template');
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->fusionValue('data');
    }

    public function getDateTimeFormat(): string
    {
        return $this->fusionValue('dateTimeFormat') ?? \DateTimeInterface::W3C;
    }

    public function evaluate()
    {
        $template = $this->getTemplate();
        $data = $this->getData();

        return preg_replace_callback(
            '/{([a-z0-9\\-\\.]+)}/ium',
            function (array $matches) use ($data) {
                $value = Arrays::getValueByPath($data, $matches[1]);
                return htmlspecialchars(strip_tags($this->stringify($value)));
            },
            $template
        );
    }

    public function stringify(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        } elseif (is_int($value) || is_float($value)) {
            return (string) $value;
        } elseif ($value instanceof \Stringable) {
            return $value->__toString();
        } elseif ($value instanceof \DateTimeInterface) {
            return $value->format($this->getDateTimeFormat());
        } elseif (is_array($value)) {
            return implode(', ', array_map(fn(mixed $item) => $this->stringify($item), $value));
        } else {
            return '';
        }
    }
}
