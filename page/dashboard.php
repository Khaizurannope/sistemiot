<?php
$sql = "SELECT * FROM devices WHERE active = 'Yes'";
$result = mysqli_query($conn, $sql);
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard Monitoring</h1>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><span id="suhu">23</span>°C</h3>
              <p>Suhu</p>
            </div>
            <div class="icon">
              <i class="fas fa-temperature-high" style="font-size: 40px;"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><span id="kelembaban">85</span>%</h3>
              <p>Kelembaban</p>
            </div>
            <div class="icon">
              <i class="fas fa-water" style="font-size: 40px;"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3 id="potentiometer">10</h3>
              <p>Potentiometer</p>
            </div>
            <div class="icon">
              <i class="fas fa-tachometer-alt" style="font-size: 40px;"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title">Servo</h3>
            </div>
            <div class="card-body">
              <div class="row margin">
                <div class="col-12">
                  <input id="servo" onChange="publishServo()" type="text">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Lampu</h3>
            </div>
            <div class="card-body table-responsive pad">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-primary" id="label-lampu1-nyala">
                  <input type="radio" name="lampu1" onChange="publishLampu(this)" id="lampu1-nyala" autocomplete="off"> Led Nyala
                </label>
                <label class="btn btn-primary" id="label-lampu1-mati">
                  <input type="radio" name="lampu1" onChange="publishLampu(this)" id="lampu1-mati" autocomplete="off"> Led Mati
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card card-indigo">
            <div class="card-header">
              <h3 class="card-title">Status Perangkat</h3>
            </div>
            <div class="card-body table-responsive p-0" style="height: 300px;">
              <table class="table table-head-fixed text-nowrap">
                <thead>
                  <tr>
                    <th>Serial Number</th>
                    <th>Location</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = mysqli_fetch_assoc($result)) { ?>
                  <tr>
                    <td><?php echo $row['serial_number'] ?></td>
                    <td><?php echo $row['location'] ?></td>
                    <td id="kelasiot/status/<?php echo $row['serial_number'] ?>" style="color:red;">Offline</td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>

<script>
  const serial_number = "12345678";  // Serial number yang sesuai
  const clientId = Math.random().toString(16).substr(2, 8);
  const host = "wss://bootclassiot.cloud.shiftr.io:443";

  const options = {
    keepalive: 30,
    clientId: clientId,
    username: "bootclassiot",
    password: "rsBKEKFT37hfBR0v",
    protocolId: 'MQTT',
    protocolVersion: 4,
    clean: true,
    reconnectPeriod: 1000,
    connectTimeout: 30 * 1000
  };

  const client = mqtt.connect(host, options);

  client.on("connect", () => {
    console.log("Menghubungkan ke broker...");
    console.log("Terhubung ke broker");

    // Update status di navbar
    document.getElementById("status").style.color = "green";
    document.getElementById("status").innerHTML = "Terhubung";

    client.subscribe(`kelasiot/${serial_number}/#`, { qos: 1 });      // tetap langganan data utama
    client.subscribe(`kelasiot/status/+`, { qos: 1 });                // langganan semua status perangkat

  });

  client.on("message", function(topic, payload) {
    const value = payload.toString();

    if (topic === `kelasiot/${serial_number}/suhu`) {
      document.getElementById("suhu").innerHTML = value;
    } else if (topic === `kelasiot/${serial_number}/kelembaban`) {
      document.getElementById("kelembaban").innerHTML = value;
    } else if (topic === `kelasiot/${serial_number}/potentiometer`) {
      document.getElementById("potentiometer").innerHTML = value;
    } else if (topic === `kelasiot/${serial_number}/servo`) {
      let servo1 = $("#servo").data("ionRangeSlider");
      if (servo1) {
        servo1.update({ from: parseInt(value) });
      }
    } else if (topic === `kelasiot/${serial_number}/led`) {
      if (value === "nyala") {
        document.getElementById("label-lampu1-nyala").classList.add("active");
        document.getElementById("label-lampu1-mati").classList.remove("active");
      } else if (value === "mati") {
        document.getElementById("label-lampu1-nyala").classList.remove("active");
        document.getElementById("label-lampu1-mati").classList.add("active");
      }
    }

    if (topic.startsWith("kelasiot/status/")) {
  const el = document.getElementById(topic);
  if (el) {
    el.innerHTML = value;
    el.style.color = (value.toLowerCase() === "online") ? "green" : "red";
  }
}
});

  function publishServo() {
    const data = document.getElementById("servo").value;
    client.publish(`kelasiot/${serial_number}/servo`, data, { qos: 1, retain: true });
  }

  function publishLampu() {
    let data = "";
    if (document.getElementById("lampu1-nyala").checked) {
      data = "nyala";
    } else if (document.getElementById("lampu1-mati").checked) {
      data = "mati";
    }
    client.publish(`kelasiot/${serial_number}/led`, data, { qos: 1, retain: true });
  }
</script>
