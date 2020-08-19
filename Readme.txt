to install the rest application run composer update from the root foldr to install dependancies

there are four api urls thier mapping is defined in .htaccess file

GET API:   class: =>StatusController.php
Based on the passed input, get an xml file, parse it and return its content by key name, or get the whole data as json object

api:
	a. statuses: query input: host, port, password
	b. status_byname: input: host, port, password, name
test:
/tests/StatusTest


POST API:   

accept json input and convert it to xml format then post it to another api to store as xml file,
the link to the xml storage api is defined in Config.App configuration folder, I implemented the xml storage webservice for testing purposes, and the files are stored under xml folder with the creation timestamp
api:
	a. converter_api	class: =>ConverterController.php
	b. xml_api		class: =>XmlStoreController.php 


test:
I: /tests/ConverterTest


session wrapper: 
a. EncryptedSessionHandler: store session as encrypted data using open_ssl 
b. NativeSessionHandler: store session as plain text file
a. Session.php: wrapper for php _SESSION, calls either EncryptedSessionHandler or NativeSessionHandler as session_set_save_handler based on the consturctor option.
