<?php

namespace App\Controller\Back;

use App\Entity\Pdf;
use App\Form\PdfType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;

class BackController extends AbstractController
{
    /**
     * @Route("/back", name="back")
     */
    public function index(): Response
    {
        $pdfs = [];
        $finder = new Finder();
        $finder->files()->in('../public/pdfs');
        if ($finder->hasResults()) {

            foreach ($finder as $file) {
                // $absoluteFilePath = $file->getRealPath();
                $pdfs[] = $fileNameWithExtension = $file->getRelativePathname();
            }
        }
        return $this->render('back/index.html.twig', ['pdfs' => $pdfs]);
    }

    /**
     * @Route("/back/subir", name="back_subir_pdf")
     */
    public function subir(Request $request, SluggerInterface $slugger)
    {
        $pdf = new Pdf();
        $form = $this->createForm(PdfType::class, $pdf);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pdfFile */
            $pdfFile = $form->get('pdf')->getData();
            dump($pdfFile);

            if ($pdfFile) {

                /*
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $product->setBrochureFilename($newFilename);
                */
            }

            //return $this->redirect($this->generateUrl('app_product_list'));

            return $this->render('back/subir.html.twig', ['form' => $form->createView(),]);
        }

        return $this->render('back/subir.html.twig', ['form' => $form->createView(),]);
    }
}
