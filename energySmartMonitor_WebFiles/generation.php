﻿<!DOCTYPE html>
<html>
<?php

session_start();

if(!isset($_SESSION["newsession"])){
  include 'login.php';
  exit;
}else{
  $_SESSION["page"]="genData";
}

   $redis = new Redis();
   $redis->connect('redis', 6379);
   //echo "Connection to server sucessfully";
   //echo "Server is running: " . $redis->ping();
   $allKeys = $redis->keys('*');

   $json = $redis->get("theme");
   $arrayValue = json_decode($json, true);

   if($arrayValue['baseType']=="light"){
     $baseTypeThemeClass="th-light";
   }else{
     $baseTypeThemeClass="";
   }

   $accentColorName=$arrayValue['accentColorName'];
   $accentColorCode='#'.$arrayValue['accentColorCode'];


   $json_generationHistory = $redis->lrange("generationHistoryList",0,-1);

   $arrV_generationHistory = json_encode($json_generationHistory, true);
   $newjson_generationHistory = str_replace('"{', '{', $arrV_generationHistory);
   $newjson_generationHistory2 = str_replace('}"', '}', $newjson_generationHistory);
   $newjson_generationHistory3 = preg_replace('/\\\"/',"\"", $newjson_generationHistory2);
   $arrayValue_generationHistory = json_decode($newjson_generationHistory3, true);

   $gettedModeChart = $redis->get("modeChartGen");

?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <meta name="theme-color" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-TileColor" content="<?php echo $accentColorCode ?>">
    <meta name="msapplication-navbutton-color" content="<?php echo $accentColorCode ?>">
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo $accentColorCode ?>">

    <title>Energy Smart Monitor | Energy Generation</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />

    <link href="css/fontello.css" rel="stylesheet">


        <!-- Bootstrap Select Css -->
        <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

        <!-- Bootstrap Tagsinput Css -->
        <link href="plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">


</head>



<body class="theme-<?php echo $accentColorName ?> <?php echo $baseTypeThemeClass;?>">


  <?php include 'base.php'; ?>


    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>GENERATION DATA</h2>
            </div>

            <div class="row clearfix">
                <!-- Bar Chart -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>" id="chartGen">
                        <div class="header">
                            <h2>LAST 24H</h2>
                        </div>
                        <div class="body" id="bar_chart_body">
                            <canvas id="bar_chart_generation" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <!-- #END# Line Chart -->
            </div>
            <div class="row clearfix">
                <!-- Cost history Info -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card <?php echo $baseTypeThemeClass;?>">
                        <div class="header">
                            <h2>HISTORY</h2>
                        </div>
                        <div class="body bodyCardScroll histCardData">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Generation</th>
                                            <th>Benefit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      foreach ($arrayValue_generationHistory as $genHist){
                                        if($genHist!=null){
                                          echo "<tr>";
                                            echo "<td>".$genHist['date']."</td>";
                                            echo "<td>".$genHist['kWh']." kWh</td>";
                                            echo "<td>".$genHist['benefit']." €</td>";
                                          echo "</tr>";
                                        }
                                      }
                                      ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# cost history Info -->

            </div>




        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="plugins/raphael/raphael.min.js"></script>
    <script src="plugins/morrisjs/morris.js"></script>

    <!-- ChartJs -->
    <script src="plugins/chartjs/Chart.bundle.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Jquery Knob Plugin Js -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>

    <script src="js/pages/charts/jquery-knob.js"></script>
    <!--<script src="js/pages/charts/chartjs.js"></script>-->

    <script src="js/scriptGeneration.js"></script>

    <!-- Demo Js -->
    <!--<script src="js/demo.js"></script>-->




</body>

</html>
