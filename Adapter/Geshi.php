<?php

namespace Carew\Plugin\Highlight\Adapter;

use Carew\Plugin\Highlight\HighlighterInterface;
use GeSHi as BaseGeshi;

class Geshi implements HighlighterInterface
{
    private $geshi;
    private $languages;

    private $matching = array(
        'yml'  => 'yaml',
        'html' => 'html5',
    );

    public function __construct(BaseGeshi $geshi = null)
    {
        $this->geshi = $geshi ?: new BaseGeshi();
        $this->languages = $this->geshi->get_supported_languages();
    }

    public function highlight($code, $language)
    {
        $language = $this->findMaching($language);

        $this->geshi->set_source($code);
        $this->geshi->set_language($language, true);

        return $this->geshi->parse_code();
    }

    public function support($language)
    {
        $language = $this->findMaching($language);

        return in_array($language, $this->languages);
    }

    private function findMaching($language)
    {
        if (array_key_exists($language, $this->matching)) {
            return $this->matching[$language];
        }

        return $language;
    }

}
