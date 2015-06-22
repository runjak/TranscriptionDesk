#!/bin/sh
# Run a simple syntax check on all php files.
find -type f -regex .*php -exec php -l {} \;
