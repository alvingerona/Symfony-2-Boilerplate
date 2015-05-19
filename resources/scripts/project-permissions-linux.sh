APACHEUSER=`ps aux | grep -E '[a]pache2|[h]ttpd' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:$APACHEUSER:rwX -m u:`whoami`:rwX app/cache app/logs app/spool
sudo setfacl -dR -m u:$APACHEUSER:rwX -m u:`whoami`:rwX app/cache app/logs app/spool

