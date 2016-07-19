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
            ['name' => 'web课程','tree' => 'web'],
            ['name' => 'php课程','parent' => 'web','tree' => 'php'],
            ['name' => 'symfony3课程','parent' => 'php','tree' => 'symfony'],
            ['name' => 'symfony3框架实战','parent' => 'symfony','tree' => 'sfpra'],
            ['name' => '前端课程','tree' => 'front'],
            ['name' => 'reactjs开发全解','parent' => 'front','tree' => 'repra'],
            ['name' => 'Vuejs开发全解','parent' => 'front','tree' => 'revue'],
            ['name' => '运维课程','tree' => 'os']
        );

        foreach ($categories as $category) {
            $Category = new Category();
            $Category->setName($category['name']);
            $Category->setSlug($category['tree']);
            $manager->persist($Category);
            $manager->flush();
            $this->addReference('category-' . $category['tree'], $Category);
        }

        $repo = $manager->getRepository('AppBundle:Category');

        foreach ($categories as $category) {
            $Category = $repo->findOneByName($category['name']);
            if(key_exists('parent',$category)){
                $parent = $this->getReference('category-'.$category['parent']);
                $Category->setParent($parent);
                $manager->persist($Category);
                $manager->flush();
            }
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
