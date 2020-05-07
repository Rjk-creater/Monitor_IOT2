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
  <style type="text/css">
    #mdata{
       font-weight: bold;
       font-size: 18;
       color: blue;
    }
  </style>
   <script type="text/javascript">
      let v1=0
      let v2=0
      let v3=0
      var updateInterval = <?php echo $updateInterval ?>;
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);
      google.charts.setOnLoadCallback(drawChart3);
      
      function drawChart1() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['LDRx10',v1],
          
        ]);

        var options = {
          width: 400, height: 180,
          redFrom: 80, redTo: 100,
          yellowFrom:70, yellowTo:80,
          minorTicks: 1
        };

        var chart1 = new google.visualization.Gauge(document.getElementById('chart_div1'));

        chart1.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1,v1);
          chart1.draw(data, options);
        },updateInterval);
       
      }
     
      function drawChart2() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['ADCx10', v2],
          
        ]);

        var options = {
          width: 400, height: 180,
          redFrom:100 , redTo:255,
          yellowFrom:90, yellowTo:100,
          minorTicks: 5
        };

        var chart2 = new google.visualization.Gauge(document.getElementById('chart_div2'));

        chart2.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, v2);
          chart2.draw(data, options);
        },updateInterval);
       
      }
     
      function drawChart3() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Simulate', v3],
          
        ]);

        var options = {
          width: 400, height: 180,
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
          minorTicks: 3
        };

        var chart3 = new google.visualization.Gauge(document.getElementById('chart_div3'));

        chart3.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, v3);
          chart3.draw(data, options);
        },updateInterval);
       
      }

$(document).ready(function(){
  
    $('#serv').val(host)
    $("#prt").val(port)
    
    var client; //MQTT
    
     $('#dchs').click(function(){
       location.reload();
     })
     $('#con').on('click',function(){
      
        ConMQtt();
      
            
      })
   })


 </script>

 <script type="text/javascript">
   
    var path = '/rjkIOTmonitor'
    // Create a client instance
    
   // client = new Paho.MQTT.Client(location.hostname, Number(location.port), "clientId");
   
   client = new Paho.MQTT.Client(host,port,path,"CldMQT_" + parseInt(Math.random() * 100, 10));
   var options = {
            timeout:3,
            useSSL:true,
            userName:username,
            password:password,
            cleanSession:true,
            onSuccess:onConnect,
            //reconnect : true,         // Enable automatic reconnect
           // reconnectInterval: 10     // Reconnect attempt interval : 10 seconds
            };

// set callback handlers
client.onConnectionLost = onConnectionLost;
client.onMessageArrived = onMessageArrived;

// connect the client
//client.connect({onSuccess:onConnect});
// called when the client connects
function onConnect() {
   client.subscribe("adc/send")
   
 }

 // called when the client loses its connection
function onConnectionLost(responseObject) {
  if (responseObject.errorCode !== 0) {
    console.log("onConnectionLost:"+responseObject.errorMessage);
    document.writeln(responseObject.errorMessage);

  }
}

// called when a message arrives
function onMessageArrived(message) {

  //console.log("onMessageArrived:"+message.payloadString);
   
 
    if(message.destinationName.indexOf("adc/") == 0){
          
              var ldrdata = JSON.parse(message.payloadString);
                
                   v1 = (ldrdata.ldr)/10
                   console.log(v1)
                  
                   v2 = (ldrdata.adc1)/10
                   console.log(v2)
                    
                   v3 = ldrdata.sim 
                   console.log(v3)
            
               var msg = "LDR = "+ldrdata.ldr;
                msg += ", ADC "+ ldrdata.adc1;
                msg += ", Simulate "+ldrdata.sim + '\n';

            $('#mdata').prepend(msg)

               setTimeout(function(){
                  $('#mdata').empty()
                        },5000)        
          }
}
 

function Send(){

    message = new Paho.MQTT.Message("Server received ok" + "\n");
    message.destinationName = "Server/reply";
    client.send(message);
 }

function ConMQtt(){

   client.connect(options);
    
     alert(client.host+" port: "+client.port);

}

function Disconnect() {
      alert("client is disconnecting..");
      this.client.disconnect();
 }

 </script>

</head>
<body>
<div class="container-fluid">

<div class="row" style="margin-top: 20px">
 
 <div class="col-2">
  <div class="card bg-dark text-white">
    <div class="card-body">
      <img src="imgs/rjkiot3.jpg" class="rounded-circle" alt="RJK IOT" width="150" height="150">   
    </div>
  </div>
</div>
<div class="col-10">
  <div class="card bg-dark text-white">
    <div class="card-body">
     <form class="form-inline">
      <label for="serv" class="mb-2 mr-sm-2">Server:</label>
      <input type="text" class="form-control mb-2 mr-sm-2" id="serv">
      <label for="prt" class="mb-2 mr-sm-2">Port:</label>
      <input type="text" class="form-control mb-2 mr-sm-2" id="prt">
      <button type="button" class="btn btn-success mb-2" id="con">Connect</button>
      &nbsp;<button type="button" class="btn btn-warning mb-2" id="dchs">Disconnect</button>
      
    </form>
    <textarea style="margin-top:20px" cols="80" rows="3" id="mdata"></textarea>
  </div>  
 </div>
</div>
</div>

<div class="row" style="margin-top:10px;">
 <div class="col bg-success">
   <div id="chart_div1" style="width:100%; height: 200px;margin-left:70px;"></div>
 </div>
 <div class="col bg-info">
  <div id="chart_div2" style="width:100%; height: 200px;margin-left:70px;"></div>
 </div>
 <div class="col bg-primary">
  <div id="chart_div3" style="width:100%; height: 200px;margin-left:70px;"></div>
 </div>
</div>




</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
