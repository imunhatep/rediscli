<?php
declare(strict_types=1);

namespace Rediscli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;

class RedisFlushAllCommand extends AbstractRedisCliCommand
{
    protected static $defaultName = 'redis:cli:flushall';

    protected function configure()
    {
        $this
            ->setDescription(
                'Clear redis cache with "flushall", using REDIS_HOST, REDIS_PORT environment variables'
            )
            ->addOption(
                'dot-env',
                null,
                InputOption::VALUE_OPTIONAL,
                'Path till dotEnv filename, with environment variables: REDIS_HOST, REDIS_PORT'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($envFilename = $input->getOption('dot-env')) {
            $this->processDotEnv($envFilename, $output);
        }

        $this->checkEnvVariables(['REDIS_HOST', 'REDIS_PORT']);

        /** @var \Redis $redis */
        $redis = $this->getRedis();

        $redis->flushDb();

        $output->writeln('flushall: OK');

        return 0;
    }

}
