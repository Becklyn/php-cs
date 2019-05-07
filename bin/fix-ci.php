<?php

// require autoloader
if (is_file($autoloader = dirname(__DIR__) . "/vendor/autoload.php"))
{
    require_once $autoloader;
}
else if (is_file($autoloader = dirname(__DIR__, 3) . "/autoload.php"))
{
    require_once $autoloader;
}

use Symfony\Component\Yaml\Yaml;

$ci = [
    getcwd() . "/.circleci/config.yml" => "circleci",
    getcwd() . "/.travis.yml" => "travis",
];

$onlyFix = !in_array('--check', $_SERVER["argv"], true);
$tasks = null;

foreach ($ci as $path => $tool)
{
    if (is_file($path))
    {
        switch ($tool)
        {
            case "circleci":
                $tasks = parseCircleTasks(Yaml::parseFile($path), $onlyFix);
                break 2;

            case "travis":
                $tasks = parseTravisTasks(Yaml::parseFile($path), $onlyFix);
                break 2;

            default:
                error("Unknown tool: {$tool}");
                exit(1);
        }
    }
}

if (null === $tasks)
{
    error("No CI tool found.");
    exit(1);
}

runTasks($tasks);
exit(0);

/**
 * Parses the task list out of a CircleCI file
 *
 * @param array $content
 * @param bool  $onlyFix
 *
 * @return string[]
 */
function parseCircleTasks (array $content, bool $onlyFix) : array
{
    $tasks = [];

    foreach ($content["jobs"] as $job)
    {
        foreach ($job["steps"] as $step)
        {
            if (!isset($step["run"]))
            {
                continue;
            }

            if (is_string($step["run"]))
            {
                $tasks[] = parseTask($step["run"], $onlyFix);
            }
            elseif (isset($step["run"]["command"]))
            {
                $tasks[] = parseTask($step["run"]["command"], $onlyFix);
            }
        }
    }

    return array_filter($tasks);
}

/**
 * Parses the task list out of a Travis file
 *
 * @param array $content
 *
 * @param bool  $onlyFix
 *
 * @return string[]
 */
function parseTravisTasks (array $content, bool $onlyFix) : array
{
    $callback = function (string $task) use ($onlyFix)
    {
        return parseTask($task, $onlyFix);
    };

    return concat(
        array_map($callback, $content["before_script"] ?? []),
        array_map($callback, $content["script"] ?? [])
    );
}

/**
 * @param string $task
 * @param bool   $onlyFix
 *
 * @return string|null
 */
function parseTask (string $task, bool $onlyFix) : ?string
{
    // If these regex match, the command will be ignored
    $excludes = [
        '~composer install~',
        '~composer.*?require~',
        '~mkdir~',
        '~npm.*? build~',
    ];

    if ($onlyFix)
    {
        $excludes[] = '~simple-phpunit~';
        $excludes[] = '~npm audit~';
        $excludes[] = '~npm test~';
    }

    // Mapping from pattern (that must match) to the string that should be removed from the cli call
    $removeParameters = [
        '~composer normalize~' => [
            '--dry-run',
        ],
        '~phpstan~' => [
            '--no-interaction',
            '--no-progress',
        ],
        '~prettier-package-json~' => [
            '--list-different',
        ],
        '~vendor/bin/php-cs-fixer~' => [
            '--dry-run',
            '--no-interaction',
        ],
    ];

    // filter out excluded tasks
    foreach ($excludes as $exclude)
    {
        if (preg_match($exclude, $task))
        {
            return null;
        }
    }

    // clean up parameters
    foreach ($removeParameters as $pattern => $removals)
    {
        if (preg_match($pattern, $task))
        {
            return trim(str_replace($removals, '', $task));
        }
    }

    return $task;
}

/**
 * @param string[] $tasks
 */
function runTasks (array $tasks) : void
{
    $isSuccess = true;
    foreach ($tasks as $task)
    {
        write("=====");
        write(" RUN: {$task} ");
        write("=====");
        write("");
        passthru($task, $return);
        write("");
        write("");

        if (0 !== $return)
        {
            $isSuccess = false;
        }
    }

    write("All done.");
    write($isSuccess ? "SUCCESS" : "ERROR");
}

/**
 * Concatenates the lists
 *
 * @param mixed ...$lists
 *
 * @return array
 */
function concat (...$lists)
{
    $total = [];

    foreach ($lists as $list)
    {
        foreach ($list as $entry)
        {
            if (null !== $entry)
            {
                $total[] = $entry;
            }
        }
    }

    return $total;
}

/**
 * @param string $message
 */
function error (?string $message) : void
{
    write("ERROR: {$message}");
}

/**
 * @param string $message
 */
function write (?string $message) : void
{
    echo $message . PHP_EOL;
}
