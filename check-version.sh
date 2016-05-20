#!/bin/bash

plugins_dir=$1
plugin=$2

[ "$plugins_dir" ] || exit 1
[ "$plugin" ] || exit 1

plugin_filtered=$(echo ${plugin} | tr '-' '_')
if [ -s $plugins_dir/$plugin/${plugin_filtered}.php ]; then
	version=$(cat $plugins_dir/$plugin/${plugin_filtered}.php | grep --perl-regexp 'Version: *[0-9].*' |  sed -r 's#.*Version: ?*##g')
else
	if [ -s $plugins_dir/$plugin/${plugin}.php ]; then
		version=$(cat $plugins_dir/$plugin/${plugin}.php | grep --perl-regexp 'Version: *[0-9].*' | sed -r 's#.*Version: ?*##g')
	else
		version=$(find  $plugins_dir/$plugin/ -maxdepth 1 -name '*php' -exec grep 'Version:' {} \; | grep --perl-regexp 'Version: *[0-9].*' | sed -r 's#.*Version: ?*##g')
	fi
fi

echo $version
exit 0
