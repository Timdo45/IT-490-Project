#!/bin/bash

if systemctl status rabbitmq-server | grep "active" > /dev/null
then
	echo Starting RabbitMQ
	sudo service rabbitmq-server start 
	echo Checking server status...
	sudo systemctl status rabbitmq-server
fi
