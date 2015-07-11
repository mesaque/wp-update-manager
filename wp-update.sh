#!/bin/bash

basename=$(basename $0);
basedir=$( which $0 |  sed "s/\/$basename//g");
log=$basedir/status.log
rm $log &> /dev/null

#auto-update
pull_msg=$(cd $basedir/; git pull origin master)
echo $pull_msg | grep 'Already up-to-date'
[ "$?" != 0 ] && {
        $basedir/wp-update.sh
        exit 0
}

[ ! -d $basedir/bkp ] && mkdir $basedir/bkp

source $basedir/app.conf

###security backup### 
tar -czf $basedir/bkp/$(date +%F_%Hh%M).tgz --exclude=$WordPressPath/wp-content/uploads/* $tar_custom_excludes $WordPressPath 

######################################################
#################plugins payed########################
plugins_payed=$(ls $basedir/plugins_payed/)
for plugin in $plugins_payed; do
	if [ -d "${WordPressPath}/wp-content/plugins/${plugin}" ] ; then
		rm -rf ${WordPressPath}/wp-content/plugins/${plugin}
		cp -rf "$basedir/plugins_payed/${plugin}" "${WordPressPath}/wp-content/plugins/"
	fi
done
######################################################
cd $basedir/bkp
backup_files=$(ls --time-style=+%F_%Hh%M | xargs readlink -f)
count_files=$(echo "$backup_files" | wc -l)
[ "$count_files" -gt 3 ] && {
	count_to_remove=$( expr $count_files - 3 )	
	echo "$backup_files" | head -n${count_to_remove} | xargs rm  
}
cd  $WordPressPath
$wpcli core update &> $basedir/coreUpdate.log
$wpcli plugin update --all &> $basedir/pluginsUpdate.log
[ $? != 0 ] && {
	$basedir/slack_notification "{$web_site_url}The Update has NOT successful" '' $basedir 'error'
	exit 1
}
#handle log from WordPress Update
wpResult=$( cat $basedir/coreUpdate.log | sed 's/^[^U].*$//;s/Us.*$//' )
[ "$wpResult" ] && echo "WordPress is $wpResult" >> $log

#handle log from free plugins
pluginResult=$(cat $basedir/pluginsUpdate.log | sed '1,/Success/ d' | sed 's/^nam.*$//;s/^Suc.*$//')
linePluginCount=$(echo "$pluginResult" | wc -l);
[ $linePluginCount -gt 1 ] && echo "$pluginResult" >> $log 

if [ -s $log ]; then
	statusMsg=$(cat $log | tr '&' '&amp;' | tr '<' '&lt;' | tr '>' '&gt;' | tr ' ' _ | tr "\t" ___ )
	$basedir/slack_notification "{$web_site_url}Update successful" "$statusMsg" $basedir
else
	$basedir/slack_notification "{$web_site_url}Everything is up to date :P" '' $basedir
fi
