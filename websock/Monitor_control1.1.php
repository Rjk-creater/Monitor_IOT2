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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>

  <script src="mqttws31.js" type="text/javascript"></script>

 <!--<script src="config_maqiatto.js" type="text/javascript"></script>-->
<script src="config_m23.js" type="text/javascript"></script>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <script type="text/javascript">

var adc1;    
var updateInterval = <?php echo $updateInterval ?>;
var xValue1 = <?php echo $x ?>;
var dataPoints1 = [{'x':xValue1,'y':adc1}];

  garea1 = function () {
 
var chart1 = new CanvasJS.Chart("chartArea1", {
  animationEnabled: true,
  theme: "light2",
  title:{
    text: "Realtime ADC"
  },
  axisX: {
    valueFormatString: "DD MMM"
  },
  axisY: {
    title: "PWM Values",
    maximum:500
  },
  data: [{
    type: "splineArea",
    color: "#6599FF",
    xValueType: "dateTime",
    xValueFormatString: "DD MMM",
    yValueFormatString: "#,##0 Units",
    dataPoints: dataPoints1
  }]
});
 
chart1.render();
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 function updateChart() {
  xValue1 += updateInterval;
  dataPoints1.push({
    x: xValue1,
    y: adc1
  });
  
    chart1.render();   
  }
}

 $(document).ready(function(){
         $('#serv').val(host);
         $('#prt').val("Port "+port);
         
         $('#con').click(function(){
            ConMQtt();
            $('#sta').append(" Connected")
            garea1(); 
         })

         $('#dchs').click(function(){
            
            // Disconnect();
            
              location.reload();
             $('#sta').html("Disconnect!").css({"color":"red"})
         })

        $('#pwm').on('change',function(){
           let pw = $(this).val()
            $('#pval').val(pw)
                SendPWM(pw)
        });

     });

  </script>
  
  <script type="text/javascript">
    var path = '/rjkIOTmonitor'
    // Create a client instance
    
   // client = new Paho.MQTT.Client(location.hostname, Number(location.port), "clientId");
   
   client = new Paho.MQTT.Client(host,port,path,"ClientMQ_" + parseInt(Math.random() * 100, 10));
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
    //client.tls_set('cert/ca.crt')
// set callback handlers
client.onConnectionLost = onConnectionLost;
client.onMessageArrived = onMessageArrived;

// connect the client
//client.connect({onSuccess:onConnect});
// called when the client connects
function onConnect() {
   client.subscribe("adc/send")
   client.subscribe("led/ctrl")
   client.subscribe("send/pwm")

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

  console.log("onMessageArrived:"+message.payloadString);
    $('#mdata').prepend(message.payloadString +"\n")
         
    if(message.destinationName.indexOf("adc/") == 0){
          console.log(message.payloadString)
        
    }

    if(message.destinationName.indexOf("send/") == 0){
          console.log(message.payloadString)
           var ldrdata = JSON.parse(message.payloadString);
              adc1 = ldrdata.adc1
    }
}

function Onled(){
  message = new Paho.MQTT.Message("1" + "\n");
   message.destinationName = "led/ctrl";
    client.send(message);
}
 
function Offled(){
  message = new Paho.MQTT.Message("0" + "\n");
   message.destinationName = "led/ctrl";
    client.send(message);
}

function SendPWM(vp){
  message = new Paho.MQTT.Message(vp + "\n");
   message.destinationName = "adc/send";
    client.send(message);
}

function Send(){

    message = new Paho.MQTT.Message("Server received ok" + "\n");
    message.destinationName = "Server/reply";
    client.send(message);
 }

function ConMQtt(){

   client.connect(options);
      $.notify("Connected", "success");
     //alert(client.host+" port: "+client.port);
    
}

function Disconnect() {
      //alert("client is disconnecting..");
      this.client.disconnect();
      $.notify("Disconnect!", "error");
 }
  
</script>
<style type="text/css">
    #mdata{
    color:yellow;
    background-color: blue;
    margin-left: 30px;
    }

  </style>
  </head>
<body>
  
  <div class="container">
   <div class="row" style="margin-top: 5px;">
      <div class="col-2">
      <img src="imgs/rjkiot3.jpg" class="rounded-circle" alt="RJK IOT" width="150" height="150">
      </div>
    <div class="col-10">
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
                <input type="text" class="form-control mb-2 mr-sm-2" id="serv" readonly>
                <input type="text" class="form-control mb-2 mr-sm-2" id="prt" readonly>
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

   <div class="row" style="margin-top: 10px;">
    <div class="col-3">
   
    <div id="accordion">    
    <div class="card ">
     <div class="card-header bg-info">
       <a class="collapsed card-link" style="color:white;" data-toggle="collapse" href="#collapseTwo">
        Control panel
      </a>
    </div>
      <div id="collapseTwo" class="collapse" data-parent="#accordion">
        <div class="card-body">
           <form class="form-inline">
            <h1>LED<span class="badge badge-secondary">Control</span></h1>
            <button type="button" class="btn btn-success mb-5" id="on" onclick="Onled();">ON</button>&nbsp;
            <button type="button" class="btn btn-danger mb-5" id="off" onclick="Offled()">OFF</button>&nbsp;
            <button type="button" class="btn btn-warning mb-5" id="blk">BLINK</button>
            </form>
            <label for="pval">PWM Value</label><input type="text" class="form-control mb-2 mr-sm-2" id="pval" readonly>
              <input type="range" class="custom-range" min="0" max="255" step="1" id="pwm">
        </div>
     </div>
   </div>
   </div>
  </div>

 <div class="col-9">
  <div id="accordion">    
    <div class="card">
      <div class="card-header">
      <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree" id="sta">
        Display 
      </a>
      </div>
      <div id="collapseThree" class="collapse" data-parent="#accordion">
       <div class="card-body">
        <label for="mdata" style="margin-left:10px;margin-top: 5px;">Data<span class="text-danger">From Device(ข้อมูลที่ส่งมาจาก ESP8266)</span>:</label>
        <textarea class="form-control" rows="2" cols="100" id="mdata" style="margin-left: 0px"></textarea>
         
         <div id="chartArea1" style="height: 370px; width: 100%;"></div>

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