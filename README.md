# Tactical Information Center
This project is an aditional website-software for the online game www.galaxy-network.de.
You coordinate the whole tactic of it of your alliance without the restrictions you' ve got in Galaxy-Network.
The project is written in PHP and uses Mysql.

This code base on the version 1.36.3
\<ticgeneration>.\<gn-rounde number>.\<minor release number>

## Running without Docker

To install this project on common web servers, you'll need PHP with MySQL set up, also you should have this repository cloned into any folder readable (and writeable) by the web server, e.g. `/usr/local/www/tic`. The example assumes that the web server runs as the `www` user:

    % cd /usr/local/www
    % git clone https://github.com/galaxy-network-tools/tic
    % chown -R www tic

Then, run the installer (to be found in `.../tic/installer/index.php`) inside your web browser and follow the instructions on your screen. Note that the installer is in German.

## Running with Docker

This project can be run as a Docker container:

    $ docker-compose down --remove-orphans && docker-compose build && docker-compose up -d

What does this do?

`docker-compose down --remove-orphans` will shutdown any existing Docker composition currently running, removing containers instead of just stopping them. The containers are stateless so this is no problem.

`docker-compose build` will make sure that the Docker image for the app container is always freshly built to make sure that code updates are installed.

Finally `docker-compose up -d` will bring the container(s) up and running, both the application container as well as a database container. They will be started in detached mode (`-d` flag), meaning they will run in the background.

If you want to clear the database and start from scratch (backup your Docker volume if you want to keep the data for later, `volume rm` cannot be undone unless backups are made):

    $ docker volume rm tic_mysql-data

In your browser go to `http://localhost` to see the TIC. Start the initial setup procedure by going to `http://localhost/installer` and follow the steps there.

### Deployment

Requirements:

You need to have a server running Docker ([HowTo](https://docs.docker.com/get-docker/)). `docker-compose` needs to be installed ([HowTo](https://docs.docker.com/compose/install/)).

For now this project is deployed via git checkout:

    $ cd <your-project-root-folder>
    $ git checkout https://github.com/galaxy-network-tools/tic.git
    $ cd tic

    # Then start docker-compose as mentioned above

### Updating

To get code updates, pull the master branch:

    $ cd <your-project-root-folder>
    $ git pull

    # Then run docker-compose as mentioned above

### Notes

docker-compose starts a MySQL server to hold the data. An initial user `root` will be automatically created with the password `root`. A database is automatically created called `tic`, to which `root` has full access as serveradmin.

**DISCLAIMER:** DO NOT USE THIS IN PRODUCTION!!!!
