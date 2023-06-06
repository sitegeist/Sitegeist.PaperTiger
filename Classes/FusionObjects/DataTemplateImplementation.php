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

        return preg_replace_callback(
            '/{([a-z0-9\\-\\.]+)}/ium',
            function(array $matches) use ($data) {
                $value = Arrays::getValueByPath($data, $matches[1]);
                try {
                    if (is_string($value) || $value instanceof \Stringable) {
                        return htmlspecialchars(strip_tags((string) $value));
                    } elseif (is_array($value)) {
                        return htmlspecialchars(strip_tags( implode(', ', $value)));
                    } else {
                        return '';
                    }
                } catch (\Exception) {
                    return '';
                }
            },
        $template
        );
    }
}
