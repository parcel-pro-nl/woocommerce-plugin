#!/usr/bin/env php
<?php

const BOOTSTRAP_TEMPLATE = 'bootstrap.php.hbs';
const CONFIG_PARCELPRO = 'parcelpro.json';
const CONFIG_SHOPSUNITED = 'shopsunited.json';

$composerJson = json_decode(file_get_contents('composer.json'), true);
$composerVersion = $composerJson['version'];

$templateContents = file_get_contents(BOOTSTRAP_TEMPLATE);

$ppContext = json_decode(file_get_contents(CONFIG_PARCELPRO), true);
$ppContext['version'] = $composerVersion;
$pp = processTemplate($templateContents, $ppContext);
file_put_contents('woocommerce-parcelpro.php', $pp);

$suContext = json_decode(file_get_contents(CONFIG_SHOPSUNITED), true);
$suContext['version'] = $composerVersion;
$su = processTemplate($templateContents, $suContext);
file_put_contents('woocommerce-shopsunited.php', $su);

function processTemplate($template, $context)
{
    // Get all placeholders that are used in the template.
    preg_match_all('/{{(.*)}}/', $template, $regexOut);
    $keys = array_unique($regexOut[1]);

    $result = $template;

    foreach ($keys as $key) {
        if (!isset($context[$key])) {
            throw new Exception('Undefined template value: "' . $key . '"');
        }

        $value = $context[$key];
        $result = str_replace('{{' . $key . '}}', $value, $result);
    }

    return $result;
}
