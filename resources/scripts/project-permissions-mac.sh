rm -rf app/cache/*
rm -rf app/logs/*
rm -rf app/spool/*

APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd' | grep -v root | head -1 | cut -d\  -f1`
sudo chmod +a "$APACHEUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs app/spool
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs app/spool

