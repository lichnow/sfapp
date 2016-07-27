<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterGroupResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseGroupEvent;
use FOS\UserBundle\Event\GroupEvent;
use FOS\UserBundle\Controller\GroupController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * RESTful controller managing group CRUD
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class GroupController extends BaseController
{
    /**
     * Show all groups
     */
    public function listAction()
    {
        $groups = $this->get('fos_user.group_manager')->findGroups();

        return $this->render('user/Group/list.html.twig', array(
            'groups' => $groups
        ));
    }

    /**
     * Show one group
     */
    public function showAction($groupName)
    {
        $group = $this->findGroupBy('name', $groupName);

        return $this->render('user/Group/show.html.twig', array(
            'group' => $group
        ));
    }

    /**
     * Edit one group, show the edit form
     */
    public function editAction(Request $request, $groupName)
    {
        $group = $this->findGroupBy('name', $groupName);

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseGroupEvent($group, $request);
        $dispatcher->dispatch(FOSUserEvents::GROUP_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.group.form.factory');

        $form = $formFactory->createForm();
        $form->setData($group);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
            $groupManager = $this->get('fos_user.group_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::GROUP_EDIT_SUCCESS, $event);

            $groupManager->updateGroup($group);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_group_show', array('groupName' => $group->getName()));
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::GROUP_EDIT_COMPLETED, new FilterGroupResponseEvent($group, $request, $response));

            return $response;
        }

        return $this->render('user/Group/edit.html.twig', array(
            'form'      => $form->createview(),
            'group_name'  => $group->getName(),
        ));
    }

    /**
     * Show the new form
     */
    public function newAction(Request $request)
    {
        /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
        $groupManager = $this->get('fos_user.group_manager');
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.group.form.factory');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $group = $groupManager->createGroup('');

        $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_INITIALIZE, new GroupEvent($group, $request));

        $form = $formFactory->createForm();
        $form->setData($group);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_SUCCESS, $event);

            $groupManager->updateGroup($group);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_group_show', array('groupName' => $group->getName()));
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_COMPLETED, new FilterGroupResponseEvent($group, $request, $response));

            return $response;
        }

        return $this->render('user/Group/new.html.twig', array(
            'form' => $form->createview(),
        ));
    }
}
