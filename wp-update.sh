#!/bin/bash

basename=$(basename $0);
basedir=$( which $0 |  sed "s/\/$basename//g");
log=$basedir/status.log
rm $log &> /dev/null
rm $basedir/plugins_payed.log &> /dev/null

#try auto-update
[ "$1" ] || {
	cd $basedir/; git pull origin master
	$basedir/wp-update.sh 'Already up-to-date'
        exit 0 
}

[ ! -d $basedir/bkp ] && mkdir $basedir/bkp

source $basedir/app.conf

###handle backup### 
tar -czf $basedir/bkp/$(date +%F_%Hh%M).tgz --exclude=$WordPressPath/wp-content/uploads/* $tar_custom_excludes $WordPressPath &> /dev/null

### manage backup 
cd $basedir/bkp
ls --time-style=+%F_%Hh%M *tgz >> listfiles
backup_files=$( while read i ; do echo `pwd`/$i; done < listfiles)
rm listfiles
count_files=$(echo "$backup_files" | wc -l)
[ "$count_files" -gt 2 ] && {
        count_to_remove=$( expr $count_files - 2 )
        echo "$backup_files" | head -n${count_to_remove} | xargs rm
}

###uptade plugins payed###
plugins_payed=$(ls $basedir/plugins_payed/)
plugins_list=$(cd $WordPressPath; php $basedir/lib/wp-cli.phar plugin list)
[ "$plugins_payed" ] && {
	for plugin in $plugins_payed; do
		if [ -d "${WordPressPath}/wp-content/plugins/${plugin}" ] ; then
			vsP=$( echo "$plugins_list" | grep "${plugin}\s" | sed 's#\s#_#g' | cut -d'_' -f4)
			vsE=$(find $basedir/plugins_payed/${plugin} -maxdepth 1 | grep '.php' | xargs grep 'Version: ' | sed 's#.*/##' | sed 's#.$##' | sed 's# #_#g' | cut -d'_' -f2)
			checkVersion=$(expr "$vsE" \< "$vsP")
			equalVersion=$(expr "$vsE" = "$vsP")
			[ "$equalVersion" == 1 ] && continue;
			[ "$checkVersion" == 1 ] && continue;
			rm -rf ${WordPressPath}/wp-content/plugins/${plugin}
			cp -rf "$basedir/plugins_payed/${plugin}" "${WordPressPath}/wp-content/plugins/"
			echo "${plugin} ${vsP}   ${vsE} Updated" >> $basedir/plugins_payed.log
		fi
	done
}
###begin update script by wp-cli
cd  $WordPressPath
php $basedir/lib/wp-cli.phar core update &> $basedir/coreUpdate.log
php $basedir/lib/wp-cli.phar core update-db &> $basedir/coreUpdate_db.log
php $basedir/lib/wp-cli.phar theme update --all &> $basedir/themesUpdate.log
php $basedir/lib/wp-cli.phar plugin update --all &> $basedir/pluginsUpdate.log
[ $? != 0 ] && {
	$basedir/slack_notification "{$web_site_url}The Update has NOT successful" '' $basedir 'error'
	exit 1
}
#handle log from WordPress Update
wpResult=$( cat $basedir/coreUpdate.log | sed 's/^[^U].*$//;s/Us.*$//' )
[ "$wpResult" ] && echo "WordPress is $wpResult" >> $log

#handle log from free plugins
pluginResult=$(cat $basedir/pluginsUpdate.log | sed '1,/Success/ d' | sed 's/^nam.*$//;s/^Suc.*$//;/Ativando Modo de Manutenção.*$/,$d' )
linePluginCount=$(echo "$pluginResult" | wc -l);
[ $linePluginCount -gt 1 ] && echo "$pluginResult" >> $log 

#handle log from payed plugins
plugins_payed_version=$(cat $basedir/plugins_payed.log)
[ -s $basedir/plugins_payed.log ] && echo "$plugins_payed_version" >> $log

if [ -s $log ]; then
	statusMsg=$(cat $log | sed 's#&#\&amp;#g' | sed 's#<#\&lt;#g'  | sed 's#>#\&gt;#g' | sed 's# #\&nbsp;#g' | sed 's#\t#\&nbsp;\&nbsp;#g' | sed 's#:#\&colon;#g' )
	$basedir/slack_notification "{$web_site_url}Update successful" "$statusMsg" $basedir
else
	$basedir/slack_notification "{$web_site_url}Everything is up to date :P" '' $basedir
fi
