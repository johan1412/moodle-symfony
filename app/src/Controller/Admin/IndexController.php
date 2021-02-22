<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/", name="")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

}