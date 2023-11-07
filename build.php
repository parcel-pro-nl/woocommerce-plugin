<?php

// All template files that should be created.
// The `{{name}}` placeholder will be replaced by the target plugin name.
const TEMPLATE_FILES = [
    'bootstrap-template.php' => 'woocommerce-{{name}}.php',
    'readme.txt.hbs' => 'readme-{{name}}.txt',
];

// The possible plugin outputs.
const PLUGIN_TYPES = [
    'parcelpro',
    'shopsunited'
];

$pluginArg = null;

// Check if an argument is given, and if so, if it is valid.
if ($argc >= 2) {
    $pluginArg = $argv[1];
    if (!in_array($pluginArg, PLUGIN_TYPES, true)) {
        echo "Invalid argument: '$pluginArg'. Please use 'parcelpro', 'shopsunited', or no argument.\n";
        exit(1);
    }

    echo "Building plugin for $pluginArg\n";
} else {
    echo "Building plugin for " . implode(', ', PLUGIN_TYPES) . "\n";
}

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
    foreach (PLUGIN_TYPES as $type) {
        // If we have an argument, only build that plugin variant.
        if ($pluginArg && $type !== $pluginArg) {
            continue;
        }

        processTemplate($in, str_replace('{{name}}', $type, $out), $templateVars[$type]);
    }
}

// If we are building a single plugin type, ensure the files are in the right place for deployment.
if ($pluginArg) {
    rename("readme-$pluginArg.txt", 'readme.txt');
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

    // Change the header formatting.
    $changelog = str_replace('# Changelog', '== Changelog ==', $changelog);

    // Change the formatting of all entries.
    $changelog = preg_replace('/^## (.*)$/m', '= $1 =', $changelog);

    return $changelog;
}
