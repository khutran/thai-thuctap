filename="$1"
while read -r line
do
  virtualhost=`echo $line | awk '{print $11}'`
  echo $virtualhost #>> /media/thai/Learning/VMMS/Docker/vmms-cloud/logs/nginx/$virtualhost/access_log

done < "$filename"