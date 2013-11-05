<?php session_start();
//session_destroy(); 
//init variables  
$cf = array();  
$sr = false;  

if(isset($_SESSION['cf_returndata'])){  
    $cf = $_SESSION['cf_returndata'];  
    $sr = true;  
}  
?>
 
<!DOCTYPE html>
<!--[if IE 8]>          <thml class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!-->  <html class="no-js" lang="en" > <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Contact form : Foundation 4 | Abide | AJAX</title>

    <link rel="stylesheet" href="css/foundation.css">

    <script src="js/vendor/custom.modernizr.js"></script>

</head>
<body>
<div class="row">

    <div class="large-5 large-centered columns">

        <form id="myform" data-abide="ajax" action="process.php" method="post">

            <fieldset>
                <legend>Your Form</legend>

                <?php //server side validation messages

                //errors
                if(isset($cf['errors']) && count($cf['errors']) > 0) :
                    echo '<div class="alert-box alert">';

                    foreach($cf['errors'] as $error) :
                        echo "<li>" . $error ."</li>";
                    endforeach;
                    echo '</div>';
                endif;

                //success
                if($cf['form_ok'] == true) :
                    echo '<div class="alert-box success">Thanks for your enquiry</div>';
                endif;
                ?>

                <!-- For spam -->
                <input type="hidden" name="firstname">

                <div class="name-field">
                    <label>Your name <small>required</small></label>
                    <input type="text" required pattern="[a-zA-Z]+" name="name" value="<?php echo ($sr && !$cf['form_ok']) ? $cf['posted_form_data']['name'] : '' ?>">
                    <small class="error">Name is required and must be a string.</small>
                </div>

                <div class="email-field">
                    <label>Email <small>required</small></label>
                    <input type="email" required name="email"  value="<?php echo ($sr && !$cf['form_ok']) ? $cf['posted_form_data']['email'] : '' ?>">
                    <small class="error">An email address is required.</small>
                </div>

                <button type="submit">Submit</button>

            </fieldset>
        </form>
        <?php unset($_SESSION['cf_returndata']); ?>  
    </div>

</div>

<script>
document.write('<script src=' +
    ('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
    '.js><\/script>')
</script>

<script src="js/foundation.min.js"></script>
<script src="js/foundation/foundation.abide.js"></script>
<script>

    $('#myform')
        .on('invalid', function () {
            var invalid_fields = $(this).find('[data-invalid]');
            //console.log(invalid_fields);
        })
        .on('valid', function () {
            //console.log('valid!');   
                $.ajax({
                    url: "process.php",
                    type: "POST",
                    data: $('#myform').serialize(),
                    success: function() {
                        //console.log('success!');
                        $('#myform').html("<div id='success' data-alert class='alert-box success'>Thanks for your enquiry son.<a href='#' class='close'>&times;</a></div>").css({
                            opacity:0
                        }).animate({
                            opacity: 1
                        }, 500, 'ease-in');
                     }
                });
                //return false; // required to block normal submit since you used ajax
                /*not required with abide.
        }); 

</script>
<script>
    $(document).foundation();
</script>
</body>
</html>
