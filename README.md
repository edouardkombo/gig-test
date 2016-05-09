# PHP-MVC
Basic MVC structure

This is the basic structure I generally use when I'm starting with PHP Projects. It allows the developer to easily create new functions and sections in the system while making use of pretty URLs throughout.
The code is fully commented and explained, with index.php making the initial call to the bootstrap. The bootstrap is what handles the parsing of the URL and makes the requests as needed to the controllers and functions.


Documentation
-------------

This basic MVC skeleton has been redesigned to the best coding practices, in order to speed up development "enjoying design pattern practices".

Please forgive in advance cause I played with your MVC skeleton, it was fun :)


Dependencies
-------------

Instead of reinventing the MVC wheel, I used some of the best open source dependencies like:
- Doctrine (very useful, especially when you have to deal with relations, ManyToOne, OneToMany...)
- Twig (your original MVC was mixing php code with html, it's not a good pratice)
- Tracy  to see all php errors, warnings and exceptions in a more precise way
- PHP-Router from Danny Van Kooten, that I modified by inheritance inside this project.
- and more...

To respect the "S" (Single Responsibility) from SOLID principle, each important task has been assigned to dependencies.
You will find inside "Lib" folder, some helpers and classes I've written.

I prefered yml format for configuration (syntax is easier to read and understand).
Acl, routes, database and based config have been done in yml format.

I have also coded a very basic ACL system that avoid some unauthorized users to access specific urls... (hoping that the code is well documented).


The database scheme is included inside "Entity folder". It's a mapping for Doctrine.

Bootstrap free templates have been used for this test for both login, admin and mail side.


Requirements
------------

- Php >= 5.6 (Check that your php cli has also the same version)
- I use Nginx as Http server, but feel free to use Apache
 

Installation
------------

Let us run the project basically

	cd to/your/directory
	
	git clone https://edouardkombo@bitbucket.org/edouardkombo/blexrtest.git
	
	mkdir cache && chmod -R 777 cache
	
	mkdir logs && chmod -R 777 logs
	
	
Then, if you don't have composer installed please download composer.phar this way

	php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
	php -r "if (hash_file('SHA384', 'composer-setup.php') === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"


It is recommended to put composer as a global variable in your binary (Linux) or Path environment (Windows)
Install all dependencies

	composer install
	

Now, open config file and put in your database informations

	nano config/database.yml
	
	
Create the database through your command line or phpMyAdmin 
	
	
Ensure, that your website is in dev mode (It will handle cache, useful for developing, especially with Twig)
Also, configure your smtp credentials to send emails
	
	nano config/config.yml
	
	
Good, now let's create the database 
	
	php vendor/bin/doctrine orm:schema-tool:create (For linux)
	php vendor/doctrine/orm/bin/doctrine orm:schema-tool:create (For windows)
	
	
Good, now we can create the first admin user in the database (delete this file once done)

	php cli-create-admin.php lastname firstname email password
	
	
Good, now go to your homepage and VOILA :)

	http://yoursite.com

	

This website has been developed on a linux Debian 8 environment. If you encounter any issue on Windows environment, please contact me:
email: edouard.kombo@gmail.com
Skype: edouard.kombo@live.fr


License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
