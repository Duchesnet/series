<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function list(SerieRepository $repository, int $page = 1, int $offset = 50,): Response
    {
        $maxPage = ceil($repository->count([]) / $offset);

        // S'assurer que la page min est 1 et page max est page max

        if($page < 1) {
            return $this->redirectToRoute('series_list', ['page' => 1]);
        }
        if($page > $maxPage) {
            return $this->redirectToRoute('series_list', ['page' => $maxPage]);
        }

        $series = $repository->findWithPagination($page);


        //TODO Renvoyer la liste des séries
        return $this->render('serie/list.html.twig',[
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $maxPage,
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(Serie $serie, SerieRepository $repository): Response
    {
//        $serie = $repository->find($id);
//
//        if(!$serie) {
//            throw $this->createNotFoundException('Regarde plutôt The Boys !');
//        }

        return $this->render('serie/detail.html.twig', [
            'serie' => $serie]);
    }

    #[Route('/add', name: 'add', methods: ['GET'])]
    public function add(EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $serie
            ->setName("Cyberpunk 2077")
            ->setBackdrop("backdrop.png")
            ->setDateCreated(new \DateTime("-1 year"))
            ->setGenres("Gangster")
            ->setFirstAirDate(new \DateTime())
            ->setOverview("Une série incroyable")
            ->setPopularity(999)
            ->setStatus("En cours")
            ->setPoster("poster.png")
            ->setTmdbId(1234)
            ->setVote(10);

        dump($serie);
        $entityManager->persist($serie);
        $entityManager->flush();

        dump($serie);
        $serie->setName("Cyberpunk 2082");

        dump($serie);

        $entityManager->flush();

        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->render('serie/add.html.twig');
    }

}
