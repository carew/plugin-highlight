<?php

namespace Carew\Plugin\Highlight;

use Carew\ExtensionInterface;
use Carew\Carew;
use Carew\Plugin\Highlight\Adapter\Geshi;

class HighlightExtension implements ExtensionInterface
{
    public function register(Carew $carew)
    {
        $geshi = new Geshi();
        $eventDispatcher = $carew->getEventDispatcher()->addSubscriber(new HighlightEventSubscriber($geshi));
    }
}
