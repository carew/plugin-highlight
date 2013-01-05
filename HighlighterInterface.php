<?php

namespace Carew\Plugin\Highlight;

interface HighlighterInterface
{
    public function highlight($code, $language);

    public function support($language);
}
