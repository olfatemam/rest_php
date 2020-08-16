to install the rest application run composer update from the root foldr to install dependancies

there are four api urls thier mapping is defined in .htaccess file

Assignment 1(GET API):   class: =>StatusController.php
Based on the passed input, get an xml file, parse it and return its content by key name, or get the whole data as json object

api:
	a. statuses: query input: host, port, password
	b. status_byname: input: host, port, password, name
test:
/tests/StatusTest


Assignment 3(POST API):   
I.
accept json input and convert it to xml format then post it to another api to store as xml file,
the link to the xml storage api is defined in Config.App configuration folder, I implemented the xml storage webservice for testing purposes, and the files are stored under xml folder with the creation timestamp
api:
	a. converter_api	class: =>ConverterController.php
	b. xml_api		class: =>XmlStoreController.php 

II. I did not work with nosql before and I need time and a better understanding of the auditing requirement, like where to get the user id's and names to record them 

test:
I: /tests/ConverterTest


Assignment 2(session wrapper): 
a. EncryptedSessionHandler: store session as encrypted data using open_ssl 
b. NativeSessionHandler: store session as plain text file
a. Session.php: wrapper for php _SESSION, calls either EncryptedSessionHandler or NativeSessionHandler as session_set_save_handler based on the consturctor option.