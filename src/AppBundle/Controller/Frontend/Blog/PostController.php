<?php

namespace AppBundle\Controller\Frontend\Blog;

use AppBundle\Entity\Post;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 博客控制器用于文章展示及单篇文章显示
 * 使用annotation路由写法
 * 具体写法请查看http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/routing.html
 *
 * @Route("/blog")
 */
class PostController extends Controller
{

    /**
     * 使用模型实体注入写法
     * 具体查看http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     * Method路由访问方式可以注明,如果是GET的话可以忽略
     *
     * 使用annotation模板写法
     * 具体查看http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/view.html
     *
     * @Route("/", name="blog_index", defaults={"_format": "html"},requirements={
     *     "_format": "html"
     * })
     * @Method("GET")
     * @Template("blog/post/list.html.twig")
     */
    public function indexAction()
    {
        $PostRepo = $this->getDoctrine()->getRepository(Post::class);
        $posts = $PostRepo->findAll();
        $category = null;
        return compact('posts','category');
    }

    /**
     * 使用模型实体注入写法
     * 具体查看http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     * Method路由访问方式可以注明,如果是GET的话可以忽略
     *
     * 使用annotation模板写法
     * 具体查看http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/view.html
     *
     * @Route("/category/{path}", name="blog_category",requirements={ "path": ".+"})
     * @Method("GET")
     * @Template("blog/post/list.html.twig")
     */
    public function listAction(Category $category)
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findByNodeCategory($category);
        return compact('posts','category');
    }
    /**
     * @Route("/posts/{path}.html", name="blog_post_show",requirements={ "path": ".+"})
     * @Method("GET")
     * @Template("blog/post/show.html.twig")
     */
    public function showAction(Post $post)
    {
        $category = $post->getCategory();
        return compact('category','post');
    }
}
