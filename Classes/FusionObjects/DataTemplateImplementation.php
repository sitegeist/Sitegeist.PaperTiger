<?php
declare(strict_types=1);

namespace Sitegeist\PaperTiger\FusionObjects;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionResponse;
use Neos\Fusion\Form\Runtime\Domain\ActionInterface;
use Neos\Fusion\Form\Runtime\Domain\ActionResolver;
use Neos\Fusion\Form\Runtime\Domain\ConfigurableActionInterface;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class DataTemplateImplementation extends AbstractFusionObject
{
    public function getTemplate(): string
    {
        return (string) $this->fusionValue('template');
    }

    public function getData(): array
    {
        return $this->fusionValue('data');
    }
    public function evaluate()
    {
        $template = $this->getTemplate();
        $data = $this->getData();
        $search = array_map(function (string|int $key) {return '{' .  $key . '}';}, array_keys($data));
        $replace = array_map(function ($value) {return (is_string($value) || $value instanceof \Stringable) ? strip_tags((string) $value): '';}, array_values($data));
        return str_replace($search, $replace, $template);
    }
}
