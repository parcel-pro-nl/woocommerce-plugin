#!/usr/bin/env php
<?php

const BOOTSTRAP_TEMPLATE = 'bootstrap.php.hbs';
const CONFIG_PARCELPRO = 'parcelpro.json';
const CONFIG_SHOPSUNITED = 'shopsunited.json';

$templateContents = file_get_contents(BOOTSTRAP_TEMPLATE);

$pp = processTemplateWithConfigFile($templateContents, CONFIG_PARCELPRO);
file_put_contents('woocommerce-parcelpro.php', $pp);

$su = processTemplateWithConfigFile($templateContents, CONFIG_SHOPSUNITED);
file_put_contents('woocommerce-shopsunited.php', $su);

function processTemplateWithConfigFile($template, $configFile)
{
    // Get all placeholders that are used in the template.
    preg_match_all('/{{(.*)}}/', $template, $regexOut);
    $keys = array_unique($regexOut[1]);

    $config = json_decode(file_get_contents($configFile), true);
    $result = $template;

    foreach ($keys as $key) {
        $value = $config[$key];
        $result = str_replace('{{' . $key . '}}', $value, $result);
    }

    return $result;
}
