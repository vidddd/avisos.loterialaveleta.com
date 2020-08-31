<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisosController extends AbstractController
{
    public function index(): Response
    {
        $number = random_int(0, 100);

        return $this->render('index.html.twig', [
            'number' => $number,
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
