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
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use FPDF;
use setasign\Fpdi\Fpdi;

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
                //echo $absoluteFilePath = $file->getRealPath();

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

            if ($pdfFile) {
                $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                // initiate FPDI
                $pdfi = new Fpdi();
                //$pdf_dir = $this->getParameter('kernel.project_dir') . '/public/pdfs/';
                //$pagecount = $pdfi->setSourceFile($pdf_dir . $originalFilename . '.pdf');
                $pagecount = $pdfi->setSourceFile($pdfFile->getPathName());
                dump($pagecount);
                //dump($pdfFile->getPathName());
                die;
                /*
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
            return $this->render('back/subir.html.twig', ['form' => $form->createView(),]);
        }

        return $this->render('back/subir.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * @Route("/back/delete-pdf/{pdf}", name="back_delete_pdf")
     */
    public function deletePdf($pdf)
    {
        $filesystem = new Filesystem();
        try {
            $filesystem->remove(['pdfs/' . $pdf]);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at " . $exception->getPath();
        }
        return $this->redirect($this->generateUrl('back'));
    }
}
