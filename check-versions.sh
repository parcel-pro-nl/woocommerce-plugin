#!/usr/bin/env bash
set -euo pipefail

if ( ! command -v jq > /dev/null )
then
  echo 'The jq command is required for this script.'
  exit 1
fi

allMatch=1
function checkVersionMatch() {
    echo "- $1: $2"
    if [ ! "$composerVersion" = "$2" ]
    then
      allMatch=0
    fi
}

echo "Detected versions:"

composerVersion=$(jq -r .version 'composer.json')
checkVersionMatch 'composer.json' "$composerVersion"

checkVersionMatch 'changelog.md' "$(sed -nE 's/^## (.*) -.*$/\1/p' 'changelog.md' | head -n 1)"
checkVersionMatch 'includes/class-parcelpro.php' "$(sed -nE "s/^.*this->version = '(.*)';$/\1/p" 'includes/class-parcelpro.php')"

if [ ! "$allMatch" = 1 ]
then
  echo 'Not all versions match.'
  exit 1
else
  echo 'All version match!'
fi
