<!-- <?php
    if(count($errorArray)>0){
        foreach ($errorArray as $txtError) {
    ?>
    <p style="color:red;padding:10px;font-weight: bold;font-family: system-ui;" ><?php echo $txtError;?></p>
    <?php }
    }
    else {
        ?>
        <p style="color:green;padding:10px;font-weight: bold;font-family: system-ui;" ><?php echo $messageArray[1];?></p>
        <?php
    } ?> -->

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <style>
            @font-face {
                font-family: IRANSans;
                font-style: normal;
                font-weight: 200;
                src: url('../assets/font/eot/IRANSansWeb_UltraLight.eot');
                src: url('../assets/font/eot/IRANSansWeb_UltraLight.eot?#iefix') format('embedded-opentype'),  /* IE6-8 */
                    url('../assets/font/woff2/IRANSansWeb_UltraLight.woff2') format('woff2'),  /* FF39+,Chrome36+, Opera24+*/
                    url('../assets/font/woff/IRANSansWeb_UltraLight.woff') format('woff'),  /* FF3.6+, IE9, Chrome6+, Saf5.1+*/
                    url('../assets/font/ttf/IRANSansWeb_UltraLight.ttf') format('truetype');
            }
            body {
                font-family: IRANSans;
                direction: rtl;
            }

            .panel-body{
                overflow: auto;
            }

            .filter_day {
                background: rgb(255,166,7,0.1);/* hsl(33,100,53,0.15); */
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;
                direction: rtl;
                text-align: center;
            }

            th, td {
                text-align: center;
                padding: 8px;
            }

            tr:nth-child(even){
                background-color: #FFA500
            }

            tr:nth-child(odd){
                background-color: #add8e6
            }

            tr:first-child{
                background-color: cyan
            }

        </style>
    </head>
    <body>
        <div class="container">
            <h2>
                @yield("title")
            </h2>
            <p></p>
            <div class="panel-group" id="accordion">
                @yield("content")
            </div>
        </div>
    </body>
</html>
