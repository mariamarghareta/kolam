<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Master Pakan</title>

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
                    Tebar Pakan
                </div>

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                if ($state == "update"){
                    echo form_open('Mastertebar/update_data', $attributes);
                } else if ($state == "create"){
                    echo form_open('Mastertebar/add_new_data', $attributes);
                } else if ($state == "delete"){
                    echo form_open('Mastertebar/delete_data', $attributes);
                }
                ?>
                <input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>">
                <div class="w3-container w3-white w3-padding-32">
                    <div style="margin:10px 20px;">
                        <?php if ($state == "delete"){?>
                            <div style="margin-bottom: 20px; font-weight:bold;">Apakah Anda yakin menghapus data ini?</div>
                        <?php } ?>
                        <div class="col-sm-6">
                            <label style="font-weight: bold">Blok</label>
                            <br>
                            <div id="div_blok" class="">
                                <select id="tblok" name="tblok" <?php if ($state != "delete"){ ?>class="selectpicker"<?php } else { ?> class="form-control" <?php } ?> data-live-search="true">
                                    <?php foreach($arr_blok as $row){
                                        if($row['id'] == $selected_blok){ ?>
                                            <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                        <?php } else { ?>
                                            <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                        <?php }} ?>
                                </select>
                            </div>
                            <br>
                            <label style="font-weight: bold">Kolam</label>
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
                            <label style="font-weight: bold">Sampling 1 ons (ekor)</label>
                            <?php echo form_input(array('name'=>'sampling', 'id'=>'sampling', 'class'=>'w3-input'), $sampling);?>
                            <?php echo form_error('sampling'); ?>
                            <br>
                            <label style="font-weight: bold">Biomass (kg)</label>
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
                            <label>Pagi (kg)</label>
                            <?php echo form_input(array('name'=>'pagi', 'id'=>'pagi', 'class'=>'w3-input', 'readonly' => 'readonly'), $pagi);?>
                            <?php echo form_error('pagi'); ?>
                            <br>
                            <label>Sore (kg)</label>
                            <?php echo form_input(array('name'=>'sore', 'id'=>'sore', 'class'=>'w3-input', 'readonly' => 'readonly'), $sore);?>
                            <?php echo form_error('sore'); ?>
                            <br>
                            <label>Malam (kg)</label>
                            <?php echo form_input(array('name'=>'malam', 'id'=>'malam', 'class'=>'w3-input', 'readonly' => 'readonly'), $malam);?>
                            <?php echo form_error('malam'); ?>
                        </div>
                    </div>
                    <?php if ($state == "update") { ?>
                        <div class="text-center">
                            <button name="write" type="submit" class="w3-button w3-green w3-center margin-up-md">Ubah Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Masterpakan">Batal</a></button>
                        </div>
                    <?php } else if ($state == "create"){ ?>
                        <div class="text-center">
                            <button name="add" type="submit" class="w3-button w3-green w3-center margin-up-md">Tambah Data</button>
                        </div>
                    <?php } else if ($state == "delete"){?>
                        <div class="text-center">
                            <button name="delete" type="submit" class="w3-button w3-red w3-center margin-up-md">Hapus Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Masterpakan">Batal</a></button>
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
        $("#menu_tebar").addClass('active');
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
    });

    if( "<?php echo $state ?>" == "delete"){
        $("input[type=text]").prop('disabled', true);
    }

    $("#sampling").keyup(function(){
        sampling = $("#sampling").val();
        size = sampling*10;
        $("#size").val(size);
        getData(size);
    });

    $("#biomass").keyup(function(){
        biomass = $("#biomass").val();
        size = $("#size").val();
        fr = $("#fr").val()/100;
        sr = $("#sr").val()/100;
        total_ikan = biomass*size;
        dosis_pakan = biomass * fr * sr;
        $("#total_ikan").val(total_ikan);
        $("#dosis_pakan").val((dosis_pakan).toFixed(4));
        $("#total_pakan").val((dosis_pakan*7).toFixed(4));
        $("#pagi").val((dosis_pakan*0.3).toFixed(3));
        $("#sore").val((dosis_pakan*0.3).toFixed(3));
        $("#malam").val((dosis_pakan*0.4).toFixed(3));
    });

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
            }
        });
        return deferredData; // contains the passed data
    };

    function changeKolam($blok_id){
        var deferredData = new jQuery.Deferred();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . index_page() . "/Masterkolam/getkolam"; ?>",
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
            }
        });
        return deferredData; // contains the passed data
    };
</script>

</body>
</html>
