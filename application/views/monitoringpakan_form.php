<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Monitoring Pakan</title>

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
                    Monitoring Pakan
                </div>

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                if ($state == "update"){
                    echo form_open('Monitoringpakan/update_data', $attributes);
                } else if ($state == "create"){
                    echo form_open('Monitoringpakan/add_new_data', $attributes);
                } else if ($state == "delete"){
                    echo form_open('Monitoringpakan/delete_data', $attributes);
                }
                ?>
                <input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>">
                <input type="hidden" name="kolam_id" id="kolam_id" value="<?php echo $kolam_id; ?>">
                <input type="hidden" name="kolam_id_before" id="kolam_id" value="<?php echo $kolam_id; ?>">
                <input type="hidden" name="pakan_id" id="pakan_id" value="<?php echo $pakan_id; ?>">
                <input type="hidden" name="tebar_id" id="tebar_id" value="<?php echo $tebar_id; ?>">
                <input type="hidden" name="pemberian_pakan_id" id="pemberian_pakan_id" value="<?php echo $pemberian_pakan_id; ?>">
                <input type="hidden" name="selected_pakan_before" id="selected_pakan_before" value="<?php echo $selected_pakan_before; ?>">
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
                                <label>Tanggal Tebar</label>
                                <?php echo form_input(array('name'=>'tgl_tebar', 'id'=>'tgl_tebar', 'class'=>'w3-input', 'readonly' => 'readonly'), $tgl_tebar);?>
                                <br>
                                <label>Jumlah Bibit</label>
                                <?php echo form_input(array('name'=>'total_ikan', 'id'=>'total_ikan', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_ikan);?>
                                <br>
                                <label>Size</label>
                                <?php echo form_input(array('name'=>'size', 'id'=>'size', 'class'=>'w3-input', 'readonly' => 'readonly'), $size);?>
                                <?php echo form_error('size'); ?>
                                <br>
                                <label>Biomass</label>
                                <?php echo form_input(array('name'=>'biomass', 'id'=>'biomass', 'class'=>'w3-input', 'readonly' => 'readonly'), $biomass);?>
                                <?php echo form_error('biomass'); ?>
                            </div>
                            <div class="col-sm-6">
                                <label style="font-weight: bold">Waktu Pemberian Makan</label><label style="color: red; padding-left: 5px;"> *</label>
                                <select class="form-control" style="width: 220px" id="waktu_pakan" name="waktu_pakan">
                                    <option value="PAGI" <?php if($selected_waktu == "PAGI"){echo "selected";} ?> >PAGI</option>
                                    <option value="SORE" <?php if($selected_waktu == "SORE"){echo "selected";} ?> >SORE</option>
                                    <option value="MALAM" <?php if($selected_waktu == "MALAM"){echo "selected";} ?> >MALAM</option>
                                </select>
                                <br>
                                <label style="font-weight: bold">MR</label><label style="color: red; padding-left: 5px;"> *</label>
                                <?php echo form_input(array('name'=>'mr', 'id'=>'mr', 'class'=>'w3-input'), $mr);?>
                                <?php echo form_error('mr'); ?>
                                <br>
                                <label style="font-weight: bold">Keterangan</label>
                                <?php echo form_input(array('name'=>'keterangan', 'id'=>'keterangan', 'class'=>'w3-input'), $keterangan);?>
                                <?php echo form_error('keterangan'); ?>
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
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Monitoringpakan">Batal</a></button>
                        </div>
                    <?php } else if ($state == "create"){ ?>
                        <div class="text-center">
                            <button name="add" type="submit" class="w3-button w3-green w3-center margin-up-md">Tambah Data</button>
                        </div>
                    <?php } else if ($state == "delete"){?>
                        <div class="text-center">
                            <button name="delete" value="delete" type="submit" class="w3-button w3-red w3-center margin-up-md">Hapus Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Monitoringpakan">Batal</a></button>
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
        $("#menu_monitoring_pakan").addClass('active');
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
        if("<?=$state?>" == "update" || "<?=$state?>" == "show" || "<?=$state?>" == "delete"){
            getKolamInfoShow(<?=$pemberian_pakan_id?>);
        } else if("<?=$state?>" == "create"){
            getKolamInfo();
        }

//        getSatuan();
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

    $("#tkolam").change(function(){
        getKolamInfo();
    });

    function getData(param1) {
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Mastertabelpakan/getpakan"; ?>",
            dataType: "json",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', size: param1},
            success: function(data) {
                if(typeof data[0] === "undefined"){
                    $("#fr").val(0);
                    $("#sr").val(0);
                }else{
                    data = data[0];
                    $("#fr").val(data["fr"]);
                    $("#sr").val(data["sr"]);
                }
                calculate();
            }
        });
        return deferredData; // contains the passed data
    };

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

    function getKolamInfo(){
        $kolam = $("#tkolam").val();
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Monitoringpakan/getKolamInfo"; ?>",
            dataType: "json",
            data: {kolam_id: $kolam},
            success: function(data) {
                for(var $i=0; $i<data.length; $i++){
                    $("#total_ikan").val(data[$i]["total_ikan"]);
                    $("#biomass").val(data[$i]["biomass"]);
                    $("#size").val(data[$i]["size"]);
                    $("#tgl_tebar").val(data[$i]["tgl_tebar"]);
                    $("#tebar_id").val(data[$i]["tebar_id"]);
                    $("#pemberian_pakan_id").val(data[$i]["pemberian_pakan_id"]);
                }
            }
        });
        return deferredData; // contains the passed data
    }

    function getKolamInfoShow(ppid){
        $pk = ppid;
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Monitoringpakan/getKolamInfoShow"; ?>",
            dataType: "json",
            data: {pemberian_pakan_id: $pk},
            success: function(data) {
                for(var $i=0; $i<data.length; $i++){
                    $("#total_ikan").val(data[$i]["total_ikan"]);
                    $("#biomass").val(data[$i]["biomass"]);
                    $("#size").val(data[$i]["size"]);
                    $("#tgl_tebar").val(data[$i]["tgl_tebar"]);
                    $("#tebar_id").val(data[$i]["tebar_id"]);
                    $("#pemberian_pakan_id").val(data[$i]["id"]);
                }
            }
        });
        return deferredData; // contains the passed data
    }

    $("#tobat").change(function(){
        getSatuan();
    });

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

    function withSatuan(value, row) {
        return row["jumlah"] + " " + row["satuan"];
    };

    function actionFormatter(value, row) {
        return [
            '<button name="delete_obat" id=del_' + row['obat_id'] + ' type="button" onclick="remove_obat_list(this.id)" class="btn btn-danger waves-effect delete_obat_class" style="text-align: center;"><div class="fa fa-times"></div></button>'
        ].join('');
    };

    $("#btn_add_obat").click(function(){
        add_obat_list();
    });

    function add_obat_list(){
        $obat_id = $("#tobat").val();
        $jum = $("#jumlah_obat").val();
        $oname = $('#div_obat span.filter-option').html();
        $satuan = $('#satuan').html();

        if($jum > 0){
            var deferredData = new jQuery.Deferred();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . index_page() . "/Monitoringpakan/addObatList"; ?>",
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
                url: "<?php echo base_url() . index_page() . "/Monitoringpakan/removeObatList"; ?>",
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
