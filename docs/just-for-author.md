This document is just for author, dont't follow.
================================================

### commands

- kill all progress in ubuntu trusty
```
ps aux | grep resque_panel | grep -v grep | cut -c 9-15 | xargs kill -9
nohup php server/resque_panel.php > /tmp/resque_panel.log &
```
- RAM usage
```
ps -e -o 'pid,comm,args,pcpu,rsz,vsz,stime,user,uid'  | grep resque_panel | sort -nrk5
ps auxw --sort=rss | grep resque_panel | sort -nrk5
```


