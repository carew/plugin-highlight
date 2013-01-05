Highlight plugin for [Carew](http://github.com/lyrixx/Carew)
============================================================

Installation
------------

Install it with composer:

```
composer require carew/plugin-highlight:dev-master
```

Then configure `config.yml`

```
engine:
    extensions:
        - Carew\Plugin\Highlight\HighlightExtension
```

Usage
-----

Add the langage to the beginning of a code block

```
Some text

    php
    <?php
    // Some code php
```

This plugins rely on [geshi](http://qbnz.com/highlighter/).
You can get a full list on supported language on its site.

**Note**: This plugin also support `twig`
