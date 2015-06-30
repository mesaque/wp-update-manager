#!/bin/bash

basedir=$(dirname $0);

#auto-update
pull_msg=$(cd $basedir/; git pull origin master)
echo $pull_msg | grep 'Already up-to-date'
[ "$?" != 0 ] && {
        $basedir/wp-update.sh
        exit 0
}

[ ! -d $basedir/bkp ] && mkdir $basedir/bkp

tar -czf $basedir/bkp/$(date +%F_%Hh%M).tgz --exclude=$WordPressPath/wp-content/uploads/* $tar_custom_excludes $WordPressPath 
