<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\FusionObjects;

use Neos\Flow\Mvc\ActionResponse;
use Neos\Fusion\Form\Runtime\Domain\ActionInterface;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class ActionCollectionImplementation extends AbstractFusionObject implements ActionInterface
{
    /**
     * @return ActionInterface[]
     */
    protected function getActionMap(): array
    {
        $map = $this->fusionValue('actionMap') ?: [];
        return array_filter(
            $map,
            function ($item) {
                return $item instanceof ActionInterface;
            }
        );
    }


    public function evaluate()
    {
        return $this;
    }

    /**
     * @return ActionResponse|null
     */
    public function perform(): ?ActionResponse
    {
        $response = new ActionResponse();

        /**
         * @var ActionInterface[] $subActions
         */
        $subActions = $this->getActionMap();

        foreach ($subActions as $subAction) {
            $subActionResponse = $subAction->perform();
            if ($subActionResponse) {
                // content of multiple responses is concatenated
                if ($subActionResponse->getContent()) {
                    $mergedContent = $response->getContent() . $subActionResponse->getContent();
                    $subActionResponse->setContent($mergedContent);
                }
                // preserve non 200 status codes that would otherwise be overwritten
                if ($response->getStatusCode() !== 200 && $subActionResponse->getStatusCode() == 200) {
                    $subActionResponse->setStatusCode($response->getStatusCode());
                }
                $subActionResponse->mergeIntoParentResponse($response);
            }
        }
        return $response;
    }
}
