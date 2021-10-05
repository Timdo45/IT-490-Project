#!/bin/bash

if systemctl status mysql | grep "active" > /dev/null
then
	echo Starting MySQL
	sudo service mysql start 
	echo Checking status...
fi
