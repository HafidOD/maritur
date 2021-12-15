<?php 
    namespace App\Components\Pdfs;

class ConfirmationPdf extends \TCPDF {
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        if (\SC::$affiliateId==1) {
            $this->Cell(0, 10, '5707 Addicks Satsuma Rd, Houston TX 77084 | www.goguytravel.com | +52 998 253 6178 Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
}
 ?>