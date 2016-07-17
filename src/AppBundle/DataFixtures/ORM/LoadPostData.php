<?php
/**
 * Created by PhpStorm.
 * User: lichnow
 * Date: 16/7/9
 * Time: 上午2:30
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use AppBundle\Entity\Post;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $posts = array(
            ['title' => '第一讲:Symfony3的简介,开发环境与版本控制', 'cate' => 'sfpra'],
            ['title' => '第二讲: 最佳实践与第一个Symfony应用', 'cate' => 'sfpra'],
            ['title' => '第三讲: 创建初步用户系统', 'cate' => 'sfpra'],
        );
        $post_files_path = $this->container->getParameter('kernel.root_dir') . '/data/fixtures/posts';
        $finder = new Finder();
        $finder->files()->in($post_files_path);
        $i = 0;
        foreach ($finder as $file) {
            $posts[$i]['content'] = $file->getContents();
            $i++;
        }
        foreach ($posts as $post) {
            $Post = new Post();
            $Post->setTitle($post['title']);
            $Post->setContent($post['content']);
            $Post->setCategory($this->getReference('category-' . $post['cate']));
            $manager->persist($Post);
            $manager->flush();
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
