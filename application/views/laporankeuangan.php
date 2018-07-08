<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Laporan Keuangan</title>

    <?php include 'header.php' ?>
</head>

<body>

<section id="container" >
    <!-- **********************************************************************************************************************************************************
    TOP BAR CONTENT & NOTIFICATIONS
    *********************************************************************************************************************************************************** -->
    <!--header start-->
    <?php include('headbar.php');?>
    <!--header end-->

    <!-- **********************************************************************************************************************************************************
    MAIN SIDEBAR MENU
    *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <?php include 'sidebar_master.php'; ?>
    <!--sidebar end-->

    <!-- **********************************************************************************************************************************************************
    MAIN CONTENT
    *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <div class="col-md-4 col-md-offset-4 white-bg margin-up-md" style="padding:20px 50px;">
                <?php
                    $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                    echo form_open('Laporankeuangan/search', $attributes);
                ?>
                <div class="margin-up-md row">
                    <div class="col-sm-8 page-title">Laporan Keuangan</div>
                </div>
                <br>
                <label>Dari Tanggal</label>
                <div class="date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control datepicker"  data-date-format="yyyy-mm-dd" type="text" name="date_from" placeholder="yyyy-mm-dd" autocomplete="off" value="<?=$date_from?>" >
                    <?php echo form_error('date_from'); ?>
                </div>
                <br>
                <label>Sampai Tanggal</label>
                <div class="date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control datepicker"  data-date-format="yyyy-mm-dd" type="text" name="date_to" placeholder="yyyy-mm-dd" autocomplete="off" value="<?=$date_to?>" >
                    <?php echo form_error('date_to'); ?>
                </div>
                <div class="text-center">
                    <button name="create" value="create" type="submit" class="w3-button w3-green w3-center margin-up-md">Print Laporan</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </section>
    </section>

    <!--main content end-->

</section>

<?php include 'footer.php' ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#menu_laporan_keuangan").addClass('active');
        $("#sub_menu_laporan").css("display", "block");
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();

        $('.datepicker').datetimepicker({
            language:  'id',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
    });

</script>

</body>
</html>
