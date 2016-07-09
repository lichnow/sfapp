<?php

namespace AppBundle\Controller\Frontend;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WidgetsController extends Controller
{
    /**
     * @Template("widgets/nav.html.twig")
     */
    public function recentNavAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return compact('categories');
    }
}
