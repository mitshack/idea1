<?php 
/* Main page with two forms: sign up and log in */
require 'db.php';
session_start();

$result = $mysqli->query("SELECT * FROM menu");

$searchitem="";
$dropdown_count=0;
$pass=0;
$batchs='';
$last_query='';

$placed_query=$mysqli->query("SELECT * FROM placed where college_id='MUT15CS001'");

?>
<!doctype html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>MITS | Placements</title>

  <?php include 'css/css.html'; ?>
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
</head> 





<style type="text/css">
#wrapper {
  display: block;
  width: auto;
  background: #eeeeee;

  margin-bottom:20px; 

  -webkit-box-shadow: 2px 2px 30px -1px rgba(0,0,0,0.305);
}
</style>




<script type="text/javascript">
$(function(){
  $('#keywords').tablesorter(); 
});
</script>


<header>
      <div class="container">
        <div id="branding">
          <h1><span class="highlight">MITS</span> Placement Portal </h1>
        </div>
        <nav>
          <ul>
            <!--li><img src="add_child.png" width="200" height="142"></li-->
            <li><a href="index.php">Home</a></li>
            <li class="current"><a href="placement.php">Placements</a></li>

          </ul>
        </nav>
      </div>
    </header>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{  
    if (($_POST['search']!="")&&isset($_POST['users'])) { //user logging in
      $batchs=$_POST['users'];
      $searchitem = $_POST['search'];
      if($_POST['users']=='100')
      {
        $result = $mysqli->query("SELECT * FROM placed WHERE college_id like '%".$searchitem. "%' or student_name like '%".$searchitem."%' or company like'".$searchitem."%' or batch like '%" .$searchitem. "%' or salary_package like '%" .$searchitem."%'");
       $_SESSION['query'] = "SELECT * FROM placed WHERE college_id like '%".$searchitem. "%' or student_name like '%".$searchitem."%' or company like'".$searchitem."%' or batch like '%" .$searchitem. "%' or salary_package like '%" .$searchitem."%'";
      }
      else
      $result = $mysqli->query("SELECT * FROM placed WHERE batch='$batchs' and ( college_id like '%".$searchitem. "%' or student_name like '%".$searchitem."%' or company like'".$searchitem."%' or batch like '%" .$searchitem. "%' or salary_package like '%" .$searchitem."%')");
         $_SESSION['query'] = "SELECT * FROM placed WHERE batch='$batchs' and ( college_id like '%".$searchitem. "%' or student_name like '%".$searchitem."%' or company like'".$searchitem."%' or batch like '%" .$searchitem. "%' or salary_package like '%" .$searchitem."%')";
    }
    elseif (($_POST['users']=='100')&&($_POST['search']=="")) {
      $result = $mysqli->query("SELECT * FROM placed;");
       $_SESSION['query'] = "SELECT * FROM placed;";
    }
  else if (isset($_POST['users']))
  { 
    $batchs=$_POST['users'];
    $result = $mysqli->query("SELECT * FROM placed WHERE batch='$batchs'");
    $_SESSION['query'] = "SELECT * FROM placed WHERE batch='$batchs'";

  }



}
?>
<?php  
if(isset($_GET['button']))
{

$filename = "Placed.xls"; // File Name
// Download file
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");

$lastquery=$_SESSION['query'];
$placed_query=$mysqli->query($lastquery);
// Write data to file
$flag = false;
while ($row1 = $placed_query->fetch_assoc()) {
    if (!$flag) {

        echo implode("\t", array_keys($row1)) . "\r\n";
        $flag = true;
    }
        echo implode("\t", array_values($row1)) . "\r\n";
}
}
?>

<body>
<div id="wrapper">

<form class="form-inline" method="POST">

  <div class="dropdown">

<select name="users" class="dropbtn" onchange="showUser(this.value)">
  <option value="100">All Batch</option>
<?php 
    foreach ($batches as $rows) :?>
      <option <?php if($batchs == $rows['batch']){echo("selected");}?> value="<?=$rows['batch']?>"><?=$rows['batch']?></option>
      <?php $dropdown_count=$dropdown_count + 1?>
<?php endforeach;?>

  </select>

 
  <input type="text" name="search" placeholder="Search.." value="<?=$searchitem?>">
 </div>
</form>
<br>
<div id="txtHint">


<table id="keywords" cellspacing="0" cellpadding="0">
<thead>
<tr>
<th><span>Item name</span></th>
<th><span>Price</span></th>
<th><span>Availability</span></th>
<th><span>Time for delivery</span></th>

</tr>
</thead>

<tbody>
<?php
  $pl=$result;
 while($row = mysqli_fetch_array($result)) { ?>

    <td> <?php echo $row['item'] ?> </td>
    <td> <?php echo $row['price'] ?> </td>
    <td> <?php echo $row['availability'] ?> </td>
    <td> <?php echo $row['delivery'] ?> </td>

   </tr>

<?php
}

?>
</tbody>
</table>



</div>
<div class="dropdown">
<form >
<button id="export_button" name="button" class="dropbtn" onclick="clickHandler()" >Book</button>
</form>
</div>


</div>

<script>
    function clickHandler() {
      <?php
       // File Name
// Download file


$lastquery=$_SESSION['query'];
$placed_query=$mysqli->query("$lastquery");
// Write data to file
$flag = false;
while ($row1 = $placed_query->fetch_assoc()) {
    if (!$flag) {

        echo implode("\t", array_keys($row1)) . "\r\n";
        $flag = true;
    }
        echo implode("\t", array_values($row1)) . "\r\n";
} 
$filename = "Placed.xls";
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");

?>

    }
</script>

</body>
</html>