<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Monitoring</title>

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
    <?php include 'sidebar_master.php' ?>
    <!--sidebar end-->

    <!-- **********************************************************************************************************************************************************
    MAIN CONTENT
    *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <div class="row ">
                <div class="col-sm-4 col-sm-offset-1 white-bg margin-up-md">
                    <div class="page-title">Monitoring Stok Pakan</div>
                    <div class="margin-up-sm">
                        <table
                                id="table_pakan"
                                data-toggle="true"
                                data-show-columns="false"
                                data-height="500">
                            <thead>
                            <tr>
                                <th data-field="name" data-sortable="true">Nama</th)>
                                <th data-field="stok" data-sortable="true">Stok (kg)</th>
                                <th data-field="status" data-align="center" data-sortable="true" data-formatter="statusFormatter">Status</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-sm-4 col-sm-offset-1 white-bg margin-up-md">
                    <div class="page-title">Monitoring Stok Obat</div>
                    <div class="margin-up-sm">
                        <table
                                id="table_obat"
                                data-toggle="true"
                                data-show-columns="false"
                                data-height="500">
                            <thead>
                            <tr>
                                <th data-field="name" data-sortable="true">Nama</th)>
                                <th data-field="stok" data-sortable="true">Stok (ml)</th>
                                <th data-field="status" data-align="center" data-sortable="true" data-formatter="statusFormatter">Status</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <!--main content end-->

</section>

<?php include 'footer.php' ?>

<script type="text/javascript">
    $(window).load(function(){
        var data = <?php echo $arr_pakan; ?>;
        $(function() {
            $('#table_pakan').bootstrapTable({
                data: data,
            });
        });
    });

    $(window).load(function(){
        var data = <?php echo $arr_obat; ?>;
        $(function() {
            $('#table_obat').bootstrapTable({
                data: data,
            });
        });
    });

    $(document).ready(function(){
        $("#menu_monitoring").addClass('active');
        $("#err_msg").addClass('text-center');
        $('#tbkaryawan').dataTable();
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
    });

    function statusFormatter(value, row) {
        if(value == -1){
            return '<div class="btn btn-danger fa fa-times-circle"></div>';
        } else if(value == 0){
            return '<div class="btn btn-warning fa fa-exclamation"></div>';
        } else {
            return '<div class="btn btn-success fa fa-check"></div>';
        }
    }
</script>

</body>
</html>
