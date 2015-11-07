<?php

namespace AppBundle\Twig;

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
        return array(
            new \Twig_SimpleFunction('app_get_current_menu_item', array($this, 'getCurrent')),
        );
    }

    public function getCurrent($menu, array $path = array(), array $options = array())
    {
        return $this->doGetCurrent($this->helper->get($menu, $path, $options));
    }

    private function doGetCurrent(ItemInterface $item)
    {
        if ($this->matcher->isCurrent($item)) {
            return $item;
        }

        foreach ($item->getChildren() as $child) {
            if (null !== $this->doGetCurrent($child)) {
                return $child;
            }
        }
    }

    public function getName()
    {
        return 'app_menu';
    }
}
