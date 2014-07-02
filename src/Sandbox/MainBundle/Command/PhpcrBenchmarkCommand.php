<?php

/*
 * This file is part of the Sandbox\MainBundle
 *
 * (c) Lukas Kahwe Smith <smith@pooteeweet.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sandbox\MainBundle\Command;

use PHPCR\NodeInterface;
use PHPCR\PropertyType;
use PHPCR\Query\QueryInterface;
use PHPCR\SessionInterface;

use Jackalope\Query\NodeIterator;
use PHPCR\Util\NodeHelper;
use Symfony\Component\Stopwatch\Stopwatch;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PhpcrBenchmarkCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('sandbox:phpcr:benchmark')
            ->setDescription('Benchmark PHPCR.')
            ->addOption('session', null, InputOption::VALUE_OPTIONAL, 'The session to use for this command')
            ->setHelp(<<<EOT
The <info>sandbox:phpcr:benchmark</info> command benchmarks PHPCR.
EOT
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var SessionInterface $session */
        $session = $this->getContainer()->get('doctrine_phpcr')->getConnection($input->getOption('session'));

        $rootPath = '/benchmark';
        if ($session->nodeExists($rootPath)) {
            $root = $session->getNode($rootPath);
            $root->remove();
        }

        $session->save();
        $session->refresh(false);

        $count = 100;
        $sections = 100;
        $nodeName = $count/2;
        $path = $rootPath.'/1/'.$nodeName;
        $stopWatch = new Stopwatch();

        $qm = $session->getWorkspace()->getQueryManager();
        $sql = "SELECT * FROM [nt:unstructured] WHERE count = '$nodeName'";
        $query = $qm->createQuery($sql, QueryInterface::JCR_SQL2);
        $sql2 = "SELECT * FROM [nt:unstructured] WHERE count = '$nodeName' AND ISDESCENDANTNODE('$rootPath/1')";
        $query2 = $qm->createQuery($sql2, QueryInterface::JCR_SQL2);

        $total = 0;
        for ($i = 1; $i < $sections; $i++) {
            $root = NodeHelper::createPath($session, "$rootPath/$i");

            $stopWatch->start("insert nodes");
            $this->insertNodes($session, $root, $count);
            $event = $stopWatch->stop("insert nodes");

            $total+= $count;
            $output->writeln("Inserting $count nodes (total $total) took '" . $event->getDuration(). "' ms.");

            $session->refresh(false);

            $stopWatch->start("get a node");
            $node = $session->getNode($path);
            $event = $stopWatch->stop("get a node");
            $output->writeln("Getting a node by path took '" . $event->getDuration(). "' ms.");
            $this->validateNode($node, $path);

            $stopWatch->start("search a node");
            $result = $query->execute();
            $event = $stopWatch->stop("search a node");
            $output->writeln("Searching a node by property took '" . $event->getDuration(). "' ms.");

            /** @var NodeIterator $nodes */
            $node = $result->getNodes()->current();
            $this->validateNode($node, $path);

            $stopWatch->start("search a node in a subpath");
            $result = $query2->execute();
            $event = $stopWatch->stop("search a node in a subpath");
            $output->writeln("Searching a node by property in a subpath took '" . $event->getDuration(). "' ms.");

            /** @var NodeIterator $nodes */
            $node = $result->getNodes()->current();
            $this->validateNode($node, $path);
        }

        return 0;
    }

    private function validateNode(NodeInterface $node = null, $path)
    {
        if (!$node) {
            throw new \RuntimeException('Benchmark failing to read correct data: no node found');
        }

        if ($node->getPath() != $path) {
            throw new \RuntimeException("Benchmark failing to read correct data: '$path' does not match '".$node->getPath()."'");
        }
    }

    private function insertNodes(SessionInterface $session, NodeInterface $root, $count)
    {
        for ($i = 1; $i <= $count; $i++) {
            $node = $root->addNode($i);
            $node->setProperty('foo', 'bar', PropertyType::STRING);
            $node->setProperty('count', $i, PropertyType::STRING);
        }

        $session->save();
    }
}
