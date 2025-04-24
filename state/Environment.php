<?php

namespace State;

class Environment
{
    /** @var array<string, string> */
    private static array $env = [];

    private static bool $populated = false;

    /**
     * Ensure that the class has been populated.
     * @return void
     */
    private static function validate(): void
    {
        if (! Environment::$populated) {
            throw new \Error('Environment has not been sourced.  You can source it with Environment::source($filename)');
        }
    }

    /**
     * Parse the specified file path into the class.
     * @param string $filePath The name of the environment variable.
     * @return void
     */
    public static function parse(string $filePath): void
    {
        /** @var array<string, string>|false $result */
        $result = parse_ini_file($filePath);

        if ($result === false) {
            throw new \Error('Unable to parse the environment variables');
        }

        Environment::$env = $result;
        Environment::$populated = true;
    }

    /**
     * Get an environment variable.
     * @param string $key The name of the environment variable.
     * @return string The value of the environment variable.
     */
    public static function var(string $key): string
    {
        Environment::validate();

        return Environment::$env[$key];
    }

    /**
     * Return the environment as an array
     * @return array<string, string>
     */
    public static function asArray(): array
    {
        Environment::validate();

        return Environment::$env;
    }
}
