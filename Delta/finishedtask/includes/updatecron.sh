#!bin/bash
#as root pre-req
#string="*/5 * * * * bash /var/www/html/includes/updatecron.sh"
#(crontab -l 2>/dev/null; echo "$string")| crontab -

 stringnew=$(cat /var/www/html/includes/mysqldump.txt | awk '/mysqldump/{print}')
 stringold=$(crontab -l -u root | awk '/mysqldump/{print}')
 if [ "$stringnew" != "$stringold" ];
 then
 #delete old
    crontab -l -u root | grep -v 'mysqldump' | crontab -u root -  
    crontab -l -u meivenkatkumar | grep -v 'mysqldump' | crontab -u meivenkatkumar - 
    (crontab -l 2>/dev/null; echo "$stringnew")| crontab -
    sudo service cron restart
 fi

#overall server response time
echo "" >> "/var/www/html/analysis/datalog"
curl -s -w '%{time_namelookup} %{time_connect} %{time_pretransfer} %{time_starttransfer} %{time_total} '$(date +"%H-%M-%S")'' -o /dev/null http://localhost:80 >> "/var/www/html/analysis/datalog"
echo "" >> "/var/www/html/analysis/datalog"
#page-wise response time
cat /var/log/apache2/access.log | grep 'index.php' | awk '{$4=substr($4,14);print$10,$4}' > "/var/www/html/analysis/indexresponsetime"
cat /var/log/apache2/access.log | grep 'details.php' | awk '{$4=substr($4,14);print$10,$4}' > "/var/www/html/analysis/detailsresponsetime"
