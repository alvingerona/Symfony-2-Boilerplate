Local Installation
=================================

1) Installing the Project
----------------------------------

##Git
Create a fork from the main project repository

Clone the fork to your local machine

    git clone [FORK URL] /data/projects/[PROJECT NAME]

##Composer

Get the latest composer:

    ./resources/scripts/getlatestcomposer.sh

This command installs all of the necessary vendor libraries - including Symfony itself - into the vendor/ directory.

    php composer.phar install

The installer will ask for some database information. If you made a mistake, you can always change it in app/config/parameters.yml

    #app/config/parameters.yml
    # This file is auto-generated during the composer install
    parameters:
        database_driver: pdo_mysql
        database_host: 127.0.0.1
        database_port: 3306
        database_name: [DATABASE_NAME]
        database_user: [DATABASE_PASSWORD]
        database_password: [DATABASE_PASSWORD]
        test_database_name: [TEST_DATABASE_NAME]
        mailer_transport: smtp
        mailer_host: 127.0.0.1
        mailer_user: null
        mailer_password: null
        mailer_dev_email:  ~
        locale: en
        secret: ThisTokenIsNotSoSecretChangeIt

Add the vhost of your project in apache2. This is easy with the following script: Change project name your project name.

    sudo app/console project:setup:apache [PROJECT NAME]

Optional options for the command:

    --apache [APACHE_PATH] 
    --hosts [HOSTS_PATH])

##Build the database models

[UNCOMMENTED THE COMMANDS THAT ARE USED IN THIS PROJECT]

<!---
<b>Propel</b>
Run the following commands:

    php app/console propel:acl:init
    php app/console propel:sql:build
    php app/console propel:sql:insert --force
    php app/console propel:model:build
    php app/console propel:fixtures:load
    
-->
<!---
<b>Doctrine</b>

    php app/console doctrine:schema:update --force
    php app/console doctrine:fixtures:load

-->
<!---
<b>Sonata</b>

    php app/console sonata:page:update-core-routes --site=all
    php app/console sonata:page:create-snapshots --site=all

Additionally you might need to reload the data for the database

- Updating core routes

    app/console sonata:page:update-core-routes --site=all

- Creating snapshot

    app/console sonata:page:create-snapshots --site=all

- Extending sonata bundles
    
    app/console sonata:easy-extends:generate SonataPageBundle

-->

##Permissions

Permissions for linux:

    ./resources/scripts/project-permissions-linux.sh

Permissions for Mac:

    ./resources/scripts/project-permissions-mac.sh
