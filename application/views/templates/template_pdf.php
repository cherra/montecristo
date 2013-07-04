<!--
Template para PDFs
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="<?php echo asset_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="<?php echo asset_url(); ?>bootstrap/js/bootstrap.min.js"></script>
        <style>
            body{
                font-size: 11px; 
                line-height: 14px;
            }
            
            @page{
                margin-left: 12mm;
                margin-right: 12mm;
                margin-top: 12mm;
                margin-bottom: 12mm;
            }
            
        </style>
    </head>
    <body>
        {contenido_vista}
    </body>
</html>
