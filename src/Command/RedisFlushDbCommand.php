<?php
declare(strict_types=1);

namespace Rediscli\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RedisFlushDbCommand extends AbstractRedisCliCommand
{
    protected static $defaultName = 'redis:cli:flushdb';

    protected function configure()
    {
        $this
            ->setDescription(
                'Clear redis cache with "flushdb", using REDIS_HOST, REDIS_PORT, REDIS_DB environment variables'
            )
            ->addOption(
                'dot-env',
                null,
                InputOption::VALUE_OPTIONAL,
                'Path till dotEnv filename, with environment variables: REDIS_HOST, REDIS_PORT, REDIS_DB'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($envFilename = $input->getOption('dot-env')) {
            $this->processDotEnv($envFilename, $output);
        }

        $this->checkEnvVariables(['REDIS_HOST', 'REDIS_PORT', 'REDIS_DB']);

        /** @var \Redis $redis */
        $redis = $this->getRedis();

        $redis->flushDb();

        $output->writeln('flushdb: OK');

        return 0;
    }
}
