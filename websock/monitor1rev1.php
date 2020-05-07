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
      var vl1,vl2,vl3
      var xValue = <?php echo $x ?>;
      var yValue1 = vl1;
      var yValue2 = vl2;

      var updateInterval = <?php echo $updateInterval ?>;
      var dataPoints1 = [{'x':<?php echo $x;?>,'y':vl1}];
      var dataPoints2 = [{'x':<?php echo $x;?>,'y':vl2}];
      var dataPointsC = [{'label':'Simulate','y':vl3}];

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
          width: 400, height: 160,
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
          width: 400, height: 160,
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
          width: 400, height: 160,
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

graphLine1 = function() {

var chart1 = new CanvasJS.Chart("chartLine1", {
  theme: "dark2",
  title: {
    text: "Realtime LDR"
  },
  axisX:{
    title: "Time Line"
  },
  axisY:{
    includeZero: false,
    suffix: " Units"
  },
  data: [{ 
      type: "line",
      lineColor:"lime",
      //name: "Value1",
      xValueType: "dateTime",
      yValueFormatString: "#,### units",
      xValueFormatString: "hh:mm:ss TT",
      showInLegend: true,
     // legendText: "{name} " + v1 + " units",
      dataPoints: dataPoints1
     }
    ]
});
chart1.render();
 
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 
function updateChart() {
  xValue += updateInterval;
  yValue1 = vl1
 
  // pushing the new values
  dataPoints1.push({
    x: xValue,
    y: yValue1
  });
  
      chart1.render();   
  }
 
}

garea1 = function () {
 
var chartAr1 = new CanvasJS.Chart("chartArea1", {
  animationEnabled: true,
  theme: "dark1",
  title:{
    text: "Realtime"
  },
  axisX: {
    valueFormatString: "DD MMM YYYY HH mm ss"
  },
  axisY: {
    title: "Realtime Analog(ADC)",
    maximum: 1024
  },
  data: [{
    type: "splineArea",
    color: "#6599FF",
     xValueType: "dateTime",
      yValueFormatString: "#,### units",
      xValueFormatString: "hh:mm:ss TT",
      showInLegend: true,
    dataPoints:dataPoints2
  }]
});
 
chartAr1.render();
 
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 

function updateChart() {
  xValue += updateInterval;
  yValue2 = vl2
 
  // pushing the new values
  dataPoints2.push({
    x: xValue,
    y: yValue2
  });
  
      chartAr1.render();   
  }

}

gcolumn = function () {
 
var chart = new CanvasJS.Chart("chartColumn", {
  title: {
    text: "Simulate signal data"
  },
  axisY: {
    minimum: 0,
    maximum: 100,
    suffix: "%"
  },
  data: [{
    type: "column",
    yValueFormatString: "#,##0.00\"%\"",
    indexLabel: "{y}",
    dataPoints:dataPointsC 
  }]
});
 
function updateChart() {
  dataPointsC[0].y = vl3
  chart.render();
};

updateChart();
 
setInterval(function () { updateChart() },updateInterval);
 
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
        graphLine1();
        garea1();
        gcolumn();
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
                   vl1 = ldrdata.ldr
                   v1 = (ldrdata.ldr)/10
                   console.log(v1)
                   vl2 = ldrdata.adc1
                   v2 = (ldrdata.adc1)/10
                   console.log(v2)
                   v3 = ldrdata.sim 
                   vl3 = v3
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
   <div id="chart_div1" style="width:100%; height: 180px;margin-left:70px;"></div>
 </div>
 <div class="col bg-info">
  <div id="chart_div2" style="width:100%; height: 180px;margin-left:70px;"></div>
 </div>
 <div class="col bg-primary">
  <div id="chart_div3" style="width:100%; height: 180px;margin-left:70px;"></div>
 </div>
</div>

<div class="row" style="margin-top:10px;">
<div class="col">
  <div id="chartLine1" style="height: 370px; width: 100%;"></div>
</div>
<div class="col">
  <div id="chartArea1" style="height: 370px; width: 100%;"></div>
</div>
<div class="col">
  <div id="chartColumn" style="height: 370px; width: 100%;"></div>
</div>
</div>


</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
