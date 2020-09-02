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
use setasign\Fpdi\Fpdi;

class BackController extends AbstractController
{
    private $temporales;

    public function __construct()
    {
        $this->temporales = [];
    }

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
                // Convertimos el pdf a la version 1.4
                $outputFileName = tempnam(sys_get_temp_dir(), '14');
                // merge files and save resulting file as PDF version 1.4 for FPDI compatibility
                $cmd = "gs -q -dNOPAUSE -dBATCH -dCompatibilityLevel=1.4 -sDEVICE=pdfwrite -sOutputFile=$outputFileName" . " " . $pdfFile->getPathName();
                $result = shell_exec($cmd);
                $pdfi = new Fpdi();
                $pageCount = $pdfi->setSourceFile($outputFileName);

                $pdf_dir_tmp = $this->getParameter('kernel.project_dir') . '/public/pdfs-tmp';
                $name_tmp = uniqid();
                //Si es 1 una pagina solo no lo parte 
                if ($pageCount > 1) {
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $newPdf = new Fpdi();
                        $newPdf->addPage();
                        $newPdf->setSourceFile($outputFileName);
                        $newPdf->useTemplate($newPdf->importPage($i));
                        $newFilename = sprintf('%s/%s_%s.pdf', $pdf_dir_tmp, $name_tmp, $i);
                        $newPdf->output($newFilename, 'F');
                        $this->temporales[] = sprintf('%s_%s.pdf', $name_tmp, $i);
                    }
                } else {
                    $filesystem = new Filesystem();
                    try {
                        $nombre =  'pdfs/' . uniqid() . '.pdf';
                        $filesystem->rename($pdfFile->getPathName(), $nombre);
                        $filesystem->chmod($nombre, 0777);
                    } catch (IOExceptionInterface $exception) {
                        echo "An error occurred while creating your directory at " . $exception->getPath();
                    }
                }

                return $this->render('back/subir.html.twig', ['form' => $form->createView(), 'temporales' => $this->temporales]);
            }
            return $this->render('back/subir.html.twig', ['form' => $form->createView(), 'temporales' => null]);
        }

        return $this->render('back/subir.html.twig', ['form' => $form->createView(), 'temporales' => null]);
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
