<?php
/**
 * 无限子分类导航挂件
 */

namespace AppBundle\Widget\Frontend\Blog;


use AppBundle\Widget\BaseWidget;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Category;

class NavWidget extends BaseWidget
{
    /**
     * @Template("widget/blog/nav.html.twig")
     */
    public function recent(Category $category = null)
    {
        return ['category' => $this->createCategoryTree($category)];
    }

    private function checkCategoryChildren($array)
    {
        $repo = $this->doctrine->getRepository(Category::class);
        $category = $repo->findOneById($array['id']);
        return $repo->childCount($category);
    }

    private function createCategoryTree($category)
    {
        $repo = $this->doctrine->getRepository(Category::class);
        $controller = $this;
        $tree = $repo->childrenHierarchy(null, false, array(
            'decorate' => true,
            'rootOpen' => '<ul class="dropdown-menu">',
            'rootClose' => '</ul>',
            'childOpen' => function ($child) use (&$controller,&$category) {
                $styleClass = $controller->checkCategoryChildren($child) ? 'class="dropdown-submenu' : 'class="';
                if($category != null && $category->getId() == $child['id']){
                    $styleClass .= ' active"';
                }else{
                    $styleClass .= '"';
                }
                return '<li '.$styleClass.'>';
            },
            'childClose' => '</li>',
            'nodeDecorator' => function ($node) use (&$controller) {
                return '  <a tabindex="0" href="' . $controller->router->generate("blog_category", array("path" => $node['path'])) . '">' . $node['name'] . '</a>';
            }
        ));
        return $tree;
    }
}