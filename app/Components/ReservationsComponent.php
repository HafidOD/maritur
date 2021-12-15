<?php 
    namespace App\Components;

class ReservationsComponent{
	public static function getConfirmationPdf($res,$stream=true)
	{
        try {
          ob_start();
            $pdf = new \App\Components\Pdfs\ConfirmationPdf('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetFont('dejavusans','',8);
            $pdf->setPrintHeader(false);
            $pdf->AddPage();
            if(\SC::$affiliateId==1) self::setStyle($pdf);
            $pdf->writeHTML(view('pdfs.res-confirmation',['res'=>$res]),true,false,true,false,'');
            $pdf->lastPage();
            ob_end_clean();            
        } catch (\Exception $e) {
            // return null;
        }
		if ($stream) $pdf->Output('Confirmation.pdf','I');
		else return $pdf->Output('Confirmation.pdf','S');
	}
	public static function setStyle($pdf)
	{
		$pdf->SetAlpha(0.3);
        $pdf->Image('https://www.goguytravel.com/images/goguy-background.png');
        $pdf->SetAlpha(1);
	}
}