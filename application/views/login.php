<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Login</title>
    <?php include 'header.php' ?>
</head>

<body>

<!-- **********************************************************************************************************************************************************
MAIN CONTENT
*********************************************************************************************************************************************************** -->

<div id="login-page">
    <div class="container">
        <?php
        $attributes = array('class' => 'form-login');
        echo form_open('login/check', $attributes);
        ?>
            <form class="form-login" action="index.html">
                <h2 class="form-login-heading">sign in now</h2>
                <div class="login-wrap">
                    <div class="form-group">
                        <input type="text" class="form-control" id="uname" name="uname" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="pass" placeholder="Enter password" name="pass">
                    </div>

                    <input type="submit" class="btn btn-theme btn-block" name="submit" id="submit" value="SIGN IN">
                    <div class="mt text-center">
                        <?=$err_msg?>
                    </div>
                </div>
            </form>
        <?php echo form_close(); ?>
    </div>
</div>

<?php include 'footer.php' ?>
<script type="text/javascript">
    $(document).ready(function(){
        $(".sldown").slideDown("slow");
    });

</script>
</body>
</html>
