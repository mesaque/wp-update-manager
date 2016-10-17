#!/bin/bash

basename=$(basename $0);
basedir=$( which $0 |  sed "s/\/$basename//g");

[ "$CUSTOM_BASE_DIR" ] && {
	basedir=$CUSTOM_BASE_DIR
} 

log=$basedir/status.log

rm $log $basedir/plugins_payed.log $basedir/coreUpdate.log $basedir/coreUpdate_db.log $basedir/themesUpdate.log $basedir/pluginsUpdate.log &> /dev/null

#try auto-update
[ "$1" ] || {
	cd $basedir/; git pull origin master
	$basedir/wp-update.sh 'Already up-to-date'
        exit 0 
}

[ ! -d $basedir/bkp ] && mkdir $basedir/bkp
[ ! -d $basedir/tmp ] && mkdir $basedir/tmp

source $basedir/app.conf
[ "$update_options" ] || {
        echo "variable update_options not find in app.conf";
        exit;
}


###handle backup###
bkp_file=$basedir/bkp/$(date +%F_%Hh%M).tgz
tar -czf $bkp_file --exclude=$WordPressPath/wp-content/uploads/* $tar_custom_excludes $WordPressPath &> /dev/null

### manage backup
cd $basedir/bkp
ls --time-style=+%F_%Hh%M *tgz >> listfiles
have_list=$(wc -c listfiles | cut -d ' ' -f1)
backup_files=$(while read i ; do echo `pwd`/$i; done < listfiles)
rm listfiles
[ $have_list == 0 ] || {
	count_files=$(echo  "$backup_files" | wc -l)
	[ "$count_files" -gt 1 ] && {
	        count_to_remove=$( expr $count_files - 1 )
	        echo "$backup_files" | head -n${count_to_remove} | xargs rm
	}
}

###handle with free plugins
plugins_dir=$($basedir/lib/helper.php $basedir $WordPressPath 2 )
all_plugins=$(ls  $plugins_dir)

old_IFS=$IFS
IFS=$'\n'
for plugin in ${all_plugins}; do
	info=$($basedir/lib/helper.php $basedir $WordPressPath 3 $plugin) 	
	[ "$info" ] && {
		link=$(echo $info | cut -d';' -f2)
		version_newest=$(echo $info | cut -d';' -f1)
		version=$($basedir/check-version.sh $plugins_dir $plugin)
		updated=$(expr ${version_newest} \> ${version})
		[ $updated != 1 ] && continue;
		wget $link  --directory-prefix=$basedir/tmp/ &> /dev/null
		unzip -o -x $basedir/tmp/${link#*/*/*/*/} -d $plugins_dir/ &> /dev/null
		rm  $basedir/tmp/${link#*/*/*/*/}
		
	}
done
IFS=$old_IFS

###Handle with plugins payed###
plugins_payed=$(ls $basedir/plugins_payed/)
[ "$plugins_payed" ] && {
	for plugin in $plugins_payed; do
		if [ -d "${WordPressPath}/wp-content/plugins/${plugin}" ] ; then
			vsP=$($basedir/check-version.sh $plugins_dir $plugin)
			vsE=$($basedir/check-version.sh $basedir/plugins_payed/ $plugin)
			checkVersion=$(expr "$vsE" \> "$vsP")
			[ "$checkVersion" == 0 ] && continue;
			cp -rf "$basedir/plugins_payed/${plugin}" "${WordPressPath}/wp-content/plugins/"
		fi
	done
}

###begin update script by wp-cli
[ $? == 0 ] && {
	$basedir/lib/helper.php $basedir $WordPressPath 1
	[ $? == 0 ] && cp -rf  $basedir/wordpress/* $WordPressPath 
}

status_page=$(curl -I $web_site_url/ |  head -n1 | cut -d' ' -f2);
echo $status_page
is_ok=$(expr $status_page \> 400)
[ $is_ok == 1 ] && {
	tar -xzf $bkp_file -C /
	$basedir/slack_notification "{$web_site_url}The update result in a ERROR, we restore a backup" '' $basedir 'error'
	exit 1
}

