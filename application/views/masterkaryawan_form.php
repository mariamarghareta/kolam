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
            <div class="col-md-8 col-md-offset-2 margin-up-md">
                <div class="w3-container w3-green page-title w3-center w3-padding-16">
                    Master Karyawan
                </div>

                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'form_blok');
                if ($state == "update"){
                    echo form_open('Masterkaryawan/update_data', $attributes);
                } else if ($state == "create"){
                    echo form_open('Masterkaryawan/add_new_data', $attributes);
                } else if ($state == "delete"){
                    echo form_open('Masterkaryawan/delete_data', $attributes);
                }
                ?>
                <input type="hidden" name="tid" id="tid" value="<?php echo $id; ?>">
                <div class="w3-container w3-white w3-padding-32">
                    <?php if ($state == "delete"){?>
                        <div style="margin-bottom: 20px; font-weight:bold;">Apakah Anda yakin menghapus data ini?</div>
                    <?php } ?>
                    <div class="row" style=";">
                        <div class="col-sm-6" style="padding: 0px 30px;">
                            <label>Username</label>
                            <?php echo form_input(array('name'=>'uname', 'id'=>'uname', 'class'=>'w3-input'), $uname);?>
                            <?php echo form_error('uname'); ?>
                            <br>
                            <label>Nama</label>
                            <?php echo form_input(array('name'=>'tname', 'id'=>'tname', 'class'=>'w3-input'), $name);?>
                            <?php echo form_error('tname'); ?>
                            <br>
                            <label>No. Telp</label>
                            <?php echo form_input(array('name'=>'telp', 'id'=>'telp', 'class'=>'w3-input'), $telp);?>
                            <?php echo form_error('telp'); ?>
                            <br>
                            <label>Alamat</label>
                            <?php echo form_input(array('name'=>'alamat', 'id'=>'alamat', 'class'=>'w3-input'), $alamat);?>
                            <?php echo form_error('alamat'); ?>
                        </div>
                        <div class="col-sm-6" style="padding: 0px 30px;">
                            <label>Password</label>
                            <div class="row">
                                <div class="col-sm-10">
                                    <?php echo form_input(array('name'=>'pass', 'id'=>'pass', 'class'=>'w3-input', 'type'=>'password'), $pass);?>
                                    <?php echo form_error('pass'); ?>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-success fa fa-eye" id="see"></button>
                                </div>
                            </div>
                            <br>
                            <label>Masukkan ulang password</label>
                            <div class="row">
                                <div class="col-sm-10">
                                    <?php echo form_input(array('name'=>'repass', 'id'=>'repass', 'class'=>'w3-input', 'type'=>'password'), $repass);?>
                                    <?php echo form_error('repass'); ?>
                                </div>
                            </div>
                            <br>
                            <label>Role</label>
                            <div id="div_blok" class="">
                                <select id="role" name="role" <?php if ($state != "delete"){ ?>class="selectpicker"<?php } else { ?> class="form-control" <?php } ?> data-live-search="true">
                                    <?php foreach($arr_role as $row){
                                        if($row['id'] == $role){ ?>
                                            <option value="<?=$row['id']?>" selected><?=$row['role']?></option>
                                        <?php } else { ?>
                                            <option value="<?=$row['id']?>"><?=$row['role']?></option>
                                        <?php }} ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if ($state == "update") { ?>
                        <div class="text-center">
                            <button name="write" value="write" type="submit" class="w3-button w3-green w3-center margin-up-md">Ubah Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Masterkaryawan">Batal</a></button>
                        </div>
                    <?php } else if ($state == "create"){ ?>
                        <div class="text-center">
                            <button name="add" type="submit" class="w3-button w3-green w3-center margin-up-md">Tambah Data</button>
                        </div>
                    <?php } else if ($state == "delete"){?>
                        <div class="text-center">
                            <button name="delete" value="delete" type="submit" class="w3-button w3-red w3-center margin-up-md">Hapus Data</button>
                            <button name="cancel" class="w3-button w3-grey w3-center margin-up-md"><a href="<?php echo base_url() . index_page(); ?>/Masterkaryawan">Batal</a></button>
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

        $("#menu_karyawan").addClass('active');
        $("#sub_menu_master_data").css("display", "block");
        $("#err_msg").addClass('text-center');
        $(".sldown").slideDown("slow");
        $(".slup").slideUp("slow");
        $(".slfadein").fadeIn("slow");
        $(".slhide").hide();
        $(".slshow").show();
    });

    $("#see").click(function(){
        if($("#pass").attr("type") == "password"){
            $("#pass").attr("type", "text");
        } else{
            $("#pass").attr("type", "password");
        }

    });

    if( "<?php echo $state ?>" == "delete"){
        $("input[type=text]").prop('disabled', true);
        $("input[type=password]").prop('disabled', true);
        $("#see").hide();
        $("#role").prop('disabled', true);
        $("#role").css('width', '220px');
    }
</script>

</body>
</html>
