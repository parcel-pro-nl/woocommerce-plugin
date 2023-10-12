#!/usr/bin/env bash
set -euo pipefail

if ( ! command -v jq > /dev/null )
then
  echo 'The jq command is required for this script.'
  exit 1
fi

allMatch=1
function checkMatch() {
    if [ ! "$composerVersion" = "$1" ]
    then
      allMatch=0
    fi
}

echo "Detected versions:"

composerVersion=$(cat 'composer.json' | jq -r .version)
echo "- composer.json: $composerVersion"
checkMatch "$composerVersion"

readmeVersion=$(cat 'readme.txt' | sed -nE 's/^Stable tag: (.*)$/\1/p')
echo "- readme.txt: $readmeVersion"
checkMatch "$readmeVersion"

entrypointVersion=$(cat 'woocommerce-parcelpro.php' | sed -nE 's/^ \* Version: +(.*)$/\1/p')
echo "- woocommerce-parcelpro.php: $entrypointVersion"
checkMatch "$entrypointVersion"

classPpVersion=$(cat 'includes/class-parcelpro.php' | sed -nE "s/^.*this->version = '(.*)';$/\1/p")
echo "- includes/class-parcelpro.php: $classPpVersion"
checkMatch "$classPpVersion"

if [ ! "$allMatch" = 1 ]
then
  echo 'Not all versions match.'
  exit 1
else
  echo 'All version match!'
fi
