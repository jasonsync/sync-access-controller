<?php
    header('Access-Control-Allow-Private-Network: true');
    header('Access-Control-Allow-Origin: *');
 ?>

 <!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="description" content="Sync Access">
  <title>Sync Access™</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
  <h1>Welcome</h1>
  <br>
  <div class="wrapper">

    <div class="dashboard">
      <div class="tile button" onclick="btnOpenGate_onclick(this)">
        Open Gate 1
      </div>
      <div class="tile button" onclick="btnOpenGate_onclick(this)">
        Open Gate 2
      </div>

      <div class="tile button" onclick="btnOpenGate_onclick(this)">
        Open Boom 1
      </div>

      <div class="tile button" onclick="btnOpenGate_onclick(this)">
        Open Boom 2
      </div>
    </div>

  </div>

  <!-- <button type="button" name="button" onclick="btnOpenGate_onclick();">Open Gate</button>-->
  <script type="text/javascript">
    function btnOpenGate_onclick(e) {
      disable_button_await_response(e);
      let key = "nnD62omYXHK1So0xyZEEj73Y0HIiH7WbOItRNY_kIOe";
      let event = "open_gate";
      fetch('ewelink-proxy.php', {
          method: 'POST',
          headers: {
            'Accept': 'application/json, text/plain, */*',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            "event": event
          })
        })
        .then(res => res.json())
        .then(res => console.log(res))
        .finally(() => {
          enable_button_after_response(e);
        });
    }

    function disable_button_await_response(e) {
      e.classList.add("disabled");
    }

    function enable_button_after_response(e) {
      e.classList.remove("disabled");
    }
  </script>
</body>

</html>
