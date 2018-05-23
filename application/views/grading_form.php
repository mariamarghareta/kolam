<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Grading</title>

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
            <div class="col-md-12 margin-up-md">
                <div class="w3-container w3-green page-title w3-center w3-padding-16">
                    Grading
                </div>

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                if ($state == "update"){
                    echo form_open('Mastergrading/update_data', $attributes);
                } else if ($state == "create"){
                    echo form_open('Mastergrading/add_new_data', $attributes);
                } else if ($state == "delete"){
                    echo form_open('Mastergrading/delete_data', $attributes);
                }
                ?>
                <input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>">
                <input type="hidden" name="kolam_id" id="kolam_id" value="<?php echo $kolam_id; ?>">
                <input type="hidden" name="sampling_id" id="sampling_id" value="<?php echo $sampling_id; ?>">
                <input type="hidden" name="tebar_id" id="tebar_id" value="<?php echo $tebar_id; ?>">
                <input type="hidden" name="total_pakan_monitoring" id="total_pakan_monitoring" value="<?php echo $total_pakan_monitoring; ?>">
                <input type="hidden" name="k_total_ikan" id="k_total_ikan" value="<?php echo $k_total_ikan; ?>">
                <input type="hidden" name="k_biomass" id="k_biomass" value="<?php echo $k_biomass; ?>">
                <input type="hidden" name="selected_kolam_txt" id="selected_kolam_txt" value="<?php echo $selected_kolam_txt; ?>">
                <div class="w3-container w3-white w3-padding-32">
                    <div style="margin:10px 20px;" class="row" id="div-header">
                        <div class="col-sm-12 page-title" style="margin-bottom:25px;">Input Data Grading</div>
                        <?php if ($state == "delete"){?>
                            <div style="margin-bottom: 20px; font-weight:bold;">Apakah Anda yakin menghapus data ini?</div>
                        <?php } ?>
                        <div class="col-sm-6" style="padding-right:20px;">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6"><label style="font-weight: bold">Blok Asal</label><label style="color: red; padding-left: 5px;"> *</label></div>
                                <div class="col-sm-6 col-xs-6"><label style="font-weight: bold">Kolam Asal</label><label style="color: red; padding-left: 5px;"> *</label></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-xs-6">
                                    <div id="div_blok" class="">
                                        <select id="tblok" name="tblok" <?php if ($state != "delete"){ ?>class="selectpicker"<?php } else { ?> class="form-control" style="width:220px;" <?php } ?> data-live-search="true">
                                            <?php foreach($arr_blok as $row){
                                                if($row['id'] == $selected_blok){ ?>
                                                    <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                                <?php } else { ?>
                                                    <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                                <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
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
                                </div>
                            </div>
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-sm-6 col-xs-6"><label style="font-weight: bold">Blok Tujuan</label><label style="color: red; padding-left: 5px;"> *</label></div>
                                <div class="col-sm-6 col-xs-6"><label style="font-weight: bold">Kolam Tujuan</label><label style="color: red; padding-left: 5px;"> *</label></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-xs-6">
                                    <div id="div_blok" class="">
                                        <select id="tblok_tujuan" name="tblok_tujuan" <?php if ($state != "delete"){ ?>class="selectpicker"<?php } else { ?> class="form-control" style="width:220px;" <?php } ?> data-live-search="true">
                                            <option value="-">Tidak dipindah</option>
                                            <?php foreach($arr_blok_tujuan as $row){
                                                if($row['id'] == $selected_blok_tujuan){ ?>
                                                    <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                                <?php } else { ?>
                                                    <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                                <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div id="div_blok" class="">
                                        <select id="tkolam_tujuan" name="tkolam_tujuan" class="form-control" style="width:220px;">
                                            <?php foreach($arr_kolam_tujuan as $row){
                                                if($row['id'] == $selected_kolam_tujuan){ ?>
                                                    <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                                <?php } else { ?>
                                                    <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                                <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <label style="font-weight: bold">Sampling</label><label style="color: red; padding-left: 5px;"> *</label>
                            <div class="row">
                                <div class="col-xs-7 col-md-4 col-sm-11">
                                    <?php echo form_input(array('name'=>'sampling', 'id'=>'sampling', 'class'=>'w3-input'), $sampling);?>
                                    <?php echo form_error('sampling'); ?>
                                </div>
                                <div class="col-xs-5 col-md-2 col-sm-1" style="padding-top:8px;">
                                    ekor tiap
                                </div>
                                <div class="col-xs-6 col-md-3 col-sm-10">
                                    <?php echo form_input(array('name'=>'tangka', 'id'=>'tangka', 'class'=>'w3-input'), $tangka);?>
                                </div>
                                <div class="col-xs-6 col-md-3 col-sm-2" style="padding-top:8px;">
                                    <select class="form-control" id="tsatuan" name="tsatuan">
                                        <option value="ons">Ons</option>
                                        <option value="kg">Kg</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <label style="font-weight: bold">Ukuran</label><label style="color: red; padding-left: 5px;"> *</label>
                            <?php echo form_input(array('name'=>'ukuran', 'id'=>'ukuran', 'class'=>'w3-input'), $ukuran);?>
                            <?php echo form_error('ukuran'); ?>
                            <br>
                            <label style="font-weight: bold">Biomass (kg)</label><label style="color: red; padding-left: 5px;"> *</label>
                            <?php echo form_input(array('name'=>'biomass', 'id'=>'biomass', 'class'=>'w3-input'), $biomass);?>
                            <?php echo form_error('biomass'); ?>
                            <br>
                            <label>Size (ekor/kg)</label>
                            <?php echo form_input(array('name'=>'size', 'id'=>'size', 'class'=>'w3-input', 'readonly' => 'readonly'), $size);?>
                            <?php echo form_error('size'); ?>
                            <br>
                            <label>Jumlah Ikan</label>
                            <?php echo form_input(array('name'=>'total_ikan', 'id'=>'total_ikan', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_ikan);?>
                            <?php echo form_error('total_ikan'); ?>
                        </div>
                        <div class="col-sm-6">
                            <label>FR</label>
                            <?php echo form_input(array('name'=>'fr', 'id'=>'fr', 'class'=>'w3-input', 'readonly' => 'readonly'), $fr);?>
                            <?php echo form_error('fr'); ?>
                            <br>
                            <label>SR</label>
                            <?php echo form_input(array('name'=>'sr', 'id'=>'sr', 'class'=>'w3-input', 'readonly' => 'readonly'), $sr);?>
                            <?php echo form_error('sr'); ?>
                            <br>
                            <label>Dosis Pakan (kg/hari)</label>
                            <?php echo form_input(array('name'=>'dosis_pakan', 'id'=>'dosis_pakan', 'class'=>'w3-input', 'readonly' => 'readonly'), $dosis_pakan);?>
                            <?php echo form_error('dosis_pakan'); ?>
                            <br>
                            <label>Total Pakan 7 Hari</label>
                            <?php echo form_input(array('name'=>'total_pakan', 'id'=>'total_pakan', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_pakan);?>
                            <?php echo form_error('total_pakan'); ?>
                            <br>
                            <label>Pagi</label>
                            <div class="row">
                                <div class="col-xs-10 col-md-5 col-sm-10">
                                    <?php echo form_input(array('name'=>'pagi', 'id'=>'pagi', 'class'=>'w3-input', 'readonly' => 'readonly'), $pagi);?>
                                    <?php echo form_error('pagi'); ?>
                                </div>
                                <div class="col-xs-2 col-md-1 col-sm-2" style="padding-top:8px;">
                                    kg
                                </div>
                                <div class="col-xs-10 col-md-5 col-sm-10">
                                    <?php echo form_input(array('name'=>'pagi_gr', 'id'=>'pagi_gr', 'class'=>'w3-input', 'readonly' => 'readonly'), $pagi);?>
                                </div>
                                <div class="col-xs-2 col-md-1 col-sm-2" style="padding-top:8px;">
                                    gr
                                </div>
                            </div>

                            <br>
                            <label>Sore (kg)</label>
                            <div class="row">
                                <div class="col-xs-10 col-md-5 col-sm-10">
                                    <?php echo form_input(array('name'=>'sore', 'id'=>'sore', 'class'=>'w3-input', 'readonly' => 'readonly'), $sore);?>
                                    <?php echo form_error('sore'); ?>
                                </div>
                                <div class="col-xs-2 col-md-1 col-sm-2" style="padding-top:8px;">
                                    kg
                                </div>
                                <div class="col-xs-10 col-md-5 col-sm-10">
                                    <?php echo form_input(array('name'=>'sore_gr', 'id'=>'sore_gr', 'class'=>'w3-input', 'readonly' => 'readonly'), $sore);?>
                                </div>
                                <div class="col-xs-2 col-md-1 col-sm-2" style="padding-top:8px;">
                                    gr
                                </div>
                            </div>
                            <br>
                            <label>Malam (kg)</label>
                            <div class="row">
                                <div class="col-xs-10 col-md-5 col-sm-10">
                                    <?php echo form_input(array('name'=>'malam', 'id'=>'malam', 'class'=>'w3-input', 'readonly' => 'readonly'), $malam);?>
                                    <?php echo form_error('malam'); ?>
                                </div>
                                <div class="col-xs-2 col-md-1 col-sm-2" style="padding-top:8px;">
                                    kg
                                </div>
                                <div class="col-xs-10 col-md-5 col-sm-10">
                                    <?php echo form_input(array('name'=>'malam_gr', 'id'=>'malam_gr', 'class'=>'w3-input', 'readonly' => 'readonly'), $malam);?>
                                </div>
                                <div class="col-xs-2 col-md-1 col-sm-2" style="padding-top:8px;">
                                    gr
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" id="btn-add-item">
                        <div class="w3-button w3-green w3-center margin-up-md" id="btn_add_item">Tambah Item</div>
                    </div>
                    <div id="msg" class="margin-up-sm row">
                        <?=$msg?>
                    </div>
                    <div class="col-sm-12 page-title" style="margin:25px 0px;">Data Item Grading</div>
                    <div class="col-sm-12" id="asal_kolam_txt" name="asal_kolam_txt" style="font-weight: bold; font-size: 14pt;"></div>
                    <div class="col-sm-12">
                        <table
                            id="table"
                            data-toggle="true"
                            data-show-columns="false"
                            data-height="350">
                            <thead>
                            <tr>
                                <th data-field="tujuan_kolam" data-sortable="true" data-formatter="tujuanKolamFormat">Tujuan Kolam</th>
                                <th data-field="sampling" data-sortable="true">Sampling (ekor)</th>
                                <th data-field="angka" data-sortable="true" data-formatter="withSatuan">Tiap</th>
                                <th data-field="ukuran" data-sortable="true">Ukuran</th>
                                <th data-field="biomass" data-sortable="true">Biomass</th>
                                <th data-field="size" data-sortable="true">Size (ekor/kg)</th>
                                <th data-field="total_ikan" data-sortable="true">Total Ikan</th>
                                <th data-field="fr" data-sortable="true">FR</th>
                                <th data-field="sr" data-sortable="true">SR</th>
                                <th data-field="dosis_pakan" data-sortable="true">Dosis Pakan (kg/hari)</th>
                                <th data-field="total_pakan" data-sortable="true">Total Pakan 7 hari</th>
                                <th data-field="pagi" data-sortable="true">Pagi</th>
                                <th data-field="sore" data-sortable="true">Sore</th>
                                <th data-field="malam" data-sortable="true">Malam</th>
                                <?php if ($state == "create"){?>
                                <th data-field="action"
                                    data-align="center"
                                    data-formatter="actionFormatter">Aksi</th>
                                <?php } ?>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-sm-12 margin-up-md">
                        <div class="col-sm-6">
                            <label>Total Berat Biomass Panen</label>
                            <?php echo form_input(array('name'=>'total_biomass', 'id'=>'total_biomass', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_biomass);?>
                            <br>
                            <label>Total Populasi Ikan</label>
                            <?php echo form_input(array('name'=>'total_populasi', 'id'=>'total_populasi', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_populasi);?>
                            <br>
                            <label>SR (Survival Rate)</label>
                            <?php echo form_input(array('name'=>'sr_akhir', 'id'=>'sr_akhir', 'class'=>'w3-input', 'readonly' => 'readonly'), $sr_akhir);?>
                        </div>
                        <div class="col-sm-6">
                            <label>Pertumbuhan Daging</label>
                            <?php echo form_input(array('name'=>'pertumbuhan_daging', 'id'=>'pertumbuhan_daging', 'class'=>'w3-input', 'readonly' => 'readonly'), $pertumbuhan_daging);?>
                            <br>
                            <label>FCR</label>
                            <?php echo form_input(array('name'=>'fcr', 'id'=>'fcr', 'class'=>'w3-input', 'readonly' => 'readonly'), $fcr);?>
                            <br>
                            <label>ADG</label>
                            <?php echo form_input(array('name'=>'adg', 'id'=>'adg', 'class'=>'w3-input', 'readonly' => 'readonly'), $adg);?>
                        </div>
                    </div>
                    <?php if ($state == "update") { ?>
                        <div class="text-center">
                            <button name="write" value="write" type="submit" class="w3-button w3-green w3-center margin-up-md">Ubah Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Mastergrading">Batal</a></button>
                        </div>
                    <?php } else if ($state == "create"){ ?>
                        <div class="text-center">
                            <button name="add" type="submit" class="w3-button w3-green w3-center margin-up-md">Simpan Data</button>
                        </div>
                    <?php } else if ($state == "delete"){?>
                        <div class="text-center">
                            <button name="delete" value="delete" type="submit" class="w3-button w3-red w3-center margin-up-md">Hapus Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Mastergrading">Batal</a></button>
                        </div>
                    <?php }?>
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
        $("#menu_grading").addClass('active');
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
        if($("#tblok_tujuan").val() == '-'){
            $("#tkolam_tujuan").prop('disabled', true);
        } else {
            $("#tkolam_tujuan").prop('disabled', false);
        }
        calculate_sampling();
        calculate();
        getKolamDetailforGrading();
    });

    $(window).load(function(){
        var data = <?php echo $list_grading; ?> ;
        $(function() {
            $('#table').bootstrapTable({
                data: data,
            });
        });
    });

    if( "<?php echo $state ?>" == "delete" || "<?php echo $state ?>" == "show"){
        $("input[type=text]").prop('readonly', true);
        $("select").prop('disabled', true);
        $("#div-header").hide();
        $("#btn-add-item").hide();
        var content = "<?php echo "Asal Kolam: $selected_kolam_txt"; ?>";
        $("#asal_kolam_txt").html(content);
    }

    $("#tangka").keyup(function(){
        calculate_sampling();
    });

    $("#tsatuan").change(function(){
        calculate_sampling();
    });

    $("#tkolam").change(function(){
        getKolamDetailforGrading();
    });

    $("#tblok_tujuan").change(function(){
        if($("#tblok_tujuan").val() == '-'){
            $("#tkolam_tujuan").prop('disabled', true);
            $("#tkolam_tujuan").html('');
        } else {
            $("#tkolam_tujuan").prop('disabled', false);
            changeKolamAvailable($("#tblok_tujuan").val());
        }

    });

    $("#sampling").keyup(function(){
        calculate_sampling();
        calculate();
    });

    $("#biomass").keyup(function(){
        calculate();
    });

    $("#btn_add_item").click(function(){
        add_grading_list();
    });

    function calculate_sampling(){
        $angka = $("#tangka").val();
        $satuan = $("#tsatuan").val();
        sampling = $("#sampling").val();
        $var = 1;
        if($satuan == "ons"){
            $var = 10;
        }
        size = (sampling/$angka*$var).toFixed(0);
        $("#size").val(size);
        getData(size);
    }

    function calculate(){
        biomass = $("#biomass").val();
        size = $("#size").val();
        fr = $("#fr").val()/100;
        sr = $("#sr").val()/100;
        total_ikan = (biomass*size).toFixed(0);
        dosis_pakan = biomass * fr * sr;
        $("#total_ikan").val(total_ikan);
        $("#dosis_pakan").val((dosis_pakan).toFixed(4));
        $("#total_pakan").val((dosis_pakan*7).toFixed(4));
        $("#pagi").val((dosis_pakan*0.3).toFixed(3));
        $("#pagi_gr").val($("#pagi").val() * 1000);
        $("#sore").val((dosis_pakan*0.3).toFixed(3));
        $("#sore_gr").val($("#sore").val() * 1000);
        $("#malam").val((dosis_pakan*0.4).toFixed(3));
        $("#malam_gr").val($("#malam").val() * 1000);
    }

    $("#tblok").change(function(){
        changeKolam($(this).val());
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
                getKolamDetailforGrading();
            }
        });
        return deferredData; // contains the passed data
        getKolamDetailforGrading();
    };

    function changeKolamAvailable($blok_id){
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Masterkolam/get_available_kolam"; ?>",
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
                $("#tkolam_tujuan").html($temp);
            }
        });
        return deferredData; // contains the passed data
    };

    function getKolamDetailforGrading(){
        $kolam_id = $("#tkolam").val();
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Mastergrading/getKolamDetailforGrading"; ?>",
            dataType: "json",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', kolam_id: $kolam_id},
            success: function(data) {
                $("#total_pakan_monitoring").val(data[0]);
                $("#k_biomass").val(data[2]);
                $("#k_total_ikan").val(data[1]);
                $("#tebar_id").val(data[3]);
                $("#sampling_id").val(data[4]);
                compute_header();
            }
        });
        return deferredData; // contains the passed data

    };

    function actionFormatter(value, row) {
        return [
            '<button name="delete_obat" id=del_' + row['urutan'] + ' type="button" onclick="remove_detail_list(this.id)" class="btn btn-danger waves-effect delete_obat_class" style="text-align: center;"><div class="fa fa-times"></div></button>'
        ].join('');
    };

    function withSatuan(value, row) {
        return row["angka"] + " " + row["satuan"];
    };

    function tujuanKolamFormat(value, row) {
        return row["blok_tujuan_txt"] + " " + row["kolam_tujuan_txt"];
    };

    function compute_header(){
        if( "<?php echo $state ?>" != "delete" && "<?php echo $state ?>" != "show") {
            $sr = ($("#total_populasi").val() / $("#k_total_ikan").val() * 100).toFixed(2);
            $pertumbuhan_daging = ($("#total_biomass").val() - $("#k_biomass").val()).toFixed(2);
            if ($pertumbuhan_daging == 0) {
                $fcr = 0;
            } else {
                $fcr = ($("#total_pakan_monitoring").val() / $pertumbuhan_daging).toFixed(2);
            }
            $adg = ($pertumbuhan_daging / 8).toFixed(2);

//        if($("#total_populasi").val() <= 0){$pertumbuhan_daging = 0; $fcr = 0; $adg = 0; }
            $("#sr_akhir").val($sr);
            if($("#total_biomass").val() > 0){
                $("#pertumbuhan_daging").val($pertumbuhan_daging);
                $("#adg").val($adg);
            }
            $("#fcr").val($fcr);
        }
    }

    function add_grading_list(){
        $ukuran = $("#ukuran").val();
        $sampling = $("#sampling").val();
        $angka = $("#tangka").val();
        $satuan = $("#tsatuan").val();
        $biomass = $("#biomass").val();
        $size = $("#size").val();
        $total_ikan = $("#total_ikan").val();
        $fr = $("#fr").val();
        $sr = $("#sr").val();
        $dosis_pakan = $("#dosis_pakan").val();
        $total_pakan = $("#total_pakan").val();
        $pagi = $("#pagi").val();
        $sore = $("#sore").val();
        $malam = $("#malam").val();
        $kolam_tujuan = $("#tkolam_tujuan").val();
        $blok_tujuan = $("#tblok_tujuan").val();
        $kolam_tujuan_txt = $("#tkolam_tujuan option:selected").text();
        $blok_tujuan_txt = $("#tblok_tujuan option:selected").text();

        if(isNaN($sampling) == false && isNaN($biomass) == false && $ukuran != ''){
            var deferredData = new jQuery.Deferred();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . index_page() . "/Mastergrading/addItemList"; ?>",
                dataType: "json",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                    ukuran: $ukuran,
                    sampling: $sampling,
                    angka: $angka,
                    satuan: $satuan,
                    biomass: $biomass,
                    size: $size,
                    total_ikan: $total_ikan,
                    fr: $fr,
                    sr: $sr,
                    dosis_pakan: $dosis_pakan,
                    total_pakan: $total_pakan,
                    pagi: $pagi,
                    sore: $sore,
                    malam: $malam,
                    blok_tujuan: $blok_tujuan,
                    kolam_tujuan: $kolam_tujuan,
                    kolam_tujuan_txt: $kolam_tujuan_txt,
                    blok_tujuan_txt: $blok_tujuan_txt
                },
                success: function(data) {
                    $('#table').bootstrapTable("load", data[0]);
                    $('#total_biomass').val(data[2]);
                    $('#total_populasi').val(data[3]);
                    if(data[1] == 0){
                        $("#msg").html('<div id="err_msg" style="text-align: center;" class="col-sm-6 col-sm-offset-3 alert alert-danger sldown">Tujuan kolam tidak boleh kembar</div>');
                    } else {
                        $("#msg").html('');
                    }
                    compute_header();
                }
            });
            return deferredData; // contains the passed data
            $("#msg").html();
        } else {
            $("#msg").html('<div id="err_msg" style="text-align: center" class="col-sm-6 col-sm-offset-3 alert alert-danger sldown">Data sampling dan biomass harus berupa angka. Ukuran tidak boleh kosong.</div>');
        }
    };

    function remove_detail_list(id){
        $urutan = (id.substring(4));
        if( "<?php echo $state ?>" != "delete"){
            var deferredData = new jQuery.Deferred();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . index_page() . "/Mastergrading/removeDetailList"; ?>",
                dataType: "json",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', urutan: $urutan},
                success: function(data) {
                    $('#table').bootstrapTable("load", data[0]);
                    $('#total_biomass').val(data[1]);
                    $('#total_populasi').val(data[2]);
                    compute_header();
                }
            });
            return deferredData; // contains the passed data
        }
    };

</script>

</body>
</html>
