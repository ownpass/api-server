#!/usr/bin/env bash

# Quit on error
set -e

# The current directory
CURRENT_DIR="$(dirname "$(readlink -f "$0")")"

# The directory to install to
INSTALL_DIR="$CURRENT_DIR/build/ownpass-api-server"

# Remove the directory if it exists
if [[ -d "$INSTALL_DIR" ]]; then
    rm -rf "$INSTALL_DIR"
fi

# Clone the repository
git clone https://github.com/ownpass/api-server.git $INSTALL_DIR

# Install dependencies
cd $INSTALL_DIR
composer install --no-interaction -o --no-dev

# Remove all files that are not relevant
rm -rf .git/
rm .[!.]*
rm *.md
rm *.json
rm *.lock
rm *.dist
rm *.xml

# Create an archive of the working directory.
cd "$CURRENT_DIR/build"
tar -cvzf "ownpass-api-server.tgz" "ownpass-api-server"
