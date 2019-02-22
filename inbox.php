<?php
session_start();
if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}

$naslov = "Inbox";
require_once("header.php");
require_once("a_navigacija.php");

echo '<script>
  document.getElementById("inbox").classList.add("active");
</script>';


echo '  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>';

  ?>
  
<?php

// Ignore Warnings
error_reporting(E_ALL & ~E_NOTICE & ~8192);

// Connect to Database
require_once "baza.php";

// Days,Hours,Minutes Time Format
require_once "time.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inbox System</title>

<style>

  body
  {
    margin: 0;
    height: 100%
  }
  
  a
  {
	color:#000000;
	text-decoration:none;
  }
  
  #loading {
	position:fixed;
	z-index:2000;
	top:0;
	bottom:0;
	right:0;
	left:0;
}

#loading img {
	position:absolute;
	padding:10px;
	top:45%;
	left:45%;
	width:24px;
	height:24px;
	background:rgba(0,0,0,0.6);
	border-radius:5px;
	-webkit-border-radius:5px;
}

  table, #pages
  {
    margin: 5% auto 0 auto;
    width: 800px;
    height: auto;
    overflow: hidden;
    background:  #f1f1f1;
  }
  
  table tr
  {
    width: 100%;
  }
  
  table tr th
  {
    background: #3e3e3e;
    color: #fff !important;
  }
  
  table tr th, table tr td
  {
    padding: 10px;
    color: #999;
    font-family: arial;
    font-size: 12px;
    text-align: center;
    border-left: 1px solid #fff;
    border-right: 1px solid #fff;
  }
  
  table tr td
  {
    border-bottom: 2px solid #fff;
  }
  
   table tr td a
  {
	color: #999
    text-decoration: none;
  }
  
  #pages
  {
    margin: 0 auto;
    background: none;
  }
  
   #pages a
  {
    display: inline-block;
    margin: 0 10px 0 0;
    padding: 5px 10px;
    background: #3e3e3e;
    color: #fff;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    font-family: arial;
    font-size: 12px;
    text-decoration: none;
  }
  
  a.active
  {
	background: #ccc !important;
	color: #666 !important;
  }
  
  #msg
  {
	margin: 5% auto 0 auto;
	padding: 20px;
	width: 800px;
  }
  
  #msg table tr td
  {
	background: #3e3e3e;
	color: #fff;
  }
  
  #msg pre
  {
	margin: 20px 0;
	padding: 10px;
	background: #f1f1f1;
  }
  
  .remove
  {
    margin: 10px 0 0 0;
	padding: 6px 10px;
	color: #fff;
    background: #f00;
	text-decoration: none;
	font-family: arial;
	font-size: 12px
    cursor: pointer;
    border-radius: 3px;
    -webkit-border-radius: 3px;
  }
  
  .back
  {
	margin: 10px 0 0 0;
	padding: 6px 10px;
	color: #fff;
    background: #f00;
	text-decoration: none;
	font-family: arial;
	font-size: 12px
    cursor: pointer;
    border-radius: 3px;
    -webkit-border-radius: 3px;
   }
  
  
  form
  {
    margin:5% auto 0 auto;
    width: 600px;
    height: auto;
    overflow: hidden;
    background: #f1f1f1;
    font-family: arial;
    font-size: 12px;
    color: #999;
    border:2px solid #ccc
  }

  form label
  {
    margin: 10px;
    padding: 0;
    float: left;
    clear: both;
    cursor: pointer;
  }
  
  form textarea
  {
    min-height: 160px;
    max-height: 200px;
    resize: vertical;
  }
  form input, form textarea
  {
    margin: 10px;
    padding: 0;
    float: left;
    clear: both;
  }
  
  form input[type=submit]
  {
    padding: 6px 10px;
    background: #3e3e3e;
    color: #fff;
    cursor: pointer;
    border: none;
    border-radius: 3px;
    -webkit-border-radius: 3px;
  }
  
  form input[type=submit]:hover
  {
    background: #09f;
  }
  
  .required
  {
    font-family: arial;
    font-size: 12px;
    color: #f00;
    text-align: center;
  }
  
  .success
  {
    font-family: arial;
    font-size: 12px;
    color: green;
    text-align: center;
  }
  

#pages a:hover {
	background:#09f;
}


#msg pre {
	margin:0 0 20px;
	padding:10px;
	float:left;
	color:#000000;
	font-family: arial;
	font-size:12px;
	line-height:18px;
	white-space: -moz-pre-wrap;
	white-space: -pre-wrap;
	white-space: -o-pre-wrap;
	white-space: pre-wrap;
	word-wrap: break-word;
}
  
</style>
<script type="text/javascript" src="js/jquery.js"></script>
</head>

<body>

<script type="text/javascript">

$("body").prepend('<div id="loading"><img src="img/loading.gif" alt="Loading.." title="Loading.." /></div>');

$(window).load(function(){
	$("#inbox, #msg").fadeIn("slow");
	$("#loading").fadeOut("slow");
});

</script>

<?php
	if(isset($_GET['msg'])){

		$id = $_GET['msg'];
		mysqli_query($baza,"UPDATE messages SET open = '1' WHERE id = '$id'");
		$msg = mysqli_query($baza,"SELECT * FROM messages WHERE id = '$id'");
		$row = mysqli_fetch_assoc($msg);
			$from = $row['from'];
			$email = $row['email'];
			$date = $row['date'];
			$time = time_passed($row['time']);
			$message = $row['message'];
?>

<div id="msg">

<a href="inbox.php">← Back to Inbox</a>

<table>
	<tr>
		<td>From : <strong><?php echo $from; ?></strong></td>
		<td>Email : <strong><?php echo $email; ?></strong></td>
		<td>Date : <strong><?php echo $date; ?></strong></td>
		<td>Time : <strong><?php echo $time; ?></strong></td>
	</tr>
</table>

<pre><?php echo $message; ?></pre>

<a class="remove btn danger" href="?remove=<?php echo $id; ?>">Delete this message</a>

</div>

<script type="text/javascript">

$('.remove').click(function(){
	var agree=confirm("Are you sure you?");
	if (agree) {
		return true;
	}else {
		return false;
	}
});

</script>

<?php

exit();

} else if(isset($_GET['remove'])){

	$id = $_GET['remove'];
	$remove = mysqli_query($baza,"DELETE FROM messages WHERE id = '$id'");
	if($remove){
		echo '<script>window.location = "./"</script>';
	}else {
		die("Please Refresh the page.");
	}

	exit();

} else {

?>

<div id="inbox">

	<table>

		<tr>

			<th width="10%">#</th>
			<th>From</th>
			<th>Email</th>
			<th>Subject</th>
			<th>Sent</th>
			<th>Seen</th>

		</tr>

			<?php

				$limit = 5;
				$p = $_GET['p'];

				$get_total = mysqli_num_rows(mysqli_query($baza, "SELECT * FROM messages"));
				$total = ceil($get_total/$limit);

				if(!isset($p)){
					$offset = 0;
				}else if($p == '1'){
					$offset = 0;
				}else if($p <= '0'){
					$offset = 0;
					echo '<script>window.location = "./";</script>';
				}else {
					$offset = ($p - 1) * $limit;
				}

				$inbox = mysqli_query($baza, "SELECT * FROM messages LIMIT $offset,$limit");
				$rows = mysqli_num_rows($inbox);
				while($row = mysqli_fetch_assoc($inbox)){
					$id = $row['id'];
					$from = $row['from'];
					$email = $row['email'];

					if(strlen($row['subject']) >= 50){
						$subject = substr($row['subject'],0,50)."..";
					}else {
						$subject = $row['subject'];
					}

					$message = $row['message'];
					$date = $row['date'];
					$time = time_passed($row['time']);
					if($row['open'] == '1'){
						$open = '<img src="img/open.png" alt="Opened" title="Opened" />';
					}else {
						$open = '<img src="img/not_open.png" alt="Opened" title="Not Opened" />';
					}

					echo '<tr class="border_bottom">';
						echo '<td><a href="?msg='.$id.'">'.$id.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$from.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$email.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$subject.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$date.' - '.$time.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$open.'</a></td>';
					echo '</tr>';

				}

				if($rows <= 0){
					echo '<tr><td width="100%">There\'s no messages at this moment, check back later!</td></tr>';
				}

			?>

	</table>

	<?php if($get_total > $limit){ ?>

		<div id="pages">

			<?php
				for($i=1; $i<$total; $i++){
					echo ($i == $p) ? '<a class="btn active">'.$i.'</a>' : '<a class="btn" href="?p='.$i.'">'.$i.'</a>';
				}
			?>

		</div>

	<?php } ?>

</div>

<?php } ?>

</body>
</html>