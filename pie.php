<?php
// POST로 넘겨받은 값 가져오기
if(isset($_POST['one']) && isset($_POST['two']) && isset($_POST['three']) && isset($_POST['four'])&& isset($_POST['five'])&& isset($_POST['per1'])&& isset($_POST['per2'])&& isset($_POST['per3'])&& isset($_POST['per4'])&& isset($_POST['per5'])) {
  $one = $_POST["one"];
  $two = $_POST["two"];
  $three = $_POST["three"];
  $four = $_POST["four"];
  $five = $_POST["five"];

  $per1 = $_POST["per1"];
  $per2 = $_POST["per2"];
  $per3 = $_POST["per3"];
  $per4 = $_POST["per4"];
  $per5 = $_POST["per5"];


    // DB 연결
    $servername = "127.0.0.1"; // MariaDB 서버 이름
    $username = "root"; // MariaDB 사용자 이름
    $password = "55555"; // MariaDB 비밀번호
    $dbname = "pizza"; // 데이터베이스 이름

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // DB 연결 확인
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // 쿼리문 실행
    $query = "INSERT INTO usersinfo (one, two, three, four, five, per1, per2, per3, per4, per5) VALUES ('$one','$two','$three','$four','$five','$per1','$per2','$per3','$per4','$per5')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error: " . $query . "<br>" . mysqli_error($conn));
    }

    mysqli_close($conn);
}

// DB에서 데이터 가져오기
$servername = "localhost";
$username = "root";
$password = "55555";
$dbname = "pizza";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// DB 연결 확인
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 쿼리문 실행
$sql = "SELECT * FROM usersinfo ORDER BY time DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

if (!$result) {
  die("쿼리 실행 오류: " . mysqli_error($conn));
}

// 결과값 배열에 저장
if(mysqli_num_rows($result) > 0){
  $row = mysqli_fetch_assoc($result);
  $values = array(
      array($row['one'], (int)$row['per1']),
      array($row['two'], (int)$row['per2']),
      array($row['three'], (int)$row['per3']),
      array($row['four'], (int)$row['per4']),
      array($row['five'], (int)$row['per5'])
  );
} else {
  $values = array(
    array('No Data', 100)
  );
}

// DB 연결 종료
mysqli_close($conn);

// 데이터를 JSON 형태로 변환
$jsonTable = json_encode($values);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <?php
    // Replace the script source with PHP to ensure the library is loaded correctly
    $googleChartsSrc = "https://www.gstatic.com/charts/loader.js";
    echo "<script type='text/javascript' src='$googleChartsSrc'></script>";
    
    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "55555";
    $dbname = "pizza";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve data from the database
    $sql = "SELECT * FROM usersinfo ORDER BY time DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $one = $row["one"];
      $two = $row["two"];
      $three = $row["three"];
      $four = $row["four"];
      $five = $row["five"];
      $per1 = (int)$row["per1"];
      $per2 = (int)$row["per2"];
      $per3 = (int)$row["per3"];
      $per4 = (int)$row["per4"];
      $per5 = (int)$row["per5"];
    } else {
      $one = "";
      $two = "";
      $three = "";
      $four = "";
      $five = "";
      $per1 = 0;
      $per2 = 0;
      $per3 = 0;
      $per4 = 0;
      $per5 = 0;
    }

    mysqli_close($conn);
  ?>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = google.visualization.arrayToDataTable([
        ['<?php echo $one ?>', '<?php echo $per1 ?>'],
        ['<?php echo $one ?>', <?php echo $per1 ?>],
        ['<?php echo $two ?>', <?php echo $per2 ?>],
        ['<?php echo $three ?>', <?php echo $per3 ?>],
        ['<?php echo $four ?>', <?php echo $per4 ?>],
        ['<?php echo $five ?>', <?php echo $per5 ?>]
      ]);

      var options = {
        title: 'Ummm... your well made pizza ☆ -by SeungJuHan'
      };

      var chart = new google.visualization.PieChart(document.getElementById('piechart'));

      chart.draw(data, options);
    }
  </script>
</head>
<body>
  <div id="piechart" style="width: 900px; height: 500px;"></div>
</body>
</html>
