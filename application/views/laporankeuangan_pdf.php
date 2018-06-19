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
            $this->Cell( 190, 10, "LAPORAN KEUANGAN", 0, 1, 'C', false );
            $this->Ln();
        }

        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(197, 237, 250);
        $this->Cell(12, 5, "No.", "TB", 0, 'C', 1);
        $this->Cell(27, 5, "Tgl.", "TB", 0, 'C', 1);
        $this->Cell(33, 5, "Keterangan", "TB", 0, 'C', 1);
        $this->Cell(30, 5, "Jumlah", "TB", 0, 'C', 1);
        $this->Cell(30, 5, "Harga", "TB", 0, 'C', 1);
        $this->Cell(29, 5, "Pendapatan", "TB", 0, 'C', 1);
        $this->Cell(29, 5, "Pengeluaran", "TB", 0, 'C', 1);
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
    $pdf->Cell(12, 5, $i + 1, 0, 0, 'R', $fill);
    $pdf->Cell(27, 5, $detail[$i]["dt"], 0, 0, 'C', $fill);
    $pdf->Cell(33, 5, $detail[$i]["keterangan"], 0, 0, 'L', $fill);
    $pdf->Cell(30, 5, separator($detail[$i]["jumlah"]), 0, 0, 'R', $fill);
    $pdf->Cell(30, 5, separator($detail[$i]["harga"]), 0, 0, 'R', $fill);
    if ($detail[$i]["jenis"] == 0) {
        $pengeluaran = $detail[$i]["total"];
        $pendapatan = 0;
        $total_pengeluaran += $pengeluaran;
    } else {
        $pengeluaran = 0;
        $pendapatan = $detail[$i]["total"];
        $total_pendapatan += $pendapatan;
    }
    $pdf->Cell(29, 5, separator($pendapatan), 0, 0, 'R', $fill);
    $pdf->Cell(29, 5, separator($pengeluaran), 0, 0, 'R', $fill);
    $pdf->Ln();
//    $pdf->Cell(50,5,'[ x ] checkbox1',1,0,'L',0);
}

$pdf->Ln();
$pdf->Ln();
$pdf->Cell(170,5,"Total Pendapatan",0,0,'L',0);
$pdf->Cell(20,5,separator($total_pendapatan),0,0,'R',0);
$pdf->Ln();
$pdf->Cell(170,5,"Total Pengeluaran",0,0,'L',0);
$pdf->Cell(20,5,separator($total_pengeluaran),0,0,'R',0);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(170,5,"Total Akhir",0,0,'L',0);
$pdf->Cell(20,5,separator($total_pendapatan - $total_pengeluaran),0,0,'R',0);
$pdf->Output();
?>