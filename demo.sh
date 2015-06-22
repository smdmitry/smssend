#!/bin/sh

php /var/spool/sms/demo.php $1 $2 &> /dev/null &
#sleep 5 && /var/spool/sms/eventhandler.sh RECEIVED demo &> /dev/null &