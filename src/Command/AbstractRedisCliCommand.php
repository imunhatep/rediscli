<?php
declare(strict_types=1);

namespace Rediscli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;

class AbstractRedisCliCommand extends Command
{
    protected function getRootPath(): string
    {
        return getcwd();
    }

    protected function getRedis(): \Redis
    {
        $redis = new \Redis;
        $redis->connect($this->getEnv('REDIS_HOST'), (int)$this->getEnv('REDIS_PORT'), 10);

        if ($this->getEnv('REDIS_DB')) {
            $redis->select((int) $this->getEnv('REDIS_DB'));
        }

        return $redis;
    }

    protected function checkEnvVariables(array $variables): void
    {
        foreach ($variables as $name) {
            if ($this->getEnv($name) === null) {
                throw new \InvalidArgumentException(sprintf('%s environment is not provided', $name));
            }
        }
    }

    protected function processDotEnv(string $envFilename, OutputInterface $output)
    {
        if (empty($envFilename)) {
            throw new \InvalidArgumentException('Empty dot-env argument value received');
        }

        // is provided dotenv filepath is absolute
        $envFilepath = ($envFilename[0] === DIRECTORY_SEPARATOR)
            ? $envFilename
            : $this->getRootPath() . DIRECTORY_SEPARATOR . $envFilename;

        if (!is_readable($envFilepath)) {
            throw new \InvalidArgumentException('Cannot read provided dotEnv file at: ' . $envFilepath);
        }

        $output->writeln('Loading env file from: ' . $envFilename);

        (new Dotenv(true))->overload($envFilepath);
    }

    private function getEnv(string $name): ?string
    {
        return $_ENV[$name] ?? $_SERVER[$name] ?? (getenv($name) === false ? null : getenv($name));
    }
}
