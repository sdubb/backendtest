#!/bin/bash

# Update package list and install Apache
apt-get update
apt-get install -y apache2

# Enable Apache mods
a2enmod rewrite

# Start Apache in the background
service apache2 start

# Ensure PHP runs with Apache
echo "Starting PHP with Apache"
