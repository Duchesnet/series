<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): Response
    {

        //TODO Renvoyer la liste des séries
        return $this->render('serie/list.html.twig');
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail($id): Response
    {

        dump($id);
        dump($id);
        dump($id);
        dump($id);
        dump($id);
        dump($id);

        //TODO Renvoyer une série spécifique
        return $this->render('serie/detail.html.twig');
    }

    #[Route('/add', name: 'add', methods: ['GET'])]
    public function add(): Response
    {

        //TODO Ajouter une série
        return $this->render('serie/add.html.twig');
    }

}
