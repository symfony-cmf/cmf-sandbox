<pre>
<?php

function runCommand($command)
{
    $output = $return_var = null;
    echo "Running: $command\n";
    exec($command, $output, $return_var);

    if (empty($output) || !is_array($output)) {
        echo 'Fixtures could not be loaded: '.var_export($return_var, true);
        exit(1);
    }

    echo "Output:\n";
    foreach ($output as $line) {
        echo $line."\n";
    }
}

runCommand('rm -rf app/cache/prod');
runCommand(__DIR__.'/../bin/console --env=prod doctrine:phpcr:init:dbal --drop --force');
runCommand(__DIR__.'/../bin/console --env=prod doctrine:phpcr:repository:init');
runCommand(__DIR__.'/../bin/console -v --env=prod doctrine:phpcr:fixtures:load -n');
runCommand(__DIR__.'/../bin/console --env=prod cache:warmup -n --no-debug');
