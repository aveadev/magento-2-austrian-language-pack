<?php
/**
 * Aveadev Austrian German (de_AT) language pack
 * Standalone — ships its own complete i18n/de_AT.csv, no dependency
 * on the de_DE package or the language.xml <use> mechanism.
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::LANGUAGE,
    'aveadev_de_at',
    __DIR__
);
