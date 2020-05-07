<?php
 $updateInterval = 2000; //in millisecond
    $initialNumberOfDataPoints = 100;
      $x = time() * 1000 - $updateInterval * $initialNumberOfDataPoints;
         for($i = 0; $i < $initialNumberOfDataPoints; $i++){
  
             $x += $updateInterval;
           }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>RJK IOT Monitoring</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <script src="mqttws31.js" type="text/javascript"></script>
   
  <script src="config_m23.js" type="text/javascript"></script>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <script type="text/javascript">
     $(document).ready(function(){
         $('#serv').val(host);

     });

  </script>


  </head>
<body>
  
  <div class="container">
   <div class="row" style="margin-top: 5px;">
      <div class="col-2">
      <img src="imgs/rjkiot3.jpg" class="rounded-circle" alt="RJK IOT" width="150" height="150">
      </div>
    <div class="col-6">
       Test
    </div>
    <div class="col-4">1</div>
   </div>

   <div class="row" style="margin-top: 10px;">
    <div class="col-12">
       <div class="card bg-dark text-white">
          <div class="card-body">
            <div id="accordion">
             <div class="card">
              <div class="card-header">
                <a class="card-link" data-toggle="collapse" href="#collapseOne">
                  MQTT Cloud Connection (เชื่อมต่อ Cloud MQTT)
               </a>
             </div>
             <div id="collapseOne" class="collapse show" data-parent="#accordion">
           <div class="card-body">
               <form class="form-inline">
               
                <input type="text" class="form-control mb-2 mr-sm-2" id="serv">
                
                <input type="text" class="form-control mb-2 mr-sm-2" id="prt">
                <button type="button" class="btn btn-primary mb-2" id="con">Connect</button>
                &nbsp;<button type="button" class="btn btn-danger mb-2" id="dchs">Disconnect</button>
                </form>
          </div>
     </div>
    </div> 
    </div>
         
    </div>
    </div>
    </div>
   </div>

</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>