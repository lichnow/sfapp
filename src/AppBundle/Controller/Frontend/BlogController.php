<?php

namespace AppBundle\Controller\Frontend;

use AppBundle\Entity\Post;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * 博客控制器用于文章展示及单篇文章显示
 * 使用annotation路由写法
 * 具体写法请查看http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/routing.html
 *
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * 使用模型实体注入写法
     * 具体查看http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     * Method路由访问方式可以注明,如果是GET的话可以忽略
     *
     * 使用annotation模板写法
     * 具体查看http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/view.html
     *
     * @Route("/{path}", name="blog_index",defaults={"path": null})
     * @Method("GET")
     * @Template("blog/post/index.html.twig")
     */
    public function indexAction(Category $category = null)
    {
        $PostRepo = $this->getDoctrine()->getRepository(Post::class);
        $posts = ($category) ? $PostRepo->findByCategory($category) : $PostRepo->findAll();
        return compact('posts');
    }
    /**
     * @Route("/posts/{id}", name="blog_post_show")
     * @Method("GET")
     * @Template("blog/post/show.html.twig",vars={"post"})
     */
    public function showAction(Post $post)
    {
    }
}
