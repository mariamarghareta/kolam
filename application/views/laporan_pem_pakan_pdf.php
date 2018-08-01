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
        $this->Cell( 40, 25, "", 0, 0, 'L', false );
        $this->Cell( 40, 25, $this->Image($logo, $this->GetX(), $this->GetY(), 25), 0, 0, 'R', false );
        $this->Cell( 40, 25, "KEBUN IKAN", 0, 0, 'L', false );
        $this->Ln();
        $this->Cell( 190, 10, "", 0, 0, 'L', false );
        $this->Ln();
        if($this->PageNo() == 1){
            $this->SetFont('Arial','B',16);
            $this->Cell( 190, 10, "LAPORAN PEMBUATAN PAKAN", 0, 1, 'C', false );
            $this->Ln();
        }
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
$pdf->AddPage("P", "A4");
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

$pdf->SetFont('Arial','',12);

for ($i = 0; $i < sizeof($pemberian_pakan); $i++) {
    $pdf->Cell(30, 5, "Bahan Pakan", 0, 0, 'L', 0);
    $pdf->Cell(70, 5, ": " . $pemberian_pakan[$i]["pakan_name"], 0, 0, 'L', 0);
    $pdf->Cell(30, 5, "Tgl. Pembuatan", 0, 0, 'L', 0);
    $pdf->Cell(50, 5, ": " . $pemberian_pakan[$i]["create_time"], 0, 1, 'L', 0);
    $pdf->Cell(30, 5, "Jumlah Pakan", 0, 0, 'L', 0);
    $pdf->Cell(70, 5, ": " . $pemberian_pakan[$i]["jumlah_pakan"] . " gr", 0, 0, 'L', 0);
    $pdf->Cell(30, 5, "User", 0, 0, 'L', 0);
    $pdf->Cell(50, 5, ": " . $pemberian_pakan[$i]["create_user"], 0, 1, 'L', 0);
    $pdf->Ln();
    $pdf->Cell(30, 5, "List Obat", 0, 1, 'L', 0);
    $pdf->SetFont('Arial','',11);
    for ($j = 0; $j < sizeof($history[$i]); $j++) {
        $pdf->Cell(100, 5, $history[$i][$j]["obat_name"], 0, 0, 'L', 0);
        $pdf->Cell(80, 5, $history[$i][$j]["jumlah"] . " " .  $history[$i][$j]["satuan"], 0, 0, 'R', 0);
        $pdf->Ln();
    }
    $pdf->Ln();
    if($i != sizeof($pemberian_pakan)-1){
        $pdf->AddPage();
    }
}
$pdf->Output();