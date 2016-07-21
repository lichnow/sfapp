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

use FOS\UserBundle\Controller\SecurityController as BaseController;
class SecurityController extends BaseController
{
    protected function renderLogin(array $data)
    {
        return $this->render('user/Security/login.html.twig', $data);
    }
}
