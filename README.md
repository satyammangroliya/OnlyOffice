# ILIAS Plugin OnlyOffice
This plugin offers a way to connect an OnlyOffice document server to ILIAS. Users can upload files by creating an ILIAS repository object, which then can be collaboratively edited in OnlyOffice's online editors.

## Getting Started

### Requirements

* ILIAS 6.x / 7.x

### Install OnlyOffice 
Install the desired edition of OnlyOffice Docs on your server. 
Note that the free community edition allows only 20 simultaneous connections.
Installation Guides can be found [here](https://helpcenter.onlyoffice.com/installation/docs-index.aspx)
We recommend using docker-compose for installation as specifications can easily be set within the .yml file.
We also recommend to set up HTTPS.

#### Security Configuration in OnlyOffice
(Note that this can only be done if you installed OnlyOffice docs using docker compose!)  
Open your OnlyOffice's docker-compose.yml file. 
In onlyoffice-documentserver.environment section, uncomment all variables starting with "JWT".
Set a safer password in JWT_SECRET variable.

### Install OnlyOffice-Plugin
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject
cd Customizing/global/plugins/Services/Repository/RepositoryObject
git clone https://github.com/fluxapps/OnlyOffice.git OnlyOffice
```

Now you can install, update & activate the OnlyOffice plugin in your ILIAS installation.


#### Configure ILIAS 
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
