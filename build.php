<?php

// All template files that should be created.
// The `{{name}}` placeholder will be replaced by the target plugin name.
const TEMPLATE_FILES = [
    'bootstrap-template.php' => 'woocommerce-{{name}}.php',
    'readme.txt.hbs' => 'readme-{{name}}.txt',
];

// Load the template vars from the file.
$templateVars = json_decode(file_get_contents('template-vars.json'), true);

// Read the current version from composer.json and add it to the template vars.
$composerJson = json_decode(file_get_contents('composer.json'), true);
$templateVars['parcelpro']['version'] = $composerJson['version'];
$templateVars['shopsunited']['version'] = $composerJson['version'];

// Read the changelog and add it to the template vars.
$changelog = getAndProcessChangelog();
$templateVars['parcelpro']['changelog'] = $changelog;
$templateVars['shopsunited']['changelog'] = $changelog;

// Process all template files.
foreach (TEMPLATE_FILES as $in => $out) {
    // Process the Parcel Pro template file.
    processTemplate($in, str_replace('{{name}}', 'parcelpro', $out), $templateVars['parcelpro']);

    // Process the Shops United template file.
    processTemplate($in, str_replace('{{name}}', 'shopsunited', $out), $templateVars['shopsunited']);
}

function processTemplate($templateFile, $outputFile, $context)
{
    // Read the template file.
    $result = file_get_contents($templateFile);

    // Get all placeholders that are used in the template.
    preg_match_all('/{{(.*?)}}/', $result, $regexOut);
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

function getAndProcessChangelog()
{
    $changelog = file_get_contents('changelog.md');

    $changelog = str_replace('# Changelog', '== Changelog ==', $changelog);

    // TODO: Replace entries with proper formatting

    return $changelog;
}
