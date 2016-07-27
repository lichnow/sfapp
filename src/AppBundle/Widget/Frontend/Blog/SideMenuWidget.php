<?php
/**
 * 分类sidebar挂件
 */

namespace AppBundle\Widget\Frontend\Blog;
use AppBundle\Entity\Category;
use AppBundle\Widget\BaseWidget;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/blog/category",service="widget.app.blog_sidemenu")
 */
class SideMenuWidget extends BaseWidget
{
    /**
     * 加载分类sidebar模板
     * @Template("widget/blog/sidemenu.html.twig")
     */
    public function recent(Category $category = null)
    {
        return compact('category');
    }

    /**
     * 输出分类tree的API数据
     * @Route("/tree/api/{id}",options={"expose"=true},name="widget.app.blog_category",defaults={"id":null})
     * @param Category $category
     * @return JsonResponse
     */
    public function treeApiAction(Category $category = null)
    {
        $repo = $this->doctrine->getRepository(Category::class);
        $repo->setChildrenIndex('nodes');
        if($category){
            $selected = $category;
            $root = $repo->getRoot($category);
        }
        $categories = $repo->buildTree($repo->getTreeQuery()->getArrayResult());
        $controller = $this;
        $nodeid = 0;
        $rootNodeId = 0;
        $filter = function(&$categories,&$controller) use(&$filter,&$selected,&$nodeid,&$root,&$rootNodeId){
            foreach ($categories as $key => $category){
                $categories[$key]['text'] = $category['name'];
                $categories[$key]['href'] = $controller->router->generate("blog_category", array("path" => $category['path']));
                unset($categories[$key]['name']);
                unset($categories[$key]['path']);
                unset($categories[$key]['slug']);
                $categories[$key]['state'] = [];
                $categories[$key]['nodeid'] = $nodeid;
                if(isset($selected) && isset($root)){
                    if($category['id'] == $root->getId()){
                        $rootNodeId = $nodeid;
                    }
                    if ($category['id'] == $selected->getId()){
                        $categories[$key]['state']['checked'] = true;
                        $categories[$key]['state']['selected'] = true;
                        $categories[$key]['state']['expanded'] = true;
                        $categories[$key]['rootnode'] = $rootNodeId;
                    }
                }
                $nodeid++;
                if(count($category['nodes']) <= 0){
                    unset($categories[$key]['nodes']);
                }else{
                    $filter($categories[$key]['nodes'],$controller);
                }
            }
        };
        $filter($categories,$controller);
        return $this->json($categories);
    }
}