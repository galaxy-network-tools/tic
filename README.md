# Tactical Information Center
This project is an aditional website-software for the online game www.galaxy-network.de.
You coordinate the whole tactic of it of your alliance without the restrictions you' ve got in Galaxy-Network.
The project is written in PHP and uses Mysql.

This code base on the version 1.36.3
\<ticgeneration>.\<gn-rounde number>.\<minor release number>

## Running with Docker

This project can be run as a Docker container:

    $ docker-compose down --remove-orphans && docker-compose build && docker-compose up

If you want to clear the database and start from scratch:

    $ docker volume rm tic_mysql-data

In your browser go to `http://localhost` to see the TIC. Start the initial setup procedure by going to `http://localhost/installer` and follow the steps there.

### Notes

docker-compose starts a MySQL server to hold the data. An initial user `root` will be automatically created with the password `root`. A database is automatically created called `tic`, to which `root` has full access as serveradmin.

**DISCLAIMER:** DO NOT USE THIS IN PRODUCTION!!!!
