#!/usr/bin/env php
<?php

const BOOTSTRAP_TEMPLATE = 'bootstrap.php.hbs';
const TEMPLATE_VARS = 'template-vars.json';

// Load the template vars from the file.
$templateVars = json_decode(file_get_contents(TEMPLATE_VARS), true);

// Read the current version from composer.json.
$composerJson = json_decode(file_get_contents('composer.json'), true);
$composerVersion = $composerJson['version'];

// Add the version number to the template vars.
$templateVars['parcelpro']['version'] = $composerVersion;
$templateVars['shopsunited']['version'] = $composerVersion;

$pp = processTemplate(BOOTSTRAP_TEMPLATE, 'woocommerce-parcelpro.php', $templateVars['parcelpro']);

$su = processTemplate(BOOTSTRAP_TEMPLATE, 'woocommerce-shopsunited.php', $templateVars['shopsunited']);

function processTemplate($templateFile, $outputFile, $context)
{
    // Read the template file.
    $result = file_get_contents($templateFile);

    // Get all placeholders that are used in the template.
    preg_match_all('/{{(.*)}}/', $result, $regexOut);
    $keys = array_unique($regexOut[1]);

    // Replace all key placeholders with the value from the context.
    foreach ($keys as $key) {
        if (!isset($context[$key])) {
            throw new Exception('Undefined template value: "' . $key . '"');
        }

        $value = $context[$key];
        $result = str_replace('{{' . $key . '}}', $value, $result);
    }

    // Write the generated output file.
    file_put_contents($outputFile, $result);
}
