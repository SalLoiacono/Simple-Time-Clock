# Simple-Time-Clock
A simple time clock I made using PHP and MYSQL on a Debian server to automate the rather archaic process of having employees punch in with physical cards and then having to manually calculate the hours each week. The input device for the time clock is a 7" android tablet, running the browser fullscreen.

#Time Clock login page.
![main](http://salloiacono.com/wp-content/uploads/2016/02/time-clock-main.png "Time Clock Main Screen")

#Employee punch in/out page.
![employeescreen](http://salloiacono.com/wp-content/uploads/2016/02/time-clock-punch-in.png "Time Clock Punch In/Out Screen")



#The MYSQL database contains two tables:

1. Employees (ID, Name, EmployeeID)

2. TimeClock (ID, Timestamp, Date, Name, MON-IN, MON-L-OUT, MON-L-IN, MON-OUT, TUE-IN, TUE-L-OUT, TUE-L-IN, TUE-OUT, WED-IN, WED-L-OUT, WED-L-IN, WED-OUT, THU-IN, THU-L-OUT, THU-L-IN, THU-OUT, FRI-IN, FRI-L-OUT, FRI-L-IN, FRI-OUT, SAT-IN, SAT-L-OUT, SAT-L-IN, SAT-OUT, SUN-IN, SUN-L-OUT, SUN-L-IN, SUN-OUT)



#To create a fresh set of records for each new payweek (in this case, on every Thursday morning at 3am), edit crontab for user 'www-data':

user@hostname:~$ sudo crontab -e -u www-data

0 3 * * 4 python /var/www/scripts/create-new-payweek.py
