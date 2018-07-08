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
            $this->Cell( 190, 10, "LAPORAN TEBAR", 0, 1, 'C', false );
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

for ($i = 0; $i < sizeof($details); $i++) {
    $pdf->Cell(30, 5, "Kode Tebar", 0, 0, 'L', 0);
    $pdf->Cell(70, 5, ": " . $details[$i]["tebar"]["kode"], 0, 0, 'L', 0);
    $pdf->Cell(30, 5, "Tgl. Tebar", 0, 0, 'L', 0);
    $pdf->Cell(50, 5, ": " . $details[$i]["tebar"]["tgl_tebar"], 0, 1, 'L', 0);
    $pdf->Cell(30, 5, "Sampling", 0, 0, 'L', 0);
    $pdf->Cell(70, 5, ": " . $details[$i]["tebar"]["sampling"] . "/" . $details[$i]["tebar"]["angka"] . " " . $details[$i]["tebar"]["satuan"], 0, 0, 'L', 0);
    $pdf->Cell(30, 5, "Size", 0, 0, 'L', 0);
    $pdf->Cell(50, 5, ": " . $details[$i]["tebar"]["size"], 0, 1, 'L', 0);
    $pdf->Cell(30, 5, "Biomass", 0, 0, 'L', 0);
    $pdf->Cell(70, 5, ": " . $details[$i]["tebar"]["biomass"], 0, 0, 'L', 0);
    $pdf->Cell(30, 5, "Total Ikan", 0, 0, 'L', 0);
    $pdf->Cell(50, 5, ": " . $details[$i]["tebar"]["total_ikan"], 0, 1, 'L', 0);
    $pdf->Ln();
    $pdf->Cell(30, 5, "History", 0, 1, 'L', 0);
    $pdf->SetFont('Arial','',11);
    for ($j = 0; $j < sizeof($history[$i]); $j++) {
        $pdf->Cell(15, 5, $history[$i][$j]->sequence . ".", 0, 0, 'R', 0);
        $pdf->Cell(85, 5, $history[$i][$j]->keterangan, 0, 0, 'L', 0);
        $pdf->Cell(30, 5, "Kolam", 0, 0, 'L', 0);
        if($history[$i][$j]->keterangan == "Delete Tebar Bibit" || $history[$i][$j]->keterangan == "Delete Sampling" || $history[$i][$j]->keterangan == "Delete Grading"){
            $pdf->Cell(55, 5, ": " . $history[$i][$j]->asal_kolam_name, 0, 1, 'L', 0);
        } else {
            $pdf->Cell(55, 5, ": " . $history[$i][$j]->tujuan_kolam_name, 0, 1, 'L', 0);
        }
//untuk tebar bibit ikan
        if($history[$i][$j]->keterangan == "Tebar Bibit Ikan"){
            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "Tgl.", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->dt, 0, 0, 'L', 0);

            $pdf->Cell(30, 5, "User", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->karyawan_name, 0, 1, 'L', 0);
        }
        if($history[$i][$j]->keterangan == "Delete Tebar Bibit"){
            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "Tgl.", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->dt, 0, 0, 'L', 0);

            $pdf->Cell(30, 5, "User", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->karyawan_name, 0, 1, 'L', 0);
        }
        if($history[$i][$j]->keterangan == "Delete Sampling" || $history[$i][$j]->keterangan == "Delete Grading"|| $history[$i][$j]->keterangan == "Tutup Kolam"){
            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "Tgl.", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->dt, 0, 0, 'L', 0);

            $pdf->Cell(30, 5, "User", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->karyawan_name, 0, 1, 'L', 0);
        }
        if($history[$i][$j]->keterangan == "Penjualan Ikan"){
            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "Tgl.", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->dt, 0, 0, 'L', 0);

            $pdf->Cell(30, 5, "User", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->karyawan_name, 0, 1, 'L', 0);

            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "Jual", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $jual[$i][$j]->jumlah . "kg x Rp " . separator($jual[$i][$j]->harga), 0, 0, 'L', 0);

            $pdf->Cell(30, 5, "Total", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . separator($jual[$i][$j]->total), 0, 1, 'L', 0);
        }
        if($history[$i][$j]->keterangan == "Sampling"){
            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "Tgl.", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->dt, 0, 0, 'L', 0);
            $pdf->Cell(30, 5, "User", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->karyawan_name, 0, 1, 'L', 0);

            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "FCR", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $sampling[$i][$j]->fcr , 0, 0, 'L', 0);
            $pdf->Cell(30, 5, "Up Daging", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $sampling[$i][$j]->kenaikan_daging, 0, 1, 'L', 0);

            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "ADG", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $sampling[$i][$j]->adg, 0, 0, 'L', 0);
        }
        if($history[$i][$j]->keterangan == "Grading"){
            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "Tgl.", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->dt, 0, 0, 'L', 0);
            $pdf->Cell(30, 5, "User", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->karyawan_name, 0, 1, 'L', 0);

            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "Biomass", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $grading[$i][$j]->total_biomass . " kg" , 0, 0, 'L', 0);
            $pdf->Cell(30, 5, "Tujuan Kolam", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $history[$i][$j]->tujuan_kolam_name, 0, 1, 'L', 0);

            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "Populasi", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $grading[$i][$j]->total_populasi, 0, 0, 'L', 0);
            $pdf->Cell(30, 5, "Prtmbhn. Daging", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $grading[$i][$j]->pertumbuhan_daging, 0, 1, 'L', 0);


            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "FCR", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $grading[$i][$j]->fcr, 0, 0, 'L', 0);
            $pdf->Cell(30, 5, "SR", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $grading[$i][$j]->sr, 0, 1, 'L', 0);

            $pdf->Cell(15, 5, "", 0, 0, 'R', 0);
            $pdf->Cell(15, 5, "ADG", 0, 0, 'L', 0);
            $pdf->Cell(70, 5, ": " . $grading[$i][$j]->adg, 0, 0, 'L', 0);
        }
        $pdf->Ln();
        $pdf->Ln();
    }
    $pdf->Ln();
    if($i != sizeof($details)-1){
        $pdf->AddPage();
    }
}
$pdf->Output();