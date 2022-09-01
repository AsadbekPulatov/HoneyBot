
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <title>Asal buyurtmalari</title>
</head>
<body>
<div class="card">
    <hr>
    <h1 align="center" class="text text-primary container "> Buyurtmalar ro'yhati</h1>
    <hr>
    <div class="container ">
        <div class="row d-flex justify-content-between w-100">
            <?php
            require_once "connect.php";
            $orders = [
                "1kg - 50 000 so'm",
                "1.5kg(1L) - 75 000 so'm",
                "4.5kg(3L) - 220 000 so'm",
                "7.5kg(5L) - 370 000 so'm",
            ];
            $sql="select * from users;";
            $result=$connect->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-sm-10 col-lg-3 p-4 col-md-10 card " style="border: 1px  solid blue; height: 400px; box-shadow: 0px 0px 10px 10px #c6c6c8">
                        <p>👤 Buyurtmachi ismi: <?php echo $row['name'] ?></p>
                        <p>📱 Telefon raqami: <?php echo $row['phone'] ?></p>
                        <p>🍯 Miqdor: <?php echo $orders[$row['product']] ?></p>
                        <p>⛳️ Manzil: <?php if($row['latitude']  != ""){
                                echo "<br>latitude:".$row['latitude']."<br> longitude:".$row['longitude'];
                            } else {
                                echo $row['address'];
                            }?></p>
                        <p>📅 Sana: <?php echo $row['created_at'] ?></p>
                        <p>📇 Status: <?php  if($row['status'] == 0){ echo " bekor qilingan ";}else{ echo " kutilmoqda ";} ?></p>
                    </div>
                    <?php
                }
            }
            ?>
        </div>


    </div>

</body>
</html>
<?php

mysqli_close($conn);
