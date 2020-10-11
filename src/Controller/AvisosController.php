<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;

class AvisosController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    public function pdfs()
    {

    }

    public function futbol(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky nuuuuuuuuuuuuuuuuuuuuuumber: ' . $number . '</body></html>'
        );
    }

    /**
     * @Route("/damePdfs")
     */
    public function damePdfs()
    {
        $pdfs = [];
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->ignoreUnreadableDirs()->in('../public/pdfs');
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                //echo $absoluteFilePath = $file->getRealPath();
                $pdfs[] = $file->getRelativePathname();
            }
            shuffle($pdfs);
        }

        $response = new JsonResponse();
        $response->setData(array_slice($pdfs, 0, 3));
        return $response;
    }
}
