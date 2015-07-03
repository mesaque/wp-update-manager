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

source $basedir/app.conf

#security backup 
tar -czf $basedir/bkp/$(date +%F_%Hh%M).tgz --exclude=$WordPressPath/wp-content/uploads/* $tar_custom_excludes $WordPressPath 
cd $basedir/bkp
backup_files=$(ls --time-style=+%F_%Hh%M | xargs readlink -f)
count_files=$(echo "$backup_files" | wc -l)
[ "$count_files" -gt 3 ] && {
	count_to_remove=$( expr $count_files - 3 )	
	echo "$backup_files" | head -n${count_to_remove} | xargs rm  
}
cd  $WordPressPath
$wpcli core update
$wpcli plugin update --all

if [ $? = 0 ]; then
	$basedir/slack_notification "{$web_site_url}Update successful" '' $basedir
else
	$basedir/slack_notification "{$web_site_url}The Update has NOT successful" '' $basedir 'error'
fi
