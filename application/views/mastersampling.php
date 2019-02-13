<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Sampling</title>

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
            <div class="col-md-12 white-bg margin-up-md">
                <div class="margin-up-md">
                    <div class="col-sm-8 page-title">Sampling</div>
                    <div class="col-sm-4 right-align"><a href="<?php echo base_url() . index_page(); ?>/Mastersampling/create" class="btn btn-success btn-sm"><i class="fa fa-plus"></i><span> TAMBAH DATA</span></a></div>
                </div>
                <div id="toolbar">
                    <div class="form-inline pull-right margin-up-down">
                        <input name="search" id="search" class="form-control pull-right" type="text" placeholder="Search">
                    </div>
                </div>
                <table
                    id="table"
                    data-toggle="true"
                    data-show-columns="false"
                    data-height="500">
                    <thead>
                    <tr>
                        <th data-field="kode" data-sortable="true">Kode Tebar</th)>
                        <th data-field="dt" data-sortable="true">Tgl. Sampling</th)>
                        <th data-field="blok_name" data-sortable="true">Blok</th>
                        <th data-field="kolam_name" data-sortable="true">Kolam</th>
                        <th data-field="kenaikan_daging"  data-formatter="commaFormatter" data-sortable="true">Kenaikan Daging</th>
                        <th data-field="fcr"  data-formatter="commaFormatter" data-sortable="true">FCR</th>
                        <th data-field="adg"  data-formatter="commaFormatter" data-sortable="true">ADG</th>
                        <th data-field="action"
                            data-align="center"
                            data-formatter="actionFormatter">Aksi</th>
                    </tr>
                    </thead>
                </table>
                <div class="row">
                    <div class="col-sm-8">
                        <ul class="pagination">
                            <li class="page-item"><span class="page-link nav material-icons md-11 fa fa-angle-double-left" href="#"></span></li>
                            <li class="page-item"><span class="page-link nav material-icons md-11 fa fa-angle-left" href="#"></span></li>
                            <?php
                            for ($i=0; $i<= $page_count; $i++){
                                if($i < $max_data/$data_per_page){
                                    $page_num = $i + 1;
                                    if($i == 0){
                                        echo "<li class='page-item page active' index=$i><span class='page-link page' href=''#'>$page_num</span></li>";
                                    }else{
                                        echo "<li class='page-item page' index=$i><span class='page-link page' href=''#'>$page_num</span></li>";
                                    }
                                }
                            }
                            ?>
                            <li class="page-item"><span class="page-link nav material-icons md-11 fa fa-angle-right" href="#"></span></li>
                            <li class="page-item"><span class="page-link nav material-icons md-11 fa fa-angle-double-right" href="#"></span></li>
                        </ul>
                    </div>
                    <div class="col-sm-4 form-horizontal margin-up-down">
                        <div class="form-group">
                            <label for="select_data_count" class="control-label col-sm-8">Data per halaaman:</label>
                            <div class="col-sm-4">
                                <select id="select_data_count" class="form-control" name="select_data_count">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" value="<?=$page_count?>" name="page_count" id="page_count"/>
                <input type="hidden" value="<?=$max_data?>" name="max_data" id="max_data"/>
                <input type="hidden" value="<?php echo base_url() . index_page(); ?>/Mastersampling" name="route" id="route"/>

            </div>
        </section>
    </section>

    <!--main content end-->

</section>

<?php include 'footer.php' ?>

<script type="text/javascript">
    $(window).load(function(){
        var data = <?php echo $arr;?>;
        $(function() {
            $('#table').bootstrapTable({
                data: data,
            });
        });
    });

    $(document).ready(function(){
        $("#menu_sampling").addClass('active');
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
    });

    function actionFormatter(value, row) {
        temp = [];
        temp.push('<a href="<?php echo base_url() . index_page(); ?>/Mastersampling/show/' + row['id'] + '" class="btn btn-default waves-effect">Lihat</a>');
        <?php if ($_SESSION['role_id'] == 1) {?>
            if(row["sampling_gabungan"] == null){
                temp.push('<a href="<?php echo base_url() . index_page(); ?>/Mastersampling/delete/' + row['id'] + '" class="btn btn-danger waves-effect">Hapus</a>');
            }
        <?php } ?>
        return temp.join('');
    }
</script>

</body>
</html>
