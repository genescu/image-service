<?php
include __DIR__ . '/app.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        body {
            background-color: lightblue;
        }

        td {
            text-align: center;
        }
    </style>

</head>
<body>

<table>
    <tr>
        <td>Resize</td>
        <td>Crop</td>
    </tr>
    <tr>
        <td>
            <img alt="my beautiful image"
                 src="<?php echo APP_SERVER_ASSETS_URL; ?>/image.jpg/?width=250&height=250&mode=resize&title=my name is john RESIZE">
        </td>
        <td>
            <img alt="my beautiful image"
                 src="<?php echo APP_SERVER_ASSETS_URL; ?>/image.jpg/?width=250&height=250&mode=crop&title=my name is john CROP">

        </td>

    </tr>
    <tr>
        <td>
            <img alt="my beautiful image"
                 src="<?php echo APP_SERVER_ASSETS_URL; ?>/Johnrogershousemay2020.webp/?width=250&height=250&mode=resize&title=my name is john house webp RESIZE">
        </td>
        <td>
            <img alt="my beautiful image"
                 src="<?php echo APP_SERVER_ASSETS_URL; ?>/Johnrogershousemay2020.webp/?width=250&height=250&mode=crop&title=my name is john house webp CROP">
        </td>
    </tr>
</table>
</body>
</html>
