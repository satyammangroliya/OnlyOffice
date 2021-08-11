This is an OpenSource project by studer + raimann ag, CH-Burgdorf (https://studer-raimann.ch)

## Description
See in [doc/DESCRIPTION.md](./doc/DESCRIPTION.md)

## Documentation
See in [doc/DOCUMENTATION.md](./doc/DOCUMENTATION.md)

## Installation

### Install OnlyOffice 
Install the desired edition of OnlyOffice Docs on your server. 
Note that the free community edition does not provide all features and only allows 20 simultaneous connections.
We recommend using docker-compose for installation as specifications can easily be set within the .yml file.  
Installation Guides can be found at https://helpcenter.onlyoffice.com/installation/docs-index.aspx


### Install OnlyOffice-Plugin
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject
cd Customizing/global/plugins/Services/Repository/RepositoryObject
git clone https://github.com/studer-raimann/OnlyOffice.git OnlyOffice
```

#### Adapt ILIAS 
Start at your ILIAS root directory.
Open .htaccess file. Add the following line:
``` code
Header set Access-Control-Allow-Origin "http://example.com"
```
Where "example.com" is the name of the server where OnlyOffice Docs is installed.

Setze globale Variablen!


Open 
Update, activate and config the plugin in the ILIAS Plugin Administration

### Requirements
* ILIAS 5.4
* PHP >=7.0

### Adjustment suggestions
* External users can report suggestions and bugs at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLONLYOFFICE
* Adjustment suggestions by pull requests via github
* Customer of studer + raimann ag: 
	* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLONLYOFFICE
	* Bug reports under https://jira.studer-raimann.ch/projects/PLONLYOFFICE

### Legal Notice
The icon for this plugin is licensed under the [Creative Commons Attribution-Share Alike 4.0 International](https://creativecommons.org/licenses/by-sa/4.0/deed.en) license.  
Author: Alisa Bulatova | Source: https://www.onlyoffice.com/press-downloads.aspx

### ILIAS Plugin SLA
Wir lieben und leben die Philosophie von Open Source Software! Die meisten unserer Entwicklungen, welche wir im Kundenauftrag oder in Eigenleistung entwickeln, stellen wir öffentlich allen Interessierten kostenlos unter https://github.com/studer-raimann zur Verfügung.

Setzen Sie eines unserer Plugins professionell ein? Sichern Sie sich mittels SLA die termingerechte Verfügbarkeit dieses Plugins auch für die kommenden ILIAS Versionen. Informieren Sie sich hierzu unter https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Bitte beachten Sie, dass wir nur Institutionen, welche ein SLA abschliessen Unterstützung und Release-Pflege garantieren.
