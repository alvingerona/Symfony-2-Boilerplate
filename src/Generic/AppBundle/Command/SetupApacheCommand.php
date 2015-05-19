<?php

namespace Generic\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Class SetupApacheCommand
 * @package Generic\AppBundleCommand
 */
class SetupApacheCommand extends ContainerAwareCommand
{
    /**
     * Configure the command configuration
     * @author Jim Ouwerkerk
     */
    protected function configure()
    {
        $this->setName('project:setup:apache')
            ->setDescription('Project Setup - This should be called as root')
            ->addArgument('project_name', InputArgument::REQUIRED,
                'Name of the project. This will be used to generate the domains in /etc/hosts')
            ->addOption('apache', null, InputArgument::OPTIONAL, 'Path to your sites-available')
            ->addOption('hosts', '/etc/hosts', InputArgument::OPTIONAL, 'Path to your hosts file');
    }

    /**
     * @author Jim Ouwerkerk
     * @param string      $command
     * @param null|string $custom_error
     * @return string
     */
    protected function runCommand($command, $custom_error = null)
    {
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            if (is_null($custom_error)) {
                throw new \RuntimeException($process->getErrorOutput());
            }
            throw new \RuntimeException($custom_error);
        } else {
            return $process->getOutput();
        }
    }

    /**
     * @author Jim Ouwerkerk
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runCommand('if [ "$(id -u)" != "0" ]; then exit 1; fi', 'This command should be called as root!');

        $name = $input->getArgument('project_name');
        $apache_path = $input->getOption('apache');
        $hosts_path = $input->getOption('hosts');
        if (is_null($apache_path)) {
            $apache_path = '/etc/apache2/sites-available/';
        }
        if (is_null($hosts_path)) {
            $hosts_path = '/etc/hosts';
        }
        $style = new OutputFormatterStyle('yellow', null, array('bold'));
        $output->getFormatter()->setStyle('feedback', $style);

        $style = new OutputFormatterStyle('white', 'green', array('bold'));
        $output->getFormatter()->setStyle('success', $style);

        //Setting up the apache config for the site
        $output->writeln("Setting up apache vhost");
        $command = $this->runCommand('sed -e "s/%project_name%/' . $name . '/g" "./resources/setup/httpd_vhosts.conf" >> "' . $apache_path . '' . $name . '.conf"');
        $output->writeln($command);
        $filesystem = new Filesystem();
        if (!$filesystem->exists('/data/projects/_ssl')) {
            $output->writeln("<info>Creating _ssl directory in '/data/projects/_ssl'</info>");
            $filesystem->mkdir('/data/projects/_ssl');
        }

        if (!$filesystem->exists('/data/projects/_ssl/' . $name)) {
            // Create SSL Certificates
            $output->writeln("<info>Creating SSL Certificates in '/data/projects/_ssl/" . $name . "/'</info>");
            $filesystem->mkdir('/data/projects/_ssl/' . $name);
            $output->writeln("Certificates will be created for: <feedback>localhost.[ENV]." . $name . ".com</feedback>");
            $output->writeln("");
            $enviroments = array("dev", "test", "behat", "stag", "benchmark", "acc", "prod");
            foreach ($enviroments as $enviroment) {
                $command = $this->runCommand(sprintf('openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 -subj "/C=NL/ST=Noord-Holland/L=Amsterdam/O=Youwe/CN=localhost.%1$s.%2$s.com" -keyout /data/projects/_ssl/%2$s/%1$s.%2$s.key -out /data/projects/_ssl/%2$s/%1$s.%2$s.crt',
                        $enviroment, $name));
                $output->writeln($command);
                $output->writeln('<info>Created Certificate for <feedback>localhost.' . $enviroment . '.' . $name . '.com</feedback></info>');
            }
        } else {
            // Skipping the certificates because they already exists
            $output->writeln("");
            $output->writeln("<feedback>Skipping creating SSL Certification: already exists</feedback>");
            $output->writeln("");
        }

        // Enable the site and restart apache
        $this->runCommand('a2ensite ' . $name . '.conf');
        $this->runCommand('service apache2 reload');

        // Add the urls to the /etc/hosts (or the given hosts file)
        $this->runCommand('sed -e "s/%project_name%/' . $name . '/g" "./resources/setup/hosts" >> "' . $hosts_path . '"');
        $output->writeln("");
        $output->writeln("<success>\r\n Success!\r\n</success>");
    }
}