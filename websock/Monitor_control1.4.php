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
let gadc = 0;
let dc=0; 
var adc2;
var vrd,vr,vrms,vm;
var updateInterval = <?php echo $updateInterval ?>;
var xValue1 = <?php echo $x ?>;
var dataPoints1 = [{'x':xValue1,'y':adc1}]; //Area chart
var dataPoints2 = [{'x':xValue1,'y':adc2}]; //Area chart
var dataPointsvr1 = [{'x':xValue1,'y':vrd}];
var dataPointsvr2 = [{'x':xValue1,'y':vr}];
var dataPointsL1 = [{'x':xValue1,'y':vrms}];
var dataPointsL2 = [{'x':xValue1,'y':vm}];

google.charts.load('current', {'packages':['gauge']});
google.charts.setOnLoadCallback(drawChart1);
google.charts.setOnLoadCallback(drawChart2);

      function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['ADCx10',gadc],
          
        ]);

        var options = {
          width: 400, height: 160,
          redFrom: 20, redTo: 30,
          yellowFrom:10, yellowTo:20,
          minorTicks: 1
        };

        var chartg1 = new google.visualization.Gauge(document.getElementById('chart_div1'));

        chartg1.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1,gadc);
          chartg1.draw(data, options);
        },updateInterval);
       
      }

function drawChart2() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['%DutyCycle',dc],
          
        ]);

        var options = {
          width: 400, height: 160,
          redFrom: 80, redTo: 100,
          yellowFrom:70, yellowTo:80,
          minorTicks: 5
        };

        var chartg2 = new google.visualization.Gauge(document.getElementById('chart_div2'));

        chartg2.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1,dc);
          chartg2.draw(data, options);
        },updateInterval);
       
      }


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
   // color: "#6599FF",
    color: "#20ec95",
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

spgraph1 = function () {
 
var chart2 = new CanvasJS.Chart("chartSp1", {
  animationEnabled: true,
  title:{
    text: "Realtime ADC"
  },
  axisY: {
    title: "PWM Values",
    maximum:500,
    valueFormatString:adc2,
    suffix: "units",
    //prefix: "$"
  },
  data: [{
    type: "spline",
    markerSize:5,
    xValueFormatString: "ค่า pwm",
    yValueFormatString: "#,##0.##",
    xValueType: "dateTime",
    dataPoints:dataPoints2
  }]
});
 
chart2.render();
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 function updateChart() {
  xValue1 += updateInterval;
  dataPoints2.push({
    x: xValue1,
    y: adc2
  });
  
    chart2.render();   
  }
}

vrarea1 = function () {
 
var chart3 = new CanvasJS.Chart("vrArea1", {
  animationEnabled: true,
  theme: "light2",
  title:{
    text: "Realtime VR ADC"
  },
  axisX: {
    valueFormatString: "DD MMM"
  },
  axisY: {
    title: "PWM Values",
    maximum:1024
  },
  data: [{
    type: "splineArea",
    color: "#6599FF",
    xValueType: "dateTime",
    xValueFormatString: "DD MMM",
    yValueFormatString: "#,##0 Units",
    xValueType: "dateTime",
    dataPoints: dataPointsvr1
  }]
});
 
chart3.render();
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 function updateChart() {
  xValue1 += updateInterval;
  dataPointsvr1.push({
    x: xValue1,
    y: vrd
  });
  
    chart3.render();   
  }
}

vrarea2 = function () {
 
var chart4 = new CanvasJS.Chart("vrArea2", {
  animationEnabled: true,
  theme: "light2",
  zoomEnabled: true,
  zoomType: "y",
  title:{
    text: "Realtime Volts"
  },
  axisX: {
    valueFormatString: "DD MMM"
  },
  axisY: {
    title: "Volts",
    maximum:4,
    valueFormatString: "#,##0.##"
  },
  data: [{
    type: "splineArea",
    color: "#F9811D",
    xValueType: "dateTime",
    xValueFormatString: "D",
    yValueFormatString: "#,##0.## Volts",
    dataPoints: dataPointsvr2
  }]
});
 
chart4.render();
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 function updateChart() {
  xValue1 += updateInterval;
  dataPointsvr2.push({
    x: xValue1,
    y: vr
  });
  
    chart4.render();   
  }
}

Line1 = function() {
 
 var chartL1 = new CanvasJS.Chart("chartLine1", {
  theme: "dark2",
  title: {
    text: "ESP8266 Send Realtime Vrms pwm"
  },
  axisY:{
    includeZero: false,
    suffix: "Volts"
  },
  data: [{
    type: "line",
    xValueType: "dateTime",
    lineColor:"#2ffbe1", 
    yValueFormatString: "#,##0.0#",
    toolTipContent: "{y} Volts",
    dataPoints: dataPointsL1
  }]
});
chartL1.render();
 
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 
function updateChart() {
  dataPointsL1.push({ x: xValue1, y:vrms});
  xValue1 += updateInterval;
  chartL1.render();
};
 
}

Line2 = function() {
 
 var chartL1 = new CanvasJS.Chart("chartLine2", {
  theme: "dark1",
  title: {
    text: "ESP8266 Send Realtime Volts pwm"
  },
  axisY:{
    includeZero: false,
    suffix: "Volts"
  },
  data: [{
    type: "line",
    xValueType: "dateTime",
    lineColor:"#fb64e9", 
    yValueFormatString: "#,##0.0#",
    toolTipContent: "{y} Volts",
    dataPoints: dataPointsL2
  }]
});
chartL1.render();
 
var updateInterval = 2000;
setInterval(function () { updateChart() }, updateInterval);
 
function updateChart() {
  dataPointsL2.push({ x: xValue1, y:vm});
  xValue1 += updateInterval;
  chartL1.render();
};
 
}
 $(document).ready(function(){
         $('#serv').val(host);
         $('#prt').val("Port "+port);

         $(window).on('load',function(){
          $('#myModal').modal('show');
           });
         
         $('#con').click(function(){
            ConMQtt();
            $('#sta').append(" Connected")
            garea1(); 
            spgraph1();
            vrarea1();
            vrarea2();
            Line1();
            Line2();
         })

         $('#dchs').click(function(){
            
            // Disconnect();
            
              location.reload();
             $('#sta').html("Disconnect!").css({"color":"red"})
         })

        $('#pwm').on('change',function(){

           const $valueSpan = $('.valueSpan');
          const $value = $('#pwm');
          $valueSpan.html($value.val());
          $value.on('input change', () => {
            $valueSpan.html($value.val());
             });
           let pw = $("#pwm").val()
            //$('#pval').val(pw)
                SendPWM(pw)
           });
        
         $('#blk1').click(function(){
              var tm = $('#numblk').val();
              console.log(tm)
              Blinktimes(tm);
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
   client.subscribe("vr/send")
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
         
    if(message.destinationName.indexOf("led/") == 0){
          console.log(message.payloadString)
        if(message.payloadString.match("ON")){
          $('#status').val(message.payloadString)
         }
        if(message.payloadString.match("OFF")){
          $('#status').val(message.payloadString)
         }
        if(message.payloadString.match("Blink")){
          $('#status').val(message.payloadString)
         }
        if(message.payloadString.match("Times")){
           $('#status').val(message.payloadString)
         }
      }
    if(message.destinationName.indexOf("vr/") == 0){
          var vrdata = JSON.parse(message.payloadString);
             vrd = vrdata.value;
             vr = Number(vrdata.volts);

    }

    if(message.destinationName.indexOf("send/") == 0){
          console.log(message.payloadString)
           var ldrdata = JSON.parse(message.payloadString);
              adc1 = ldrdata.adc1
              adc2 = adc1
              gadc = ldrdata.adc1/10
              dc = ldrdata.duty
              //console.log(dc)
              $('#duty').val(dc)
              vrms = ldrdata.vrms
              $('#vrms').val(vrms)
              vm = ldrdata.vm
              console.log(vm)
    }
}

function Onled(){
  message = new Paho.MQTT.Message("on" + "\n");
   message.destinationName = "led/ctrl";
    client.send(message);
}
 
function Offled(){
  message = new Paho.MQTT.Message("off" + "\n");
   message.destinationName = "led/ctrl";
    client.send(message);
}

function Blink1(){
  message = new Paho.MQTT.Message("2" + "\n");
   message.destinationName = "led/ctrl";
    client.send(message);
}

function Blinktimes(tm){
  message = new Paho.MQTT.Message(tm + "\n");
   message.destinationName = "blk/num";
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
    #status{
      background-color: black;
      color:lime;
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
                  MQTT Cloud Connection (เชื่อมต่อ Cloud MQTT) By RJK ระบบนี้ทำเพื่อทดสอบ Pub/Sub MQTT 
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
            <label>ควบคุม LED</label>
            <h1><span class="badge badge-secondary">LED Control</span></h1>

            <button type="button" class="btn btn-success mb-5" id="on" onclick="Onled()">ON</button>&nbsp;
            <button type="button" class="btn btn-danger mb-5" id="off" onclick="Offled()">OFF</button>&nbsp;
            <button type="button" class="btn btn-warning mb-5" id="blk" onclick="Blink1()">BLINK 1</button>

           
            </form>

             <label>อุปรณ์แจ้งสถานะกลับมา</label>
            <input type="text" class="form-control mb-2 mr-sm-2" id="status" readonly>
            <label>ส่งค่าPWMไปควบคุม</label>
            <!--<label for="pval">PWM Value to device</label><input type="text" class="form-control mb-2 mr-sm-2" id="pval" readonly>-->
             <span class="font-weight-bold text-primary ml-2 mt-1 valueSpan"></span>
              <input type="range" class="custom-range" min="0" max="255" step="1" id="pwm">
              จำนวนครั้งกระพิบ(blink):
              <input type="number" id="numblk" min="1" max="20">
               

              <button type="button" class="btn btn-info mb-6" id="blk1">สั่งกระพิบ</button>
        
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
        Display ADC Graph & Gauge (ควบคุมอุปกรณ์จากหน้า Web  PWM Value to device , แสดงผลอุปกรณ์ส่งค่ากลับ)
       </a>
      </div>
      <div id="collapseThree" class="collapse" data-parent="#accordion">
       <div class="card-body">
       <label for="mdata" style="margin-left:10px;margin-top: 5px;">Data<span class="text-danger">From Device(ข้อมูลที่ส่งมาจาก ESP8266)</span>:
       </label>
        <textarea class="form-control" rows="2" cols="100" id="mdata" style="margin-left: 0px">
        </textarea>
         <div class="row">
          <div class="col-4">
            <div id="chart_div1" style="width:100%; height: 180px;"></div>
            
          </div>
          <div class="col-4">
            <div id="chart_div2" style="width:100%; height: 180px;"></div>
          </div>
           <div class="col-4">
             <form class="form-inline" style="margin-top:5px;">
                <label for="duty">% Duty Cycle</label>&nbsp;
                <input type="text" class="form-control mb-2 mr-sm-2" id="duty" size="3" readonly>
            </form>  
            <form class="form-inline" style="margin-top:5px;">    
                <label for="duty">Vrms(V)</label>&nbsp;
                <input type="text" class="form-control mb-2 mr-sm-2" id="vrms" size="3" readonly>
             
             </form>
           </div>
         </div>

         <div id="chartArea1" style="height: 370px; width: 100%;"></div>
      </div>
      </div>
     </div>
   </div>

    <div class="card">
      <div class="card-header">
       <a class="collapsed card-link" data-toggle="collapse" href="#collapse4" id="sta">
        Display Spline graph (ควบคุมอุปกรณ์จากหน้า Web  PWM Value to device , แสดงผลอุปกรณ์ส่งค่ากลับ)
       </a>
      </div>
      <div id="collapse4" class="collapse" data-parent="#accordion">
       <div class="card-body">
         <div id="chartSp1" style="height: 370px; width: 100%;"></div>
      </div>
      </div>
     </div>

     <div class="card">
      <div class="card-header">
       <a class="collapsed card-link" data-toggle="collapse" href="#collapse6" id="vt">
        Display Line graph voltage(ควบคุมอุปกรณ์จากหน้า Web  PWM Value to device , แสดงผลอุปกรณ์ส่งค่ากลับ)
       </a>
      </div>
      <div id="collapse6" class="collapse" data-parent="#accordion">
       <div class="card-body">
       <div class="row">
         <div class="col-6">
           <div id="chartLine1" style="height: 370px; width: 100%;"></div>
         </div>
         <div class="col-6">
          <div id="chartLine2" style="height: 370px; width: 100%;"></div>
         </div>
       </div>
      </div>
      </div>
     </div>


     <div class="card">
      <div class="card-header">
       <a class="collapsed card-link" data-toggle="collapse" href="#collapse5" id="sta">
        Display Spline graph data from IOT device(รับค่า การปรับ variable resistor(VR) ที่ส่งมาจากอุปกรณ์ ESP8266)
       </a>
      </div>
      <div id="collapse5" class="collapse" data-parent="#accordion">
       <div class="card-body">
         <div class="card-block">
            <div class="row">
                <div class="col-6">
                  <div id="vrArea1" style="height: 370px; width: 100%;"></div>
                </div>
                <div class="col-6">
                  <div id="vrArea2" style="height: 370px; width: 100%;"></div>
                </div>
            </div>
         </div>
       
      </div>
      </div>
     </div>
   </div>


   </div><!--accor-->

  </div><!--col9-->
    
  </div><!--row-->

 <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">RJK IOT</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <img src="imgs/mon_ctrl2.jpg" width="100%" height="380px">
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  



</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>