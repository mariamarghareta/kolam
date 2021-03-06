<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Monitoring Air</title>

    <?php include 'header.php' ?>

    <style>
        .col-container {
            display: table;
            width: 100%;
        }
        .col {
            display: table-cell;
        }
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
    <?php include 'sidebar_master.php'; ?>
    <!--sidebar end-->

    <!-- **********************************************************************************************************************************************************
    MAIN CONTENT
    *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <div class="col-md-8 col-md-offset-2 margin-up-md">
                <div class="w3-container w3-green page-title w3-center w3-padding-16">
                    Monitoring Air
                </div>

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                if ($state == "update"){
                    echo form_open('Monitoringair/update_data', $attributes);
                } else if ($state == "create"){
                    echo form_open('Monitoringair/add_new_data', $attributes);
                } else if ($state == "delete"){
                    echo form_open('Monitoringair/delete_data', $attributes);
                }
                ?>
                <input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>">
                <input type="hidden" name="pakan_id" id="pakan_id" value="<?php echo $pakan_id; ?>">
                <input type="hidden" name="tebar_id" id="tebar_id" value="<?php echo $tebar_id; ?>">
                <div class="w3-container w3-white w3-padding-32">
                    <div style="margin:10px 20px;">
                        <?php if ($state == "delete"){?>
                            <div style="margin-bottom: 20px; font-weight:bold;">Apakah Anda yakin menghapus data ini?</div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <label style="font-weight: bold">Blok</label><label style="color: red; padding-left: 5px;"> *</label>
                                <br>
                                <div id="div_blok" class="">
                                    <select id="tblok" name="tblok" <?php if ($state != "delete" and $state != "show"){ ?>class="selectpicker"<?php } else { ?> class="form-control" style="width:220px;" <?php } ?> data-live-search="true">
                                        <?php foreach($arr_blok as $row){
                                            if($row['id'] == $selected_blok){ ?>
                                                <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                            <?php } else { ?>
                                                <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                            <?php }} ?>
                                    </select>
                                </div>
                                <br>
                                <label style="font-weight: bold">Kolam</label><label style="color: red; padding-left: 5px;"> *</label>
                                <br>
                                <div id="div_blok" class="">
                                    <select id="tkolam" name="tkolam" class="form-control" style="width:220px;">
                                        <?php foreach($arr_kolam as $row){
                                            if($row['id'] == $selected_kolam){ ?>
                                                <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                            <?php } else { ?>
                                                <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                            <?php }} ?>
                                    </select>
                                </div>
                                <br>
                                <label style="font-weight: bold">Tinggi Air</label><label style="color: red; padding-left: 5px;"> *</label>
                                <?php echo form_input(array('name'=>'tinggi_air', 'id'=>'tinggi_air', 'class'=>'w3-input'), $tinggi_air);?>
                                <br>
                                <label style="font-weight: bold">Waktu Monitoring</label><label style="color: red; padding-left: 5px;"> *</label>
                                <select class="form-control" style="width: 220px" id="waktu_monitoring" name="waktu_monitoring">
                                    <option value="PAGI" <?php if($selected_waktu == "PAGI"){echo "selected";} ?> >PAGI (07.30)</option>
                                    <option value="SORE" <?php if($selected_waktu == "SORE"){echo "selected";} ?> >SORE (15.30)</option>
                                </select>
                                <br>
                                <label style="font-weight: bold">PH</label><label style="color: red; padding-left: 5px;"> *</label>
                                <?php echo form_input(array('name'=>'ph', 'id'=>'ph', 'class'=>'w3-input'), $ph);?>
                                <?php echo form_error('ph'); ?>
                                <br>
                                <label style="font-weight: bold">Suhu</label><label style="color: red; padding-left: 5px;"> *</label>
                                <?php echo form_input(array('name'=>'suhu', 'id'=>'suhu', 'class'=>'w3-input'), $suhu);?>
                                <?php echo form_error('suhu'); ?>
                                <br>
                                <label style="font-weight: bold">KCR</label><label style="color: red; padding-left: 5px;"> *</label>
                                <?php echo form_input(array('name'=>'kcr', 'id'=>'kcr', 'class'=>'w3-input'), $kcr);?>
                                <?php echo form_error('kcr'); ?>
                                <br>
                                <label style="font-weight: bold">Warna Air</label><label style="color: red; padding-left: 5px;"> *</label>
                                <?php echo form_input(array('name'=>'warna_air', 'id'=>'warna_air', 'class'=>'w3-input'), $warna_air);?>
                                <?php echo form_error('warna_air'); ?>
                                <br>
                                <label style="font-weight: bold">Keterangan</label>
                                <?php echo form_input(array('name'=>'keterangan', 'id'=>'keterangan', 'class'=>'w3-input'), $keterangan);?>
                                <?php echo form_error('keterangan'); ?>
                            </div>
                            <div class="col-sm-6">
                                <label style="font-weight: bold">Aplikasi Bahan Penolong</label>
                                <br>
                                <div id="div_obat" class="">
                                    <select id="tobat" name="tobat" <?php if ($state != "delete" and $state != "show"){ ?>class="selectpicker"<?php } else { ?> class="form-control" style="width:220px;" <?php } ?> data-live-search="true">
                                        <?php foreach($arr_obat as $row){
                                            if($row['id'] == $selected_obat){ ?>
                                                <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                            <?php } else { ?>
                                                <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                            <?php }} ?>
                                    </select>
                                </div>
                                <br>
                                <div class="" style="margin-bottom: 50px;">
                                    <div><label style="font-weight: bold">Jumlah Obat</label></div>
                                    <div class="col-xs-10 col-md-8 row">
                                        <?php echo form_input(array('name'=>'jumlah_obat', 'id'=>'jumlah_obat', 'class'=>'w3-input'), $jumlah_obat);?>
                                        <?php echo form_error('jumlah_obat'); ?>
                                    </div>
                                    <div class="col-xs-2 col-md-2" id="satuan" style="padding-top: 8px; text-align: center;">

                                    </div>
                                    <div class="col-xs-12 col-md-2 row" style="text-align: center;">
                                        <div class="right-align btn btn-success btn-sm" id="btn_add_obat"><i class="fa fa-plus"></i></div>
                                    </div>
                                </div>
                                <br>
                                <table
                                        id="table"
                                        data-toggle="true"
                                        data-show-columns="false"
                                        data-height="350">
                                    <thead>
                                    <tr>
                                        <th data-field="obat_name" data-sortable="true">Nama Obat</th)>
                                        <th data-field="jumlah" data-sortable="true" data-formatter="withSatuan" >Jumlah Obat</th)>
                                        <th data-field="action"
                                            data-align="center"
                                            data-formatter="actionFormatter">Aksi</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if ($state != "create"){ ?>
                        <div class="col-sm-12">
                            <h4>Tambahan Informasi</h4>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="col-sm-6">Dibuat Oleh</div>
                                <div class="col-sm-6">: <?php echo $create_user; ?> </div>
                                <div class="col-sm-6">Dibuat Pada</div>
                                <div class="col-sm-6">: <?php echo $create_time; ?> </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-6">Terakhir di Rubah Oleh</div>
                                <div class="col-sm-6">: <?php echo $write_user; ?> </div>
                                <div class="col-sm-6">Terakhir di Ubah Pada</div>
                                <div class="col-sm-6">: <?php echo $write_time; ?> </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($state == "update") { ?>
                        <div class="text-center">
                            <button name="write" value="write" type="submit" class="w3-button w3-green w3-center margin-up-md">Ubah Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Monitoringair">Batal</a></button>
                        </div>
                    <?php } else if ($state == "create"){ ?>
                        <div class="text-center">
                            <button name="add" value="save_data" type="submit" class="w3-button w3-green w3-center margin-up-md">Tambah Data</button>
                        </div>
                    <?php } else if ($state == "delete"){?>
                        <div class="text-center">
                            <button name="delete" value="delete" type="submit" class="w3-button w3-red w3-center margin-up-md">Hapus Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Monitoringair">Batal</a></button>
                        </div>
                    <?php }?>
                    <div class="margin-up-sm">
                        <?=$msg?>
                    </div>
                </div>
                <?php
                echo form_close();
                ?>

            </div>
        </section>
    </section>

    <!--main content end-->

</section>

<?php include 'footer.php' ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#menu_monitoring_air").addClass('active');
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
        getSatuan();
        getKolamInfo();
    });

    $(window).load(function(){
        var data = <?php echo $list_obat; ?> ;
        $(function() {
            $('#table').bootstrapTable({
                data: data,
            });
        });
    });

    if( "<?php echo $state ?>" == "delete" || "<?php echo $state ?>" == "show"){
        $("input[type=text]").prop('disabled', true);
        $("select").prop('disabled', true);
        $("#btn_add_obat").prop('disabled', true);
        $(".delete_obat_class").prop('disabled', true);
    }

    $("#tblok").change(function(){
        changeKolam($(this).val());
    });

    $("#tobat").change(function(){
        getSatuan();
    });

    $("#tkolam").change(function(){
        getKolamInfo();
    });

    $("#btn_add_obat").click(function(){
        add_obat_list();
    });

    function changeKolam($blok_id){
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Masterkolam/get_occupied_kolam"; ?>",
            dataType: "json",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', blok_id: $blok_id},
            success: function(data) {
                $temp = "";
                for(var $i=0; $i<data.length; $i++){
                    if($i == 0){
                        $temp+= "<option value='" + (data[$i]["id"]) + "' selected>" + (data[$i]["name"]) + "</option>";
                    } else{
                        $temp+= "<option value='" + (data[$i]["id"]) + "'>" + (data[$i]["name"]) + "</option>";
                    }

                }
                $("#tkolam").html($temp);
                getKolamInfo();
            }
        });
        return deferredData; // contains the passed data
    };

    function getSatuan(){
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Transaksipembelian/getSatuan"; ?>",
            dataType: "json",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                tipe: "o",
                id: $("#tobat").val()
            },
            success: function (data) {
                $("#satuan").html(data[0]["satuan"]);
            }
        });
        return deferredData; // contains the passed data
    };

    function actionFormatter(value, row) {
        return [
            '<button name="delete_obat" id=del_' + row['obat_id'] + ' type="button" onclick="remove_obat_list(this.id)" class="btn btn-danger waves-effect delete_obat_class" style="text-align: center;"><div class="fa fa-times"></div></button>'
        ].join('');
    };

    function withSatuan(value, row) {
        return row["jumlah"] + " " + row["satuan"];
    };

    function getKolamInfo(){
        $kolam_id = $("#tkolam").val();
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Monitoringpakan/getKolamInfo"; ?>",
            dataType: "json",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', kolam_id: $kolam_id},
            success: function(data) {
                for(var $i=0; $i<data.length; $i++){
                    $("#tebar_id").val(data[$i]["tebar_id"]);
                    $("#pakan_id").val(data[$i]["pemberian_pakan_id"]);
                }
            }
        });
        return deferredData; // contains the passed data
    }

    function add_obat_list(){
        $obat_id = $("#tobat").val();
        $jum = $("#jumlah_obat").val();
        $oname = $('#div_obat span.filter-option').html();
        $satuan = $('#satuan').html();

        if($jum > 0){
            var deferredData = new jQuery.Deferred();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . index_page() . "/Monitoringair/addObatList"; ?>",
                dataType: "json",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', obat_id: $obat_id, jumlah: $jum, obat_name: $oname, satuan:$satuan},
                success: function(data) {
                    $('#table').bootstrapTable("load", data);
                }
            });
            return deferredData; // contains the passed data
        }
    };


    function remove_obat_list(id){
        if( "<?php echo $state ?>" == "create" || "<?php echo $state ?>" == "update") {
            $obat_id = (id.substring(4));
            var deferredData = new jQuery.Deferred();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . index_page() . "/Monitoringair/removeObatList"; ?>",
                dataType: "json",
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                    obat_id: $obat_id
                },
                success: function (data) {
                    $('#table').bootstrapTable("load", data);
                }
            });
            return deferredData; // contains the passed data
        }
    };
</script>

</body>
</html>
