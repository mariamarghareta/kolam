<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Print Pemberian Pakan</title>

    <?php include 'header.php' ?>
    <style>
        .pad-left{
            padding-left:5px;
        }
    </style>
</head>
<body style="font-family: Ruda !important;">
    <?php if($waktu == "pagi" || $waktu == "all"){ ?>
    <table style="width: 58mm; border:1px solid transparent;">
        <tr>
            <td colspan="2" style="text-align: center">
                <h3><?php echo $data_pakan->kolam_name . " - (PAGI)"; ?></h3>
            </td>
        </tr>
        <tr>
            <td>
                Tanggal
            </td>
            <td>
                : <?php echo $hari_ini_date; ?>
            </td>
        </tr>
        <tr>
            <td>
                Waktu
            </td>
            <td>
                : <?php echo $hari_ini_time; ?>
            </td>
        </tr>
        <tr>
            <td>
                Kode Tebar
            </td>
            <td>
                : <?php echo $data_pakan->kode; ?>
            </td>
        </tr>
        <tr>
            <td>
                Makanan
            </td>
            <td>
                : <?php echo $data_pakan->pagi; ?> kg
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
                : <?php echo $data_pakan->pagi * 1000; ?> gr
            </td>
        </tr>
    </table>
    <?php } ?>
    <?php if($waktu == "all"){ ?>
    <br>
    <br>
    <?php } ?>
    <?php if($waktu == "sore" || $waktu == "all"){ ?>
    <table style="width: 58mm; border:1px solid transparent;">
        <tr>
            <td colspan="2" style="text-align: center">
                <h3><?php echo $data_pakan->kolam_name . " - (SORE)"; ?></h3>
            </td>
        </tr>
        <tr>
            <td>
                Tanggal
            </td>
            <td>
                : <?php echo $hari_ini_date; ?>
            </td>
        </tr>
        <tr>
            <td>
                Waktu
            </td>
            <td>
                : <?php echo $hari_ini_time; ?>
            </td>
        </tr>
        <tr>
            <td>
                Kode Tebar
            </td>
            <td>
                : <?php echo $data_pakan->kode; ?>
            </td>
        </tr>
        <tr>
            <td>
                Makanan
            </td>
            <td>
                : <?php echo $data_pakan->sore; ?> kg
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
                : <?php echo $data_pakan->sore * 1000; ?> gr
            </td>
        </tr>
    </table>
    <?php } ?>
    <?php if($waktu == "all"){ ?>
        <br>
        <br>
    <?php } ?>
    <?php if($waktu == "malam" || $waktu == "all"){ ?>
    <table style="width: 58mm; border:1px solid transparent;">
        <tr>
            <td colspan="2" style="text-align: center">
                <h3><?php echo $data_pakan->kolam_name . " - (MALAM)"; ?></h3>
            </td>
        </tr>
        <tr>
            <td>
                Tanggal
            </td>
            <td>
                : <?php echo $hari_ini_date; ?>
            </td>
        </tr>
        <tr>
            <td>
                Waktu
            </td>
            <td>
                : <?php echo $hari_ini_time; ?>
            </td>
        </tr>
        <tr>
            <td>
                Kode Tebar
            </td>
            <td>
                : <?php echo $data_pakan->kode; ?>
            </td>
        </tr>
        <tr>
            <td>
                Makanan
            </td>
            <td>
                : <?php echo $data_pakan->malam; ?> kg
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
                : <?php echo $data_pakan->malam * 1000; ?> gr
            </td>
        </tr>
    </table>
    <?php } ?>
</body>
</html>