<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Master Karyawan</title>

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
                    <div class="col-sm-8 page-title">Master Karyawan</div>
                    <div class="col-sm-4 right-align"><a href="<?php echo base_url() . index_page(); ?>/Masterkaryawan/create" class="btn btn-success btn-sm"><i class="fa fa-plus"></i><span> TAMBAH DATA</span></a></div>
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
                        <th data-field="name">Nama</th)>
                        <th data-field="telp">Telepon 1</th>
                        <th data-field="alamat">Alamat</th>
                        <th data-field="role_name">Role</th>
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
                <input type="hidden" value="<?php echo base_url() . index_page(); ?>/Masterkaryawan" name="route" id="route"/>

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
        $("#menu_karyawan").addClass('active');
        $("#sub_menu_master_data").css("display", "block");
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
    });

    function actionFormatter(value, row) {
        return [
            '<a href="<?php echo base_url() . index_page(); ?>/Masterkaryawan/update/' + row['id'] + '" class="btn btn-default waves-effect">Ubah</a>',
            '<a href="<?php echo base_url() . index_page(); ?>/Masterkaryawan/delete/' + row['id'] + '" class="btn btn-danger waves-effect">Hapus</a>',
        ].join('');
    }
</script>

</body>
</html>
