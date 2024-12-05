<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    /**
     * @Route('/main', name = {'app_main'})
     */
    #[Route('/home', name: 'main_home')]
    public function index(): Response
    {
        $username = "<h2>Thomas</h2>";
        $serie = ["name" => "Emily in Paris", "year" => 2020];
        return $this->render('main/home.html.twig', [
            'name' => $username,
            'serie' => $serie
        ]);
    }

    #[Route('/test', name: 'main_test')]
    public function test(): Response
    {

        $data = file_get_contents('https://swapi.dev/api/planets/18');
        dump($data);

//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, "https://swapi.dev/api/planets/18");
//        curl_setopt($curl, CURLOPT_POST);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, ['key' => 'val']);
//
//        curl_exec($curl);
//        curl_close($curl);

        return $this->render('main/test.html.twig');
    }

}
