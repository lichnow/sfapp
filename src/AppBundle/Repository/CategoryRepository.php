<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends MaterializedPathRepository
{
    public function getRoot(Category $category,$pathSeparator = '/') {
        if (0 !== $category->getLevel() && null !== $category->getPath()) {
            $rootPath = explode($pathSeparator, $category->getPath())[0];
            return $this->findOneByPath($rootPath);
        }

        return $category;
    }

//    public function getSiblings(Category $category)
//    {
//        if (null !== $category){
//            return $this->getChildren($category->getParent(),true);
//        }
//        return $category;
//    }

    public function getBreadBrumb(Category $category)
    {
        $qb = $this->_em->createQueryBuilder();
        $tree = $qb->select('c')
            ->from(Category::class,'c')
            ->where($qb->expr()->in('c',':tree'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('c.id',$category->getId()),
                $qb->expr()->lt('c.level',$category->getLevel())
            ))
            ->orderBy('c.level','ASC')
            ->setParameter('tree',$this->getTree($this->getRoot($category)))
            ->getQuery()
            ->getArrayResult();
        return $tree;
    }

    public function getNavMenu()
    {
        $qb = $this->_em->createQueryBuilder();
        $tree = $qb->select('c')
            ->from(Category::class,'c')
            ->where('c.level < 2')
            ->getQuery()
            ->getArrayResult();
        return $tree;
    }
}
