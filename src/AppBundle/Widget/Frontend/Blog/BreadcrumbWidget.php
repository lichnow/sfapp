<?php
/**
 * 面包屑挂件
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