# ILIAS Plugin OnlyOffice
This plugin offers a way to connect an OnlyOffice document server to ILIAS. Users can upload files by creating an ILIAS repository object, which then can be collaboratively edited in OnlyOffice's online editors.

## Getting Started

### Installation

#### In [flux-ilias](https://github.com/fluxfw/flux-ilias)

*You need to adjust placeholders (Applies everywhere)*

Add in your `Dockerfile` based on [flux-ilias-ilias-base](https://github.com/fluxfw/flux-ilias-ilias-base)

```dockerfile
RUN /flux-ilias-ilias-base/bin/install-archive.sh https://github.com/fluxapps/OnlyOffice/archive/refs/heads/main.tar.gz /var/www/html/Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice
```

Extends your `docker-compose.yaml`

```yaml
services:
    onlyoffice:
        environment:
            - JWT_ENABLED=true
            - JWT_SECRET=...
        image: onlyoffice/documentserver:latest
        ports:
            - [%host_ip%:]8181:80
        volumes:
            - ./data/onlyoffice-cache:/var/lib/onlyoffice
            - ./data/onlyoffice-data:/var/www/onlyoffice/Data
            - ./data/onlyoffice-postgresql:/var/lib/postgresql
            - ./data/log/onlyoffice:/var/log/onlyoffice
```

Use `http[s]://%host%:8181` as `ONLYOFFICE root URL` in plugin configuration

##### Development

Unfortunately port forward to host's loopback network is not possible, because ILIAS and OnlyOffice communicates with the same `ONLYOFFICE root URL` configuration

The easist way is to create a specific docker network and assign a static IP, which can reached inside the containers and from your host

Extends your `docker-compose.yaml`

```yaml
services:
    database:
        networks:
            - ilias
    ilias:
        environment:
            [- ILIAS_CHATROOM_CLIENT_PROXY_CLIENT_URL=http://%chatroom_ip%:8080]
            - ILIAS_HTTP_PATH=http://%ilias_ip%
        networks:
            - ilias
    nginx:
        networks:
            ilias:
                ipv4_address: %ilias_ip%
#        ports:
#            - [%host_ip%:]80:80
    [cron:
        networks:
            - ilias]
    [ilserver:
        networks:
            - ilias]
    [chatroom:
        networks:
            ilias:
                ipv4_address: %chatroom_ip%
#        ports:
#            - [%host_ip%:]8080:8080]
    onlyoffice:
        networks:
            ilias:
                ipv4_address: %onlyoffice_ip%
#        ports:
#            - [%host_ip%:]8181:80
networks:
    ilias:
        ipam:
            config:
                - subnet: %ilias-subnet%
```

Use `http://%onlyoffice_ip%` as `ONLYOFFICE root URL` in plugin configuration

#### Other

##### Requirements

* ILIAS 6.x / 7.x
* PHP >= 7.1
* OnlyOffice Docs 7.0

##### Simple Docker Installation Guide for Developers

A simple installation guide for developers using docker can be found [here](doc/DOCKER_INSTALLATION.md).

##### Install OnlyOffice 
Install the desired edition of OnlyOffice Docs on your server. 
Note that the free community edition allows only 20 simultaneous connections.
Installation Guides can be found [here](https://helpcenter.onlyoffice.com/installation/docs-index.aspx)
We recommend using docker-compose for installation as specifications can easily be set within the .yml file.
We also recommend to set up HTTPS.

###### Security Configuration in OnlyOffice
(Note that this can only be done if you installed OnlyOffice docs using docker compose!)  
Open your OnlyOffice's docker-compose.yml file. 
In onlyoffice-documentserver.environment section, uncomment all variables starting with "JWT".
Set a safer password in JWT_SECRET variable.

##### Install OnlyOffice-Plugin
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject
cd Customizing/global/plugins/Services/Repository/RepositoryObject
git clone https://github.com/fluxapps/OnlyOffice.git OnlyOffice
```

Now you can install, update & activate the OnlyOffice plugin in your ILIAS installation.


###### Configure ILIAS 
Start at your ILIAS root directory.
Open .htaccess file. Add the following line:
``` code
Header set Access-Control-Allow-Origin "https://onlyoffic_docs.example"
```
Where "onlyoffice_docs.example" is the name of the server where OnlyOffice Docs is installed.
If you did not set up https, use "http://" instead.

Next you must navigate to the plugin's configuration form. 
Enter the root URL of your OnlyOffice Docs installation.
Enter the JWT-Secret which you specified in OnlyOffice's docker-compose.yml file.

##### Troubleshooting
1. If the OnlyOffice window opens up but you can't access the file make sure that your webserver user has read and write access to the plugin folder and the data folder where the files are saved.

## Authors

This is an OpenSource project by fluxlabs ag (https://fluxlabs.ch)

## License

This project is licensed under the GPL v3 License

## Contributing :purple_heart:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... create pull requests :fire:

## Adjustment suggestions / bug reporting :feet:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... ask us for a Service Level Agreement: support@fluxlabs.ch :kissing_heart:
3. ... Read and create issues
