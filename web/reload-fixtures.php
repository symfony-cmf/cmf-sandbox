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

runCommand(__DIR__.'/../bin/console cache:clear -e=prod');
runCommand(__DIR__.'/../bin/console -v doctrine:phpcr:fixtures:load -e=prod');
