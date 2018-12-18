<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Pembelian</title>

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
                    Pembelian
                </div>

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                if ($state == "update"){
                    echo form_open('Transaksipembelian/update_data', $attributes);
                } else if ($state == "create"){
                    echo form_open('Transaksipembelian/add_new_data', $attributes);
                } else if ($state == "delete"){
                    echo form_open('Transaksipembelian/delete_data', $attributes);
                }
                ?>
                <input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>">
                <input type="hidden" name="tipe_pembelian_before" id="tipe_pembelian_before" value="<?php echo $tipe_pembelian_before; ?>">
                <input type="hidden" name="item_id_before" id="item_id_before" value="<?php echo $item_id_before; ?>">
                <div class="w3-container w3-white w3-padding-32">
                    <div style="margin:10px 20px;">
                        <?php if ($state == "delete"){?>
                            <div style="margin-bottom: 20px; font-weight:bold;">Apakah Anda yakin menghapus data ini?</div>
                        <?php } ?>
                        <br>
                        <label style="font-weight: bold">Dari Tanggal</label>
                        <div class="date"  data-link-field="dtp_input2">
                            <input class="form-control datepicker" style="width:220px;" type="text" name="buy_date" placeholder="yyyy-mm-dd hh:mi" autocomplete="off" value="<?=$buy_date?>" >
                            <?php echo form_error('buy_date'); ?>
                        </div>
                        <br>
                        <label style="font-weight: bold">Tipe Pembelian</label><label style="color: red; padding-left: 5px;"> *</label>
                        <select id="tipe_pembelian" name="tipe_pembelian" class="form-control" style="width:220px">
                            <option value="p" <?php if ($tipe_pembelian == "p"){echo "selected";} ?> >Pakan</option>
                            <option value="o" <?php if ($tipe_pembelian == "o"){echo "selected";} ?> >Obat</option>
                            <option value="l" <?php if ($tipe_pembelian == "l"){echo "selected";} ?> >Lain-lain</option>
                        </select>
                        <br>
                        <label style="font-weight: bold">Barang</label><label style="color: red; padding-left: 5px;"> *</label>
                        <br>
                        <div id="div_pakan">
                            <select id="cb_pakan" name="cb_pakan" class="form-control" style="width:220px;" >
                                <?php foreach($arr_pakan as $row){
                                    if($row['id'] == $selected_pakan){ ?>
                                        <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                    <?php } else { ?>
                                        <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php }} ?>
                            </select>
                        </div>
                        <div id="div_obat">
                            <select id="cb_obat" name="cb_obat" class="form-control" style="width:220px;">
                                <?php foreach($arr_obat as $row){
                                    if($row['id'] == $selected_obat){ ?>
                                        <option value="<?=$row['id']?>" selected><?=$row['name']?></option>
                                    <?php } else { ?>
                                        <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php }} ?>
                            </select>
                        </div>
                        <?php echo form_input(array('name'=>'nama_lain', 'id'=>'nama_lain', 'class'=>'w3-input'), $nama_lain);?>
                        <?php echo form_error('nama_lain'); ?>
                        <br>
                        <label style="font-weight: bold">Jumlah Item</label><label style="color: red; padding-left: 5px;"> *</label>
                        <?php echo form_input(array('name'=>'jumlah_item', 'id'=>'jumlah_item', 'class'=>'w3-input'), $jumlah_item);?>
                        <?php echo form_error('jumlah_item'); ?>
                        <br>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6">
                                <label style="font-weight: bold">Harga per Item</label><label style="color: red; padding-left: 5px;"> *</label>
                                <?php echo form_input(array('name'=>'harga_per_item', 'id'=>'harga_per_item', 'class'=>'w3-input'), $harga_per_item);?>
                                <?php echo form_error('harga_per_item'); ?>
                            </div>
                            <div class="col-xs-6 col-sm-6">
                                <label>Total Harga</label>
                                <?php echo form_input(array('name'=>'total_harga', 'id'=>'total_harga', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_harga);?>
                            </div>
                        </div>
                        <br>
                        <div class="row" id="div_isi">
                            <div class="col-xs-6 col-sm-6">
                                <div><label style="font-weight: bold">Isi</label><label style="color: red; padding-left: 5px;"> *</label></div>
                                <div class="col-xs-10 col-md-10 row">
                                    <?php echo form_input(array('name'=>'isi', 'id'=>'isi', 'class'=>'w3-input'), $isi);?>
                                    <?php echo form_error('isi'); ?>
                                </div>
                                <div class="col-xs-2 col-md-2" id="satuan" style="padding-top: 8px; text-align: center;">

                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6">
                                <div><label >Total Isi</label></div>
                                <div class="col-xs-10 col-md-10 row">
                                    <?php echo form_input(array('name'=>'total_isi', 'id'=>'total_isi', 'class'=>'w3-input', 'readonly' => 'readonly'), $total_isi);?>
                                </div>
                                <div class="col-xs-2 col-md-2" id="total_satuan" style="padding-top: 8px; text-align: center;">

                                </div>
                            </div>
                        </div>
                        <br>
                        <label style="font-weight: bold">Keterangan</label>
                        <?php echo form_input(array('name'=>'keterangan', 'id'=>'keterangan', 'class'=>'w3-input'), $keterangan);?>
                        <?php echo form_error('keterangan'); ?>

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
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Transaksipembelian">Batal</a></button>
                        </div>
                    <?php } else if ($state == "create"){ ?>
                        <div class="text-center">
                            <button name="add" type="submit" class="w3-button w3-green w3-center margin-up-md">Tambah Data</button>
                        </div>
                    <?php } else if ($state == "delete"){?>
                        <div class="text-center">
                            <button name="delete" value="delete" type="submit" class="w3-button w3-red w3-center margin-up-md">Hapus Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Transaksipembelian">Batal</a></button>
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
        $("#menu_pembelian").addClass('active');
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
        cek_tipe();

        if( "<?php echo $state ?>" == "update"){
            $("#tipe_pembelian").attr('readonly', 'readonly');
            $("#cb_pakan").attr('readonly', 'readonly');
            $("#cb_obat").attr('readonly', 'readonly');
        }
    });

    if( "<?php echo $state ?>" == "delete" || "<?php echo $state ?>" == "show"){
        $("input[type=text]").prop('disabled', true);
        $("#tipe_pembelian").prop('disabled', true);
        $("#cb_pakan").prop('disabled', true);
        $("#cb_obat").prop('disabled', true);
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

    $("#harga_per_item").keyup(function(){
        calculate();
    })

    $("#jumlah_item").keyup(function(){
        calculate();
    })

    $("#isi").keyup(function(){
        calculate();
    })

    function cek_tipe(){
        $tipe = $("#tipe_pembelian").val();
        $id = 0;
        $is_check_satuan = true;
        if ($tipe == "o"){
            $("#div_pakan").hide();
            $("#div_obat").show();
            $("#nama_lain").hide();
            $("#div_isi").show();
            $id = $("#cb_obat").val();
        } else if ($tipe == "p"){
            $("#div_pakan").show();
            $("#div_obat").hide();
            $("#nama_lain").hide();
            $("#div_isi").show();
            $id = $("#cb_pakan").val();
        } else if ($tipe == "l"){
            $("#div_pakan").hide();
            $("#div_obat").hide();
            $("#nama_lain").show();
            $("#div_isi").hide();
            $is_check_satuan = false;
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
                    $("#satuan").html(data[0]["satuan"] + "/item");
                    $("#total_satuan").html(data[0]["satuan"]);
                }
            });
            return deferredData; // contains the passed data
        }
    }

    function calculate(){
        $item = $("#jumlah_item").val();
        $harga_per_item = $("#harga_per_item").val();
        $isi = $("#isi").val();
        $total_harga = $item * $harga_per_item;
        $total_isi = $item * $isi
        if(!isFinite($total_harga)){$total_harga = 0;}
        if(!isFinite($total_isi)){$total_isi = 0;}
        $("#total_harga").val($total_harga);
        $("#total_isi").val($total_isi);
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
