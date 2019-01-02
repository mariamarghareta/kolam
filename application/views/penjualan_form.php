<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Penjualan</title>

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
            <div class="col-md-8 col-md-offset-2 margin-up-md">
                <div class="w3-container w3-green page-title w3-center w3-padding-16">
                    Penjualan
                </div>

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                if ($state == "update"){
                    echo form_open('Transaksipenjualan/update_data', $attributes);
                } else if ($state == "create"){
                    echo form_open('Transaksipenjualan/add_new_data', $attributes);
                } else if ($state == "delete"){
                    echo form_open('Transaksipenjualan/delete_data', $attributes);
                }
                ?>
                <input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>">
                <input type="hidden" name="tebar_id" id="tebar_id" value="<?php echo $tebar_id; ?>">
                <input type="hidden" name="pemberian_pakan_id" id="pemberian_pakan_id" value="<?php echo $pemberian_pakan_id; ?>">
                <input type="hidden" name="selected_kolam_before" id="selected_kolam_before" value="<?php echo $selected_kolam_before; ?>">
                <input type="hidden" name="his_id" id="his_id" value="<?php echo $his_id; ?>">
                <div class="w3-container w3-white w3-padding-32">
                    <div style="margin:10px 20px;">
                        <?php if ($state == "delete"){?>
                            <div style="margin-bottom: 20px; font-weight:bold;">Apakah Anda yakin menghapus data ini?</div>
                        <?php } ?>
                        <br>
                        <label style="font-weight: bold">Tanggal</label><label style="color: red; padding-left: 5px;"> *</label>
                        <div class="date"  data-link-field="dtp_input2">
                            <input class="form-control datepicker" style="width:220px;" type="text" name="buy_date" placeholder="yyyy-mm-dd hh:mi" autocomplete="off" value="<?=$buy_date?>" >
                            <?php echo form_error('buy_date'); ?>
                        </div>
                        <br>
                        <label style="font-weight: bold">Tipe Penjualan</label><label style="color: red; padding-left: 5px;"> *</label>
                        <select id="tipe_penjualan" name="tipe_penjualan" class="form-control" style="width:220px">
                            <option value="k" <?php if ($tipe_penjualan == "k"){echo "selected";} ?> >Kolam</option>
                            <option value="s" <?php if ($tipe_penjualan == "s"){echo "selected";} ?> >Sayur</option>
                            <option value="l" <?php if ($tipe_penjualan == "l"){echo "selected";} ?> >Lain-lain</option>
                        </select>
                        <br>
                        <div id="div_detail_kolam">
                            <label style="font-weight: bold">Customer</label><label style="color: red; padding-left: 5px;"> *</label>
                            <div id="div_mitra">
                                <select id="cb_mitra" name="cb_mitra" <?php if ($state != "delete" and $state != "show" and $state != "update"){ ?>class="selectpicker"<?php } else { ?> class="form-control" style="width:220px;" <?php } ?> data-live-search="true">
                                    <?php foreach($arr_mitra as $row){
                                        if($row['id'] == $selected_mitra){ ?>
                                            <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                        <?php } else { ?>
                                            <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                        <?php }} ?>
                                </select>
                            </div>
                            <br>
                            <label style="font-weight: bold">Blok</label><label style="color: red; padding-left: 5px;"> *</label>
                            <br>
                            <div id="div_blok" class="">
                                <select id="tblok" name="tblok" <?php if ($state != "delete" and $state != "show" and $state != "update"){ ?>class="selectpicker"<?php } else { ?> class="form-control" style="width:220px;" <?php } ?> data-live-search="true">
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
                            <label>Tgl. Tebar</label>
                            <?php echo form_input(array('name'=>'tgl_tebar', 'id'=>'tgl_tebar', 'class'=>'w3-input', 'readonly'=>'true'));?>
                            <br>
                        </div>
                        <label style="font-weight: bold">Berat yang dijual (kg)</label><label style="color: red; padding-left: 5px;"> *</label>
                        <?php echo form_input(array('name'=>'jumlah', 'id'=>'jumlah', 'class'=>'w3-input'), $jumlah);?>
                        <?php echo form_error('jumlah'); ?>
                        <br>
                        <label style="font-weight: bold">Harga per kg</label><label style="color: red; padding-left: 5px;"> *</label>
                        <?php echo form_input(array('name'=>'harga', 'id'=>'harga', 'class'=>'w3-input'), $harga);?>
                        <?php echo form_error('harga'); ?>
                        <br>
                        <label>Total</label>
                        <?php echo form_input(array('name'=>'total', 'id'=>'total', 'class'=>'w3-input', 'readonly' => 'true'), $total);?>
                        <?php echo form_error('total'); ?>
                        <br>
                        <label style="font-weight: bold">Keterangan</label>
                        <?php echo form_input(array('name'=>'keterangan', 'id'=>'keterangan', 'class'=>'w3-input'), $keterangan);?>
                        <?php echo form_error('keterangan'); ?>
                        <br>
                        <input class="w3-check" id="cb_tutup" type="checkbox" name="cb_tutup" value="tutup" <?php if($cb_tutup == 1){echo "checked=checked";}?> >
                        <label id="lb_cb_tutup">Tutup Kolam</label>

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
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Transaksipenjualan">Batal</a></button>
                        </div>
                    <?php } else if ($state == "create"){ ?>
                        <div class="text-center">
                            <button name="add" type="submit" class="w3-button w3-green w3-center margin-up-md">Tambah Data</button>
                        </div>
                    <?php } else if ($state == "delete"){?>
                        <div class="text-center">
                            <button name="delete" value="delete" type="submit" class="w3-button w3-red w3-center margin-up-md">Hapus Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Transaksipenjualan">Batal</a></button>
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
        $("#menu_penjualan").addClass('active');
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
        getKolamDetail();

        if ("<?php echo $tipe_penjualan ?>" == "s" || "<?php echo $tipe_penjualan ?>" == "l"){
            $("#div_detail_kolam").hide();
            $("#lb_cb_tutup").hide();
            $("#cb_tutup").hide();
        }
    });

    if( "<?php echo $state ?>" == "delete" || "<?php echo $state ?>" == "show"){
        $("input[type=text]").prop('disabled', true);
        $("#tipe_penjualan").attr('readonly', 'readonly');
        $("#tblok").prop('disabled', true);
        $("#tkolam").prop('disabled', true);
        $("#cb_mitra").prop('disabled', true);
    }

    if( "<?php echo $state ?>" == "update"){
        $("#tipe_penjualan").attr('readonly', 'readonly');
        $("#tblok").attr('readonly', 'readonly');
        $("#tkolam").attr('readonly', 'readonly');
        $("#cb_mitra").attr('readonly', 'readonly');
    }

    $("#tipe_penjualan").change(function(){
        if ($("#tipe_penjualan").val() == "k"){
            $("#div_detail_kolam").show();
            $("#lb_cb_tutup").show();
            $("#cb_tutup").show();
        } else if ($("#tipe_penjualan").val() == "s" || $("#tipe_penjualan").val() == "l"){
            $("#div_detail_kolam").hide();
            $("#lb_cb_tutup").hide();
            $("#cb_tutup").hide();
        }
    });

    $("#tblok").change(function(){
        changeKolam($(this).val());
    });

    $("#tkolam").change(function(){
        getKolamDetail();
    });

    $("#jumlah").keyup(function(){
        compute();
    });

    $("#harga").keyup(function(){
        compute();
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
                getKolamDetail();
            }
        });
        return deferredData; // contains the passed data
    };

    function getKolamDetail(){
        $kolam_id = $("#tkolam").val();
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Masterkolam/get_kolam_detail"; ?>",
            dataType: "json",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', kolam_id: $kolam_id},
            success: function(data) {
                $("#tgl_tebar").val(data[0]["tgl_tebar"]);
                $("#tebar_id").val(data[0]["tebar_id"]);
                $("#pemberian_pakan_id").val(data[0]["pemberian_pakan_id"]);
            }
        });
        return deferredData; // contains the passed data

    };

    function compute(){
        jumlah = $("#jumlah").val();
        harga = $("#harga").val();
        total = jumlah*harga;
        if(isNaN(total)){
            total = 0;
        }
        $("#total").val(total);
    }

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
</script>

</body>
</html>
