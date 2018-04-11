<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Master Timeout</title>

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
            <div class="col-md-6 col-md-offset-3 margin-up-md">
                <div class="w3-container w3-green page-title w3-center w3-padding-16">
                    Master Timeout
                </div>
                <div class="w3-container w3-white w3-padding-32">
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'id' => 'form_timeout');
                    echo form_open('Mastertime/change', $attributes);
                    ?>
                    <label>Lama Timeout (Menit)</label>
                    <?php echo form_input(array('name'=>'time', 'id'=>'time', 'class'=>'w3-input'), $time);?>
                    <div class="text-center">
                        <button name="write" type="submit" class="w3-button w3-green w3-center margin-up-md">Ubah Data</button>
                    </div>
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
        </section>
    </section>

    <!--main content end-->

</section>

<?php include 'footer.php' ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#menu_time").addClass('active');
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
    });
</script>

</body>
</html>
