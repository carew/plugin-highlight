<?php

namespace Carew\Plugin\Highlight;

use Carew\Event\Events;
use Carew\Document;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HighlightEventSubscriber implements EventSubscriberInterface
{
    private $highlighter;

    public function __construct(HighlighterInterface $highlighter)
    {
        $this->highlighter = $highlighter;
    }

    public function onDocument($event)
    {
        $document  = $event->getSubject();

        if (Document::TYPE_POST == $document->getType()) {
            $this->highlight($document);
        } elseif (Document::TYPE_PAGE == $document->getType()) {
            $this->highlight($document);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::DOCUMENT => array(
                array('onDocument', 256),
            ),
        );
    }

    private function highlight($document)
    {
        $body = $document->getBody();

        if (preg_match('/^(\s|\n)+$/', $body)) {
            return;
        }

        $body = preg_replace_callback('#<pre><code>(.*)</code></pre>#sU', array($this,'doHighlight'), $body);

        $document->setBody($body);
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
}
