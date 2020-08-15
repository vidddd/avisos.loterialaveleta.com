<?php
// src/Entity/Pdf.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Pdf
{
    // ...

    /**
     * @ORM\Column(type="string")
     */
    private $pdfFilename;

    public function getPdfFilename()
    {
        return $this->pdfFilename;
    }

    public function setPdfFilename($pdfFilename)
    {
        $this->pdfFilename = $pdfFilename;

        return $this;
    }
}