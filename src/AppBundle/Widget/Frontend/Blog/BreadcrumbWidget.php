<?php
/**
 * Created by PhpStorm.
 * User: lichnow
 * Date: 16/7/16
 * Time: 上午10:24
 */

namespace AppBundle\Widget\Frontend\Blog;


use AppBundle\Entity\Category;
use AppBundle\Widget\BaseWidget;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BreadcrumbWidget extends BaseWidget
{
    /**
     * @Template("widget/blog/breadcrumb.html.twig")
     */
    public function recent(Category $category = null)
    {
        $repo = $this->doctrine->getRepository(Category::class);
        $tree = ($category) ? $repo->getBreadBrumb($category) : [];
        return compact('tree','category');
    }
}