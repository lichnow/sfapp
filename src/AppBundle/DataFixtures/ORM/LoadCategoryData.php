<?php
/**
 * Created by PhpStorm.
 * User: lichnow
 * Date: 16/7/9
 * Time: 上午2:17
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categories = array(
            ['name' => 'symfony3框架实战', 'path' => 'symfony3-practis'],
            ['name' => 'reactjs开发全解', 'path' => 'reactjs-development'],
        );
        foreach ($categories as $category) {
            $Category = new Category();
            $Category->setName($category['name']);
            $Category->setPath($category['path']);
            $manager->persist($Category);
            $manager->flush();
            $this->addReference('category-' . $category['path'], $Category);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
