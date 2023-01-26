<?php
declare(strict_types=1);

namespace Sitegeist\PaperTiger\FusionObjects;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionResponse;
use Neos\Fusion\Form\Runtime\Domain\ActionInterface;
use Neos\Fusion\Form\Runtime\Domain\ActionResolver;
use Neos\Fusion\Form\Runtime\Domain\ConfigurableActionInterface;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class ActionImplementation extends AbstractFusionObject implements ActionInterface
{
    /**
     * @var ActionResolver
     * @Flow\Inject
     */
    protected $actionResolver;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array|null
     */
    protected $options;

    public function evaluate()
    {
        $this->type = $this->fusionValue('type');
        $this->options = $this->fusionValue('options');
        return $this;
    }

    /**
     * @return ActionResponse|null
     */
    public function perform(): ?ActionResponse
    {
        $action = $this->actionResolver->createAction($this->type);
        if ($action instanceof ConfigurableActionInterface && $this->options) {
            $action = $action->withOptions($this->options);
        }
        return $action->perform();
    }
}
