<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function list(SerieRepository $repository, int $page = 1, int $offset = 50): Response
    {
        $maxPage = ceil($repository->count([]) / $offset);

        // S'assurer que la page min est 1 et page max est page max

        if ($page < 1) {
            return $this->redirectToRoute('series_list', ['page' => 1]);
        }
        if ($page > $maxPage) {
            return $this->redirectToRoute('series_list', ['page' => $maxPage]);
        }

        $series = $repository->findWithPagination($page);


        //TODO Renvoyer la liste des séries
        return $this->render('serie/list.html.twig', [
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

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function save(EntityManagerInterface $entityManager, Request $request, int $id = null, SerieRepository $repository): Response
    {
        $serie = !$id ? new Serie() : $entity = $repository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException('No such serie');
        }

        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->get('genres')->setData(explode('/', $serie->getGenres()));

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $serie->setGenres(implode('/', $serieForm->get('genres')->getData()));
            $backdrop = $serieForm->get('backdrop')->getData();


            /**
             * @var UploadedFile $backdrop
             */
            $fileName = $serie->getName() . '-' . uniqid() . '.' . $backdrop->guessExtension();
            $backdrop->move("img/backdrops", $fileName);

            $serie->setBackdrop($fileName);


            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Série ' . $serie->getName() . ' créée avec success.');

            return $this->redirectToRoute('series_detail', ['id' => $serie->getId()]);
        }

        return $this->render('serie/save.html.twig', [
            'serieForm' => $serieForm,
            'serieId' => $serie->getId(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(Serie $serie, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('success', 'Serie has been deleted.');

        return $this->redirectToRoute('series_list');

    }
}
