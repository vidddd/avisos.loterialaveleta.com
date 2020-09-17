<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;

class AvisosController extends AbstractController
{
    public function index(): Response
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
            $pdfs = array_reverse($pdfs);
        }

        return $this->render('index.html.twig', [
            'pdfs' => $pdfs,
        ]);
    }

    public function futbol(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky nuuuuuuuuuuuuuuuuuuuuuumber: ' . $number . '</body></html>'
        );
    }
    /**
     * @Route("/number")
     */
    public function number()
    {
        // this looks exactly the same
    }
}
