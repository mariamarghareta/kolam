<?php
$path = APPPATH . '../assets/fpdf181/fpdf.php';
require_once($path);

function separator($angka){
    return number_format($angka,0,".",",");
}

class PDF extends FPDF
{
    public $detail;
    function setDetail($det){
        $this->detail = $det;
    }
    function Header()
    {
        $logo = APPPATH . "../assets/logo.jpg";
        $this->SetFont('Arial','',24);
        $this->Cell( 40, 25, $this->Image($logo, $this->GetX(), $this->GetY(), 25), 0, 0, 'L', false );
        $this->Cell( 40, 25, "KEBUN IKAN", 0, 0, 'L', false );
        $this->Ln();
        $this->Cell( 190, 10, "", 0, 0, 'L', false );
        $this->Ln();
        if($this->PageNo() == 1){
            $this->SetFont('Arial','B',16);
            $this->Cell( 279, 10, "LAPORAN MONITORING SAYUR", 0, 1, 'C', false );
            $this->Ln();
        }

        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(197, 237, 250);
        $this->Cell(12, 5, "No.", "TBRL", 0, 'C', 1);
        $this->Cell(25, 5, "Tgl.", "TBLR", 0, 'C', 1);
        $this->Cell(25, 5, "PH (Pagi)", "TBLR", 0, 'C', 1);
        $this->Cell(25, 5, "TDS (Pagi)", "TBLR", 0, 'C', 1);
        $this->Cell(25, 5, "PH (Sore)", "TBLR", 0, 'C', 1);
        $this->Cell(25, 5, "TDS (Sore)", "TBLR", 0, 'C', 1);
        $this->Cell(70, 5, "Bahan Penolong", "TBLR", 0, 'C', 1);
        $this->Cell(70, 5, "Keterangan", "TBLR", 0, 'C', 1);
        $this->Ln();

    }

    function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial','',10);
        // Print centered page number
        $this->Cell(0,10,''.$this->PageNo(),0,0,'C');
    }
}

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->setDetail($detail);
$pdf->AddPage("L", "A4");
$pdf->SetAutoPageBreak(true,15);
//$pdf->Cell(40,10,'Hello World!');
//$pdf->Cell(40,5,' ','LTR',0,'L',0);   // empty cell with left,top, and right borders
//$pdf->Cell(50,5,'Words Here',1,0,'L',0);
//$pdf->Cell(50,5,'Words Here',1,0,'L',0);
//$pdf->Cell(40,5,'Words Here','LR',1,'C',0);  // cell with left and right borders
//$pdf->Cell(50,5,'[ x ] abc',1,0,'L',0);
//$pdf->Cell(50,5,'[ x ] checkbox1',1,0,'L',0);
//$pdf->Cell(40,5,'','LBR',1,'L',0);   // empty cell with left,bottom, and right borders
//$pdf->Cell(50,5,'[ x ] def',1,0,'L',0);
//$pdf->Cell(50,5,'[ x ] checkbox2',1,0,'L',0);

$total_pendapatan = 0;
$total_pengeluaran = 0;

for ($i = 0; $i < sizeof($detail); $i++) {
    $pdf->SetFillColor(245, 247, 250);
//    $pdf->Rect(0, $i*5, 33,5, 'F');
    if ($i % 2 == 0) {
        $fill = 1;
    } else {
        $fill = 0;
    }
    $pdf->Cell(12, 5, $i + 1, "LR", 0, 'R', $fill);
    $pdf->Cell(25, 5, $detail[$i]["write_time"], "LR", 0, 'C', $fill);
    $pdf->Cell(25, 5, $detail[$i]["ph_pagi"], "LR", 0, 'C', $fill);
    $pdf->Cell(25, 5, $detail[$i]["tds_pagi"], "LR", 0, 'C', $fill);
    $pdf->Cell(25, 5, $detail[$i]["ph_sore"], "LR", 0, 'C', $fill);
    $pdf->Cell(25, 5, $detail[$i]["tds_sore"], "RL", 0, 'C', $fill);
    $pdf->Cell(70, 5, 'Pagi: ' . ucfirst(strtolower($detail[$i]["obat_pagi"])), "RL", 0, 'L', $fill);
    $pdf->Cell(70, 5, 'Pagi: ' . ucfirst(strtolower($detail[$i]["ket_pagi"])), "RL", 1, 'L', $fill);

    $border = "LR";
    if($i == sizeof($detail)-1){
        $border = "LRB";
    }
//    gap
    $pdf->Cell(12, 5, "", $border, 0, 'R', $fill);
    $pdf->Cell(25, 5, "", $border, 0, 'C', $fill);
    $pdf->Cell(25, 5, "", $border, 0, 'C', $fill);
    $pdf->Cell(25, 5, "", $border, 0, 'C', $fill);
    $pdf->Cell(25, 5, "", $border, 0, 'C', $fill);
    $pdf->Cell(25, 5, "", $border, 0, 'C', $fill);
//    gap
    $pdf->Cell(70, 5, 'Sore: ' . ucfirst(strtolower($detail[$i]["obat_sore"])), $border, 0, 'L', $fill);
    $pdf->Cell(70, 5, 'Sore: ' . ucfirst(strtolower($detail[$i]["ket_sore"])), $border, 0, 'L', $fill);
    $pdf->Ln();
//    $pdf->Cell(50,5,'[ x ] checkbox1',1,0,'L',0);
}
$pdf->Output();
?>