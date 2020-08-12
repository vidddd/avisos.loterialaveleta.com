<?php
// src/Controller/AvisosController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class AvisosController
{
    public function index(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
    
    public function futbol(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}