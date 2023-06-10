# PHP Self-Setup Demo
### Developed by https://github.com/jiggey1/

# Prerequisites:
MySQL >= 5.0 ; PHP >= 8.0

# Installation:
1) Download this repo as a .zip
2) Set up a PHP Webserver environment, as well as a MySQL connection.
3) Visit the setup.php file in your web-browser, after starting a PHP server for this project.
4) You will now be prompted to run through an installer.

# Database Configuration:
When you set up a new Database configuration, the following data is required; Database Host, Database Port, Database Admin User, Database Admin Password.

These are then encrypted, and passed along to a FileManager. Your database connection is then kept securely, using the key on the ENCRYPTION.PHP CLASS (`api/crypt/Encryption.php`).

**You must create your own key to decrypt and encrypt the database information.** I chose a randomized string as my string, using [random.org](https://random.org). The database file is then stored in `[webserver_root]/files/database.php.`

# What is it
This demo is a basic PHP webserver, which can be used to set up a new environment for a website. For example, if you were distributing a web project, that needed a MySQL database already made, and has extra steps required to properly start it up. You can adapt this code, so it will configure to the users desired MySQL connection, create the database and tables needed, whilst also letting you set up an administrator account which can be used to properly configure the website, or access admin-only parts of the website.

# Bugs / Errors / Issues
Although this project won't be constantly updated, any issues or bugs found, report them here on GitHub, so I can fix it later for other users.

# Incomplete
This project is incomplete, and pretty messy in terms of code and layout.
Although the code is messy, the product is a functional demo. The website features no stylesheet or JS to run, as it is simple *a concept project*.

# Notice:
This code should not be put directly into a production environment! It does not use proper validation, error handling, or best practices since it was never meant to be a full-stack project. Instead, this project was created to show an example of how you would approach a self-setup webserver by using PHP, MySQL and HTML.

# Showcase:

[Demo Video Of Entire Process](https://github.com/jiggey1/PHP-Self-Setup/blob/main/assets/video/demo.mp4)

# Images:

config.json (Before Config)

![config.json before configuration](https://github.com/jiggey1/PHP-Self-Setup/blob/main/assets/images/before_config.png)

config.json (After Config)

![config.json after configuration](https://github.com/jiggey1/PHP-Self-Setup/blob/main/assets/images/after_config.png)

database.json (Using Example From Video)

![config.json after configuration](https://github.com/jiggey1/PHP-Self-Setup/blob/main/assets/images/example_db_file.png)

The database & tables created using the MySQL Connection:

![config.json after configuration](https://github.com/jiggey1/PHP-Self-Setup/blob/main/assets/images/database_created.png)

How the user data is stored in the database:

![config.json after configuration](https://github.com/jiggey1/PHP-Self-Setup/blob/main/assets/images/data_stored.png)
