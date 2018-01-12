<?php

function outputNice($output)
{
    if (is_array($output)) {
        foreach ($output as $line) {
            printf("<pre><code>%s</code>", $line);
        }
    } else {
        printf("<pre><code>%s</code>", $output);
    }
}

$commandFile = __DIR__.'/../bin/reloadFixtures.sh';
if (!file_exists($commandFile)) {
    outputNice('File not found at: '.$commandFile);
}

$returnValue = null;
$output = [];
exec($commandFile.' '.__DIR__.'/../', $output, $returnValue);

if (0 !== (int) $returnValue) {
    outputNice('Errors on Execution:');
    outputNice($output);
    exit($returnValue);
} else {
    outputNice($output);
    outputNice('Success');
}
