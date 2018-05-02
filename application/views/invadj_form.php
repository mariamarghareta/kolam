<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Inventory Adjustment</title>

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
            <div class="col-md-4 col-md-offset-4 margin-up-md">
                <div class="w3-container w3-green page-title w3-center w3-padding-16">
                    Inventory Adjustment
                </div>

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                if ($state == "update"){
                    echo form_open('Stockadj/update_data', $attributes);
                } else if ($state == "create"){
                    echo form_open('Stockadj/add_new_data', $attributes);
                } else if ($state == "delete"){
                    echo form_open('Stockadj/delete_data', $attributes);
                }
                ?>
                <input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>">
                <div class="w3-container w3-white w3-padding-32">
                    <div style="margin:10px 20px;">
                        <?php if ($state == "delete"){?>
                            <div style="margin-bottom: 20px; font-weight:bold;">Apakah Anda yakin menghapus data ini?</div>
                        <?php } ?>
                        <label style="font-weight: bold">Tipe Barang</label><label style="color: red; padding-left: 5px;"> *</label>
                        <select id="tipe_pembelian" name="tipe_pembelian" class="form-control" style="width:220px">
                            <option value="p" <?php if ($tipe_pembelian == "p"){echo "selected";} ?> >Pakan</option>
                            <option value="o" <?php if ($tipe_pembelian == "o"){echo "selected";} ?> >Obat</option>
                        </select>
                        <br>
                        <label style="font-weight: bold">Barang</label><label style="color: red; padding-left: 5px;"> *</label>
                        <br>
                        <div id="div_pakan">
                            <select id="cb_pakan" name="cb_pakan" <?php if ($state != "delete"){ ?>class="selectpicker"<?php } else { ?> class="form-control" style="width:220px;" <?php } ?> data-live-search="true">
                                <?php foreach($arr_pakan as $row){
                                    if($row['id'] == $selected_pakan){ ?>
                                        <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                    <?php } else { ?>
                                        <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php }} ?>
                            </select>
                        </div>
                        <div id="div_obat">
                            <select id="cb_obat" name="cb_obat" <?php if ($state != "delete"){ ?>class="selectpicker"<?php } else { ?> class="form-control" style="width:220px;" <?php } ?> data-live-search="true">
                                <?php foreach($arr_obat as $row){
                                    if($row['id'] == $selected_obat){ ?>
                                        <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                    <?php } else { ?>
                                        <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php }} ?>
                            </select>
                        </div>
                        <br>
                        <label style="font-weight: bold">Stok</label><label style="color: red; padding-left: 5px;"> *</label>
                        <div class="row">
                            <div class="col-xs-10 col-md-10">
                                <?php echo form_input(array('name'=>'stok', 'id'=>'stok', 'class'=>'w3-input'), $stok);?>
                                <?php echo form_error('stok'); ?>
                            </div>
                            <div class="col-xs-2 col-md-2" id="satuan" style="padding-top: 8px;">

                            </div>
                        </div>
                    </div>
                    <?php if ($state == "update") { ?>
                        <div class="text-center">
                            <button name="write" value="write" type="submit" class="w3-button w3-green w3-center margin-up-md">Ubah Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Stockadj">Batal</a></button>
                        </div>
                    <?php } else if ($state == "create"){ ?>
                        <div class="text-center">
                            <button name="add" type="submit" class="w3-button w3-green w3-center margin-up-md">Tambah Data</button>
                        </div>
                    <?php } else if ($state == "delete"){?>
                        <div class="text-center">
                            <button name="delete" value="delete" type="submit" class="w3-button w3-red w3-center margin-up-md">Hapus Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Stockadj">Batal</a></button>
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
        $("#menu_invadj").addClass('active');
        $("#sub_menu_master_data").css("display", "block");
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
        cek_tipe();
    });

    if( "<?php echo $state ?>" == "delete"){
        $("input[type=text]").prop('disabled', true);
    }

    $("#tipe_pembelian").change(function(){
        cek_tipe();
    })

    $("#cb_obat").change(function(){
        cek_tipe();
    })

    $("#cb_pakan").change(function(){
        cek_tipe();
    })

    function cek_tipe(){
        $tipe = $("#tipe_pembelian").val();
        $id = 0;
        $is_check_satuan = true;
        if ($tipe == "o"){
            $("#div_pakan").hide();
            $("#div_obat").show();
            $id = $("#cb_obat").val();
        } else if ($tipe == "p"){
            $("#div_pakan").show();
            $("#div_obat").hide();
            $id = $("#cb_pakan").val();
        }

        if($is_check_satuan) {
            var deferredData = new jQuery.Deferred();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . index_page() . "/Transaksipembelian/getSatuan"; ?>",
                dataType: "json",
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                    tipe: $tipe,
                    id: $id
                },
                success: function (data) {
                    $("#satuan").html(data[0]["satuan"]);
                }
            });
            return deferredData; // contains the passed data
        }
    }
</script>

</body>
</html>
