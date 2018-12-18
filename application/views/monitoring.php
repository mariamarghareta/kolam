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
    <style>

    </style>
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
                <div class="col-sm-4 margin-up-md">
                    <div class="white-bg" style="padding:20px;">
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
                                    <th data-field="stok" data-sortable="true" data-formatter="commaFormatterWithSatuan" data-halign="center" data-align="right">Stok</th>
                                    <th data-field="status" data-align="center" data-sortable="true" data-formatter="statusFormatter">Status</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 margin-up-md">
                    <div class="white-bg" style="padding:20px;">
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
                                    <th data-field="stok" data-sortable="true" data-formatter="commaFormatterWithSatuan" data-halign="center" data-align="right">Stok</th>
                                    <th data-field="status" data-align="center" data-sortable="true" data-formatter="statusFormatter">Status</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 margin-up-md">
                    <div class="white-bg" style="padding:20px;">
                        <div class="page-title">Kolam yang Kosong</div>
                        <div class="margin-up-sm">
                            <table
                                    id="table_kolam_kosong"
                                    data-toggle="true"
                                    data-show-columns="false"
                                    data-height="500">
                                <thead>
                                <tr>
                                    <th data-field="blok_name" data-sortable="true">Nama Blok</th)>
                                    <th data-field="name" data-sortable="true">Nama Kolam</th)>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 margin-up-md">
                    <div class="white-bg" style="padding:20px;">
                        <div class="col-sm-8">
                            <div class="page-title">Monitoring Pakan dan Air</div>
                        </div>
                        <div class="col-sm-4" style="text-align: right">
                            <div class="page-title">Total Pakan : <?php echo round($total_pakan->total_pakan * 100,2); ?> gr</div>
                        </div>
                        <div class="margin-up-md">
                            <table
                                    id="table_monitoring"
                                    data-toggle="true"
                                    data-show-columns="false"
                                    data-height="500">
                                <thead>
                                <tr>
                                    <th data-field="blok_name" data-sortable="true">Nama Blok</th)>
                                    <th data-field="kolam_name" data-sortable="true">Nama Kolam</th)>
                                    <th data-field="kode" data-sortable="true">Kode Tebar</th>
                                    <th data-field="total_ikan" data-sortable="true">Total Ikan</th>
                                    <th data-field="fcr" data-sortable="true">FCR</th>
                                    <th data-field="pakan_pagi" data-sortable="true" data-formatter="stat" data-align="center">Pakan Pagi</th>
                                    <th data-field="pakan_sore" data-sortable="true" data-formatter="stat" data-align="center">Pakan Sore</th>
                                    <th data-field="pakan_malam" data-sortable="true" data-formatter="stat" data-align="center">Pakan Malam</th>
                                    <th data-field="air_pagi" data-sortable="true" data-formatter="stat" data-align="center">Cek Air Pagi</th>
                                    <th data-field="air_sore" data-sortable="true" data-formatter="stat" data-align="center">Cek Air Sore</th>
                                    <th data-field="" data-formatter="print" data-align="center">Print Pemberian Pakan</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
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

        var data = <?php echo $arr_kolam_kosong; ?>;
        $(function() {
            $('#table_kolam_kosong').bootstrapTable({
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

    $(window).load(function(){
        var data = <?php echo $arr_monitoring; ?>;
        $(function() {
            $('#table_monitoring').bootstrapTable({
                data: data,
            });
            $(".print").click( function($this){
                $parent = $(this).parent().closest("form").attr("id");
                $("#" + $parent).prop("target", "_blank");
                $("#" + $parent).attr("action", "<?php echo base_url();echo index_page(); ?>/Monitoring/print_pakan");
                $("#" + $parent).submit();
                $("#" + $parent).prop("target", "_self");
                $("#" + $parent).attr("action", "<?php echo base_url();echo index_page(); ?>/Monitoring/print_pakan");
                //alert("aaa");
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

    function stat(value, row) {
        if(value == 0){
            return '<div class="btn btn-danger fa fa-times-circle"></div>';
        } else {
            return '<div class="btn btn-success fa fa-check"></div>';
        }
    }

    function print(value, row) {
        return [
            '<div class="col-sm-3">',
            '<form action="<?php echo base_url().index_page(); ?>/Monitoring/print_pakan" method="post" id="print' + row['pemberian_pakan_id'] +'" class="form_cetak">',
            '<input type=hidden value= ' + row['pemberian_pakan_id'] + ' name="pemberian_pakan_id" />',
            '<input type=hidden value="all" name="waktu" />',
            '<button type="button" class="print btn btn-default waves-effect btn-print" id="print" name="print">Print</button>',
            <?php echo "'" . form_close() . "'"; ?>,
            '</div>',
            '<div class="col-sm-3">',
            '<form action="<?php echo base_url().index_page(); ?>/Monitoring/print_pakan" method="post" id="print_pagi_' + row['pemberian_pakan_id'] +'" class="form_cetak">',
            '<input type=hidden value= ' + row['pemberian_pakan_id'] + ' name="pemberian_pakan_id" />',
            '<input type=hidden value="pagi" name="waktu" />',
            '<button type="button" class="print btn btn-default waves-effect btn-print" id="print" name="print">Pagi</button>',
            <?php echo "'" . form_close() . "'"; ?>,
            '</div>',
            '<div class="col-sm-3">',
            '<form action="<?php echo base_url().index_page(); ?>/Monitoring/print_pakan" method="post" id="print_sore_' + row['pemberian_pakan_id'] +'" class="form_cetak">',
            '<input type=hidden value= ' + row['pemberian_pakan_id'] + ' name="pemberian_pakan_id" />',
            '<input type=hidden value="sore" name="waktu" />',
            '<button type="button" class="print btn btn-default waves-effect btn-print" id="print" name="print">Sore</button>',
            <?php echo "'" . form_close() . "'"; ?>,
            '</div>',
            '<div class="col-sm-3">',
            '<form action="<?php echo base_url().index_page(); ?>/Monitoring/print_pakan" method="post" id="print_malam_' + row['pemberian_pakan_id'] +'" class="form_cetak">',
            '<input type=hidden value= ' + row['pemberian_pakan_id'] + ' name="pemberian_pakan_id" />',
            '<input type=hidden value="malam" name="waktu" />',
            '<button type="button" class="print btn btn-default waves-effect btn-print" id="print" name="print">Malam</button>',
            <?php echo "'" . form_close() . "'"; ?>,
            '</div>'
        ].join('');
    }


</script>

</body>
</html>
