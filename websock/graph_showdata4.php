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
   
  <script src="config_m24.js" type="text/javascript"></script>
  <style type="text/css">
    #mdata{
    color:yellow;
    background-color: blue;
    margin-left: 30px;
    }

  </style>
<script>
var v1,v2,v22,v3,v4,v5,v0;
var updateInterval = <?php echo $updateInterval ?>;
var dataPoints1 = [{'x':<?php echo $x;?>,'y':v1}];
var dataPoints2 = [{'x':<?php echo $x;?>,'y':v2}];
var dataPoints3 = [{'x':<?php echo $x;?>,'y':v22}];
var dataPoints4 = [{'label1':'Value3','y':v3},{'label2':'Value4','y':v4},{'label3':'Value5','y':v5}];

var xValue = <?php echo $x ?>;
var yValue1 = v1;
var yValue2 = v2;
var yValue3 = v22;


graphLine1 = function() {

var chart1 = new CanvasJS.Chart("chartContainer1", {
  theme: "light2",
  title: {
    text: "Realtime Analog Value1"
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
      lineColor:"red",
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
  yValue1 = v1
 
  // pushing the new values
  dataPoints1.push({
    x: xValue,
    y: yValue1
  });
  
      chart1.render();   
  }
 
}


graphLine2 = function() {


var chart2 = new CanvasJS.Chart("chartContainer2", {
  theme: "dark1",
  title: {
    text: "Realtime Analog Value2"
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
      dataPoints: dataPoints2
     }
    ]
});
chart2.render();
 
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 

function updateChart() {
  xValue += updateInterval;
  yValue2 = v2
 
  // pushing the new values
  dataPoints2.push({
    x: xValue,
    y: yValue2
  });
  
      chart2.render();   
  }
 
}

graphLine3 = function() {


var chart3 = new CanvasJS.Chart("chartContainer3", {
  theme: "dark2",
  title: {
    text: "Realtime Analog Value2_2"
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
       lineColor:"orange",
      //name: "Value1",
      xValueType: "dateTime",
      yValueFormatString: "#,### units",
      xValueFormatString: "hh:mm:ss TT",
      showInLegend: true,
     // legendText: "{name} " + v1 + " units",
      dataPoints: dataPoints3
     }
    ]
});
chart3.render();
 
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 

function updateChart() {
  xValue += updateInterval;
  yValue3 = v22
 
  // pushing the new values
  dataPoints3.push({
    x: xValue,
    y: yValue3
  });
  
      chart3.render();   
  }
 
}

column = function () {
 
var chart4 = new CanvasJS.Chart("chartColumn", {
  title: {
    text: "Realtime Data Column"
  },
  axisY: {
    minimum: 0,
    maximum: 1500,
    suffix: "units"
  },
  data: [{
    type: "column",
    yValueFormatString: "#,##0.00\"units\"",
    indexLabel: "{y}",
    dataPoints:dataPoints4
  }]
});
 
function updateChart() {
  
 dataPoints4[0].y = v3
 dataPoints4[1].y = v4
 dataPoints4[2].y = v5

  chart4.render();
};
updateChart();
 
setInterval(function () { updateChart() },updateInterval);
 
}


$(document).ready(function(){
  
    $('#serv').val(host)
    $("#port").val(port)
    
    var client; //MQTT
    
     $('#dchs').click(function(){
       location.reload();
     })
     $('#con').on('click',function(){
      
        ConMQtt();
            graphLine1();  
            graphLine2();  
            graphLine3();
            column();
     })
  
  });



</script>


<script type="text/javascript">
  var path = '/rjkIOTmonitor'
    // Create a client instance
    
   // client = new Paho.MQTT.Client(location.hostname, Number(location.port), "clientId");
   
   client = new Paho.MQTT.Client(host,port,path,"CloudMQT_" + parseInt(Math.random() * 100, 10));
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
   client.subscribe("data/simmu")
   
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
   
 
   $('#mdata').prepend(message.payloadString+'\n')

       setTimeout(function(){
         $('#mdata').empty()
       },3000)
      
      if(message.destinationName.indexOf("data/") == 0){
          
              var simdata = JSON.parse(message.payloadString);
                v0 = simdata.hex;
                console.log(v0)
                document.getElementById("hex").value = v0
                v1 = simdata.value1
                 document.getElementById("val1").value = v1
                v2 = simdata.value2
                 document.getElementById("val2").value = v2
                v22 = simdata.value2_2  
                 document.getElementById("val22").value = v22
                v3 = simdata.value3
                 document.getElementById("val3").value = v3
                v4 = simdata.value4
                 document.getElementById("val4").value = v4
                v5 = simdata.value5
                 document.getElementById("val5").value = v5
                dt = simdata.dt;
                 document.getElementById("dt").value = dt
                console.log(v1)
                console.log(v2)
                console.log(v22)
                console.log(dt)

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
  <h4>IOT Monitoring By RJKIOT</h4>
  <div class="row" style="margin-top: 30px">
   <form class="form-inline">
    <label for="serv">Server:&nbsp;</label>
    <input type="text" class="form-control" id="serv" readonly>
    <label for="port">&nbsp;Port:&nbsp;</label>
    <input type="text" class="form-control" id="port" readonly>&nbsp;
    <button type="button" class="btn btn-primary" id="con">Connect</button>
     <button type="button" class="btn btn-warning" id="dchs">Disconnect</button>
  </form>
 </div>
 <div class="row">
    <label for="mdata" style="margin-left:10px;margin-top: 5px;">Data<span class="text-danger">ON LINE</span>:</label>
    <textarea class="form-control" rows="2" cols="120" id="mdata" style="margin-left: 0px"></textarea>
 </div>
 <div class="row">
 
   
    <form class="form-inline">
    <label for="port">&nbsp;Hex:&nbsp;</label>
    <input type="text" class="form-control" id="hex" readonly style="background:black;color:lime" size="5">
    <label for="port">&nbsp;Value1:&nbsp;</label>
    <input type="text" class="form-control" id="val1" readonly style="background:black;color:lime" size="8">
    <label for="port">&nbsp;Value2:&nbsp;</label>
    <input type="text" class="form-control" id="val2" readonly style="background:black;color:lime" size="8">
    <label for="port">&nbsp;Value2_2:&nbsp;</label>
    <input type="text" class="form-control" id="val22" readonly style="background:black;color:lime" size="8">
    <label for="port">&nbsp;Value3:&nbsp;</label>
    <input type="text" class="form-control" id="val3" readonly style="background:black;color:lime" size="8">
    <label for="port">&nbsp;Value4:&nbsp;</label>
    <input type="text" class="form-control" id="val4" readonly style="background:black;color:lime" size="8">
    <label for="port">&nbsp;Value5:&nbsp;</label>
    <input type="text" class="form-control" id="val5" readonly style="background:black;color:lime" size="8">
    <label for="port">&nbsp;Date:&nbsp;</label>
    <input type="text" class="form-control" id="dt" readonly style="background:black;color:lime" size="16">
</form>
 </div>
 <div class="row" style="margin-top: 30px">
    <div class="col-4">
      <div id="chartContainer1" style="height: 370px; width: 100%;"></div>
    </div>
    <div class="col-4">
      <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
    </div>
    <div class="col-4">
      <div id="chartContainer3" style="height: 370px; width: 100%;"></div>
    </div>

 </div>
<div class="row" style="margin-top: 30px">
  <div class="col-12" >
      <div id="chartColumn" style="height: 370px; width: 100%;"></div>
  </div>
   
</div>
 </div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>