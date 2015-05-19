Behat Tests
==================================

1. Running the tests
----------------------------------
Download the ChromeWebDriver (for your operating system) from

    http://chromedriver.storage.googleapis.com/index.html

Download Selenium from
http://selenium-release.storage.googleapis.com/index.html

Run selenium with the following command but with your paths

    java -jar /path/to/selenium-server -Dwebdriver.chrome.driver="/path/to/chromedriver"

Example:

    java -jar ~/Downloads/selenium-server-standalone-2.42.2.jar -Dwebdriver.chrome.driver="~/Downloads/chromedriver"


Run the behat command

    app/console behat:run
    
Default the test will run in progress format, but if you want output, run the following command:

    app/console behat:run --format="progress,pretty"

2. Troubleshooting behat tests
-----------------------------------

When you run into some not working tests because of a local situation so make sure you have done:

```
    php composer.phar install
    php app/console propel:build
    php app/console cache:clear --env=behat
```

Sometimes generated propel object should be removed because they are removed from the scheme.<br>
This doesn't happen automatically so you might have to remove the Model/om and Model/map directories from the bundles.

This command will remove all Model/om and Model/map directories:

    cd ../src && find . -type d -name map -prune -exec rm -rf {} \; && find . -type d -name om -prune -exec rm -rf {} \; cd ..
    