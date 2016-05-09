# PHP-MVC
Basic MVC structure

Custom basic MVC structure gor GiG Test

Online Demo
-------------

Visit: http://vps249035.ovh.net/

For PhpMyadmin: http://vps249035.ovh.net:8080/ (root / toto1986)


Documentation
-------------

This basic MVC skeleton has been redesigned to the best coding practices, in order to speed up development "enjoying design pattern practices".

I reused my own mvc structure that I use to work with for this test.

According to the test, I used an orm to design the database structure, the ORM used is Doctrine.
I created these entities (User, Item, ItemType).

User and item have a ManyToMany Bidirectional relation.
Item and ItemType have a OneToOne relation.

In my test, you first add an item name then a slug is created to be sure to have only unique item names. This is ItemType.
The item entity stores the number of the item and his type (name).

Then, you assign a number and a type of the item to a user.
The user is an ArrayCollection due to ManyToMany relation, so it is easier to add and remove an item from this point.

There is no need to code an exchange method for items (or maybe it is a misunderstood from my side), because to add an item to a user you simply have to do this:
http://vps249035.ovh.net/user/item/add and send in post parameters "number = x" and "type = y". Y is the id of the itemType.

By this way you can exchange as you want and it is more scalable.

The main code is inside "Controller" folder. each line is documented, go and see it. All responses are made in JSON with Symfony Http Component.



Dependencies
-------------

Doctrine, Twig, Symfony Http Component.

I used Twig just to show the home page.




Requirements
------------

- Php >= 5.6 (Check that your php cli has also the same version)
- I used Nginx as Http server
 

Installation
------------

Let us run the project basically

	cd to/your/directory
	
	git clone https://github.com/edouardkombo/gig-test
	
	mkdir cache && chmod -R 777 cache
	
	mkdir logs && chmod -R 777 logs
	
	
Then, if you don't have composer installed please download composer.phar this way

	php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
	php -r "if (hash_file('SHA384', 'composer-setup.php') === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
	
	mv composer.phar /usr/local/bin/composer


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
	
	php vendor/bin/doctrine orm:schema-tool:create
	
	
Good, now we have to create a test user just to make our api test to work

	php cli-create-user.php email
	
	
Good, now go to your homepage and VOILA :)

	http://yoursite.com

	

This website has been developed on a linux Debian 8 environment. If you encounter any issue on Windows environment, please contact me:
email: edouard.kombo@gmail.com
Skype: edouard.kombo@live.fr


License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
