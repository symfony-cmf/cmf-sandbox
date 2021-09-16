<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Twig\Helper;

class MenuExtension extends \Twig_Extension
{
    private $helper;

    private $matcher;

    public function __construct(Helper $helper, MatcherInterface $matcher)
    {
        $this->helper = $helper;
        $this->matcher = $matcher;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('app_get_current_menu_item', [$this, 'getCurrent']),
        ];
    }

    public function getCurrent($menu, array $path = [], array $options = [])
    {
        return $this->doGetCurrent($this->helper->get($menu, $path, $options));
    }

    public function getName()
    {
        return 'app_menu';
    }

    private function doGetCurrent(ItemInterface $item)
    {
        if ($this->matcher->isCurrent($item)) {
            return $item;
        }

        foreach ($item->getChildren() as $child) {
            $result = $this->doGetCurrent($child);
            if (null !== $result) {
                return $result;
            }
        }
    }
}
