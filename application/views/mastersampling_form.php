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
                    Sampling
                </div>

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                if ($state == "update"){
                    echo form_open('Mastersampling/update_data', $attributes);
                } else if ($state == "create"){
                    echo form_open('Mastersampling/add_new_data', $attributes);
                } else if ($state == "delete"){
                    echo form_open('Mastersampling/delete_data', $attributes);
                }
                ?>
                <input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>">
                <input type="hidden" name="his_id" id="his_id" value="<?php echo $his_id; ?>">
                <input type="hidden" name="pakan_id" id="pakan_id" value="<?php echo $pakan_id; ?>">
                <input type="hidden" name="kolam_id" id="kolam_id" value="<?php echo $kolam_id; ?>">
                <input type="hidden" name="sampling_id" id="sampling_id" value="<?php echo $sampling_id; ?>">
                <div class="w3-container w3-white w3-padding-32">
                    <div style="margin:10px 20px;">
                        <?php if ($state == "delete"){?>
                            <div style="margin-bottom: 20px; font-weight:bold;">Apakah Anda yakin menghapus data ini?</div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <label style="font-weight: bold">Blok </label><label style="color: red; padding-left: 5px;"> *</label>
                                <br>
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
                                <label>Size (ekor/kg)</label>
                                <?php echo form_input(array('name'=>'size', 'id'=>'size', 'class'=>'w3-input', 'readonly' => 'readonly'), $size);?>
                                <?php echo form_error('size'); ?>
                                <br>
                                <label>Jumlah Ikan</label>
                                <?php echo form_input(array('name'=>'total_ikan', 'id'=>'total_ikan', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_ikan);?>
                                <?php echo form_error('total_ikan'); ?>
                                <br>

                                <div class="row">
                                    <div class="col-xs-12 col-md-6 col-sm-12">
                                        <label>Biomass (kg)</label>
                                        <?php echo form_input(array('name'=>'biomass', 'id'=>'biomass', 'class'=>'w3-input', 'readonly' => 'readonly'), $biomass);?>
                                        <?php echo form_error('biomass'); ?>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-sm-12">
                                        <label>Biomass Sebelumnya(kg)</label>
                                        <?php echo form_input(array('name'=>'biomass_before', 'id'=>'biomass_before', 'class'=>'w3-input', 'readonly' => 'readonly'), $biomass_before);?>
                                        <?php echo form_error('biomass_before'); ?>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xs-12 col-md-6 col-sm-12">
                                        <label>FR</label>
                                        <?php echo form_input(array('name'=>'fr', 'id'=>'fr', 'class'=>'w3-input', 'readonly' => 'readonly'), $fr);?>
                                        <?php echo form_error('fr'); ?>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-sm-12">
                                        <label>SR</label>
                                        <?php echo form_input(array('name'=>'sr', 'id'=>'sr', 'class'=>'w3-input', 'readonly' => 'readonly'), $sr);?>
                                        <?php echo form_error('sr'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Dosis Pakan (kg/hari)</label>
                                <?php echo form_input(array('name'=>'dosis_pakan', 'id'=>'dosis_pakan', 'class'=>'w3-input', 'readonly' => 'readonly'), $dosis_pakan);?>
                                <?php echo form_error('dosis_pakan'); ?>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6" style="padding: 0px;" >
                                        <div class="col-sm-12"><label>Total Pakan 7 Hari</label></div>
                                        <div class="col-xs-10 col-md-10 col-sm-10">
                                            <?php echo form_input(array('name'=>'total_pakan', 'id'=>'total_pakan', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_pakan);?>
                                            <?php echo form_error('total_pakan'); ?>
                                        </div>
                                        <div class="col-xs-2 col-md-2 col-sm-2" style="padding-top: 8px;">
                                            kg
                                        </div>
                                    </div>
                                    <div class="col-sm-6" style="padding: 0px;">
                                        <div class="col-sm-12"><label>Total Pakan 7 Hari Sebelumnya</label></div>
                                        <div class="col-xs-10 col-md-10 col-sm-10">
                                            <?php echo form_input(array('name'=>'total_pakan_before', 'id'=>'total_pakan_before', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_pakan_before);?>
                                        </div>
                                        <div class="col-xs-2 col-md-2 col-sm-2" style="padding-top: 8px;">
                                            kg
                                        </div>
                                    </div>
                                </div>
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
                                <br>
                                <label>Kenaikan daging (kg)</label>
                                <?php echo form_input(array('name'=>'kenaikan_daging', 'id'=>'kenaikan_daging', 'class'=>'w3-input', 'readonly' => 'readonly'), $kenaikan_daging);?>
                                <br>
                                <label>Feed Conversion Rate (FCR)</label>
                                <?php echo form_input(array('name'=>'fcr', 'id'=>'fcr', 'class'=>'w3-input', 'readonly' => 'readonly'), $fcr);?>
                                <br>
                                <label>Penambahan Berat Tiap Hari (ADG)</label>
                                <?php echo form_input(array('name'=>'adg', 'id'=>'adg', 'class'=>'w3-input', 'readonly' => 'readonly'), $adg);?>
                            </div>
                        </div>
                    </div>
                    <?php if ($state == "update") { ?>
                        <div class="text-center">
                            <button name="write" value="write" type="submit" class="w3-button w3-green w3-center margin-up-md">Ubah Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Mastersampling">Batal</a></button>
                        </div>
                    <?php } else if ($state == "create"){ ?>
                        <div class="text-center">
                            <button name="add" type="submit" class="w3-button w3-green w3-center margin-up-md">Tambah Data</button>
                        </div>
                    <?php } else if ($state == "delete"){?>
                        <div class="text-center">
                            <button name="delete" value="delete" type="submit" class="w3-button w3-red w3-center margin-up-md">Hapus Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Mastersampling">Batal</a></button>
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
        $("#menu_sampling").addClass('active');
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
        calculate_sampling();
        calculate();
        getTotalIkan();
    });

    if( "<?php echo $state ?>" == "delete"){
        $("input[type=text]").prop('disabled', true);
        $("select").prop('disabled', true);
    }

    $("#sampling").keyup(function(){
        calculate_sampling();
        calculate();
    });

    $("#biomass").keyup(function(){
        calculate();
    });

    $("#tangka").keyup(function(){
        calculate_sampling();
    });

    $("#tsatuan").change(function(){
        calculate_sampling();
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
        total_ikan = $("#total_ikan").val();
        size = $("#size").val();
        fr = $("#fr").val()/100;
        sr = $("#sr").val()/100;
        biomass = (total_ikan/size).toFixed(4);
        dosis_pakan = biomass * fr * sr;
        if(!isFinite(biomass)){biomass = 0;}
        if(!isFinite(dosis_pakan)){dosis_pakan = 0;}
        $("#biomass").val(biomass);
        $("#dosis_pakan").val((dosis_pakan).toFixed(4));
        $("#total_pakan").val((dosis_pakan*7).toFixed(4));
        $("#pagi").val((dosis_pakan*0.3).toFixed(3));
        $("#pagi_gr").val($("#pagi").val() * 1000);
        $("#sore").val((dosis_pakan*0.3).toFixed(3));
        $("#sore_gr").val($("#sore").val() * 1000);
        $("#malam").val((dosis_pakan*0.4).toFixed(3));
        $("#malam_gr").val($("#malam").val() * 1000);

        $biomass_before = $("#biomass_before").val();
        $kenaikan_daging = (biomass - $biomass_before).toFixed(3);
        if(!isFinite($kenaikan_daging) || $kenaikan_daging <= 0){
            $kenaikan_daging = 0;
        }
        $fcr = ($("#total_pakan_before").val() /$kenaikan_daging).toFixed(3);
        if(!isFinite($fcr) || $fcr <= 0){
            $fcr = 0;
        }
        $adg = ($kenaikan_daging/7).toFixed(3);
        if(!isFinite($adg) || $adg <= 0){
            $adg = 0;
        }
        $("#kenaikan_daging").val($kenaikan_daging);
        $("#fcr").val($fcr);
        $("#adg").val($adg);
    }

    $("#tblok").change(function(){
        changeKolam($(this).val());
    });

    $("#tkolam").change(function(){
        getTotalIkan();
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
                getTotalIkan();
            }
        });
        return deferredData; // contains the passed data
    };

    function getTotalIkan(){
        $kolam_id = $("#tkolam").val();
        $biomass = $("#biomass").val();
        $total_pakan_before = $("#total_pakan_before").val();
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Mastersampling/getTotalIkan"; ?>",
            dataType: "json",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', kolam_id: $kolam_id},
            success: function(data) {
                for(var $i=0; $i<data.length; $i++){
                    $("#total_ikan").val(data[$i]["total_ikan"]);
                    $("#biomass_before").val(data[$i]["biomass"]);
                    $("#total_pakan_before").val(data[$i]["total_pakan"]);
                }
                calculate();
            }
        });
        return deferredData; // contains the passed data
    }
</script>

</body>
</html>
