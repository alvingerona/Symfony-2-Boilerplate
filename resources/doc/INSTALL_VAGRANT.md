Vagrant
=================================

Requirements
----------------------------------

1. [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
2. [Vagrant](https://www.vagrantup.com/downloads)
3. NFS for performance boost:
    - For mac this is already available
    - For linux install using command $ `sudo apt-get install nfs-common nfs-kernel-server`
    - For Windows install using command $ `vagrant plugin install vagrant-winnfsd`

**Note for Windows users**
<br/>- Install software to a path which contains no spaces. for example: `C:\tools\`
<br/>- Add the VirtualBox installation directory to your PATH variables.
<br/>- Add the Git bin directory to your PATH variables.

1. Clone Repository
----------------------------------
1. [Create a fork]([FORK URL]) from the main project repository.
2. Clone the fork to your local machine:

    `git clone [FORK URL] /data/projects/[PROJECT NAME]`

2. Install Project
----------------------------------
Start the virtual machine. First time usage will install and configure all dependencies. This can take up to 10 minutes.
<br/>From your project directory enter the command:

$ `vagrant up`
    
3. Run Project
----------------------------------
1. Start the virtual machine if you haven't already.

$ `vagrant up`
    
2. After the command has finished the project will be available on [DEV DOMAIN NAME]([DEV DOMAIN URL]).

3. Changes you make to this project will be synced to the virtual machine.

4. MySQL database can be accessed trough credentials:

```
    host:       [DEV DOMAIN NAME]
    database:   [DB NAME]
    user:       [DB USER]
    pass:       [DB PASS]
```

4. Stopping the virtual machine
----------------------------------
- Stop the virtual machine using the command:

$ `vagrant halt`
    
- Destroy the virtual machine, for example if you want to clear space or don't often work on this project.

$ `vagrant destroy`

5. Updating the project
----------------------------------
- Login to the machine

$ `vagrant ssh`
    
- Navigate to the project directory

$ `cd /data/projects/[PROJECT NAME]/`

- Run the following commands:

```
    php composer.phar self-update
    php composer.phar install -o
    php app/console propel:build
    php app/console propel:migration:migrate
```

- Additionally you might need to reload the data for the database

```
    php app/console propel:fixtures:load
    php app/console faker:populate
```