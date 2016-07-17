<?php
/**
 * Created by PhpStorm.
 * User: lichnow
 * Date: 16/7/12
 * Time: 上午11:06
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\Category;
use AppBundle\Utils\ToPingyin;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class PingyinSlugSubscriber implements EventSubscriber
{
    private $pingyin;
    public function __construct(ToPingyin $pingyin)
    {
        $this->pingyin = $pingyin;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist'
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Category) {
            $entity->setSlug($this->pingyin->trans($entity->getName()));
        }
    }
}