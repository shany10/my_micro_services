<?php

declare(strict_types=1);

use Monolog\Logger;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use App\Application\Settings\SettingsInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function (ContainerInterface $c) {

            $settings = $c->get(SettingsInterface::class);

            $dbSettings = $settings->get('db');
            $host = $dbSettings['host'];
            $dbname = $dbSettings['dbname'];
            $user = $dbSettings['user'];
            $password = $dbSettings['password'];
            $charset = $dbSettings['charset'];
            $flags = $dbSettings['flags'];
            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
            return new PDO($dsn, $user, $password);
        },
        EntityManager::class => function (ContainerInterface $c): EntityManager {
            $settings = $c->get(SettingsInterface::class);
            
            $config = Setup::createAnnotationMetadataConfiguration(
                $settings->get('doctrine')['metadata_dirs'],
                $settings->get('doctrine')['dev_mode']
            );
        
            $config->setMetadataDriverImpl(
                new AnnotationDriver(
                    new AnnotationReader,
                    $settings->get('doctrine')['metadata_dirs']
                )
            );
        
            $config->setMetadataCacheImpl(
                new Cache(
                    $settings->get('doctrine')['cache_dir']
                )
            );
        
            return EntityManager::create(
                $settings->get('doctrine')['connection'],
                $config
            );
        }
    ]);
};
