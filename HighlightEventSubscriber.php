<?php

namespace Carew\Plugin\Highlight;

use Carew\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HighlightEventSubscriber implements EventSubscriberInterface
{
    private $highlighter;

    public function __construct(HighlighterInterface $highlighter)
    {
        $this->highlighter = $highlighter;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::PAGE => array(
                array('highlight', static::getPriority()),
            ),
            Events::POST => array(
                array('highlight', static::getPriority()),
            )
        );
    }

    public function highlight($event)
    {
        $subject = $event->getSubject();
        $body = $subject->getBody();

        if (preg_match('/^(\s|\n)+$/', $body)) {
            return;
        }

        $body = preg_replace_callback('#<pre><code>(.*)</code></pre>#sU', array($this,'doHighlight'), $body);

        $subject->setBody($body);
    }

    private function doHighlight($matches)
    {
        $code = $matches[1];
        list($language) = preg_split("/[\s,]+/", $code, 2);

        if (!$this->highlighter->support($language)) {
            return $matches[0];
        }

        $code = substr_replace($code, '', 0, strlen($language));
        $code = html_entity_decode($code);
        $code = trim($code);

        return $this->highlighter->highlight($code, $language);
    }

    public static function getPriority()
    {
        return 125;
    }
}
