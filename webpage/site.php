<?php 
session_start(); 

header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire');

if((!empty($_REQUEST['username']) && !empty($_REQUEST['password']) )  || empty($_REQUEST['PHPSESSID'])){
	
	$_SESSION['username'] = $_REQUEST['username'];
	$_SESSION['password'] = $_REQUEST['password'];
}

$username = $_SESSION['username'];
$password = $_SESSION['password'];
 
print_r($_SESSION);
//Start Connection
$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => "https://login.salesforce.com/services/oauth2/token",
	CURLOPT_SSL_VERIFYPEER =>0,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "client_id=3MVG9g9rbsTkKnAXEHsxzO08QzDD2_0GnIedlmVrxZWJcZtP_A8dGjmuGcDlZjeqXCEUgjUxIp_Sau3voeK1v&client_secret=1687696237180270615&grant_type=password&username=$username&password=$password",
	CURLOPT_HTTPHEADER => array(
	"cache-control: no-cache",
	"content-type: application/x-www-form-urlencoded",
	"postman-token: d3723d71-0840-5a08-8635-da468eca0978"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$res = (array)json_decode($response);

$access_token = $res['access_token'];

$instance_url = "https://ruchin-dev-ed.my.salesforce.com";

//End Connection 


			$query = "SELECT id,Like__c,Number_of_Bathrooms__c,Number_Of_Bedrooms__c,Price__c,Property_Image__c,Sold__c,Type__c,Zipcode__c,Address__c,Image_URL__c,MLS__c from Property_Detail__c WHERE Sold__c != true order by Price__c asc";
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://ruchin-dev-ed.my.salesforce.com/services/data/v39.0/query?q=".urlencode($query),
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER =>0,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 500,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
				"authorization: Bearer ".$res['access_token'],
				"cache-control: no-cache"
				),
			));

			$response1 = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);
			$res1 = (array)json_decode($response1);
			//echo "<pre>";print_r($res1['records']);exit;
			
					
	
	
?>
<?php if($res['instance_url'] == "https://ruchin-dev-ed.my.salesforce.com"){ ?>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 
    Dragonfruit Template 
    http://www.templatemo.com/preview/templatemo_411_dragonfruit 
    -->
    <title>Property Detail</title>
    <meta name="description" content="" />
    <!-- templatemo 411 dragonfruit -->
    <meta name="author" content="templatemo">
    <!-- Favicon-->
    <link rel="shortcut icon" href="./favicon.png" />		
    <!-- Font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Camera -->
    <link href="css/camera.css" rel="stylesheet">
    <!-- Template  -->
    <link href="css/templatemo_style.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
  </head>
<body>




<div id="templatemo_mobile_menu">
        <ul class="nav nav-pills nav-stacked">
           <!-- <li><a href="#templatemo_banner_slide"><i class="glyphicon glyphicon-home"></i> &nbsp; Home</a></li>
           <li><a href="#templatemo_about"><i class="glyphicon glyphicon-briefcase"></i> &nbsp; About</a></li>
            <li><a href="#templatemo_events"><i class="glyphicon glyphicon-bullhorn"></i> &nbsp; Events</a></li>
            <li><a href="#templatemo_timeline"><i class="glyphicon glyphicon-calendar"></i> &nbsp; Timeline</a></li>
            <li><a rel="nofollow" href="http://www.google.com" class="external-link"><i class="glyphicon glyphicon-export"></i> &nbsp; External</a></li>
            <li><a href="#templatemo_contact"><i class="glyphicon glyphicon-phone-alt"></i> &nbsp; Contact</a></li> -->
        </ul>
</div>

<div class="container_wapper">
    <div id="templatemo_banner_menu">
        <div class="container-fluid">
            <div class="col-xs-4 templatemo_logo" style="    width: 36.333333%;">
            	<a href="site.php">
                	<img src="images/halth.png" id="logo_img" alt="dragonfruit website template" title="Dragonfruit Template"  />
                	<h1 id="logo_text" style="color: #1d1f80;">Yorkshire <span style="color: #1d1f80;">Hathaway</span></h1>
                </a>
				
            </div>
			<div>
			<a href="logout.php" class="btn btn-primary" style="float: right;
    margin-top: 30px;">Logout</a>
			</div>
			
            <div class="col-sm-8 hidden-xs">
                <ul class="nav nav-justified">
                  <!--  <li><a href="#templatemo_banner_slide">Home</a></li>
                    <li><a href="#templatemo_about">About</a></li>
                    <li><a href="#templatemo_events">Events</a></li>
                  <li><a href="#templatemo_timeline">Timeline</a></li>
                    <li><a rel="nofollow" href="http://www.google.com" class="external-link">External</a></li>
                    <li><a href="#templatemo_contact">Contact</a></li> -->
                 </ul>
            </div>
            <div class="col-xs-8 visible-xs">
                <a href="#" id="mobile_menu"><span class="glyphicon glyphicon-th-list"></span></a>
            </div>
        </div>
    </div>
</div>

<div id="templatemo_events" class="container_wapper">
    <div class="container-fluid">
        <h1 style="padding-top: 50px;">Properties</h1>
		<?php foreach ($res1['records'] as $values) { ?>
		
        <div class="col-sm-10 col-sm-offset-1 col-md-4 col-md-offset-0">
            <div class="event_box_wap event_animate_left">
                <div class="event_box_img">
                    <img src="<?php echo $values->Image_URL__c; ?>" class="img-responsive" alt="Property Image" title="<?php echo $values->Type__c; ?>" style="    height: 330px;"/>
                </div>
                <div class="event_box_caption">
				<div style="display: inline-block; margin
				:10px 5px;">
                    <div style="font-family: sans-serif;font-weight: bold;">Property Type:  <?php echo $values->Type__c; ?></div>
					<div style="font-family: sans-serif;font-weight: bold;">Property Price:  <span style = "color: red;"><?php echo $values->Price__c; ?></span></div>
					
					<div style="font-family: sans-serif;font-weight: bold;">Zipcode:  <?php echo $values->Zipcode__c; ?></div>
				</div>
				<div style="float: right;margin
				:10px 5px;">
				
				<div style="font-family: sans-serif;font-weight: bold;">MLS:  <?php echo $values->MLS__c; ?></div>
					<div style="font-family: sans-serif;font-weight: bold;">Number of Bathrooms:  <?php echo $values->Number_of_Bathrooms__c; ?></div>
					<div style="font-family: sans-serif;font-weight: bold;">Number of Bedrooms:  <?php echo $values->Number_Of_Bedrooms__c; ?></div>
					
				</div>
				
				<div style="margin: 0 5px;">
				<div style="font-family: sans-serif;font-weight: bold;height: 80px;">Property Address:  <?php echo $values->Address__c; ?></div>
				</div>
				<div style="    display: inline-block;">
				<a href="edit.php?id=<?php echo $values->Id; ?>" class="btn btn-success" style="margin: 25px 50% 0 260%;">Edit</a> 
				</div>

				<div style="float: right;">
				<?php if($values->Like__c == false){ ?>
				<a href="like.php?id=<?php echo $values->Id; ?>" class="btn btn-primary" style="margin: 25px -212% 0;" id="action"><i class="fa fa-thumbs-o-up" aria-hidden="true" style="padding-right: 5px;"></i>Like</a>
				<?php } else { ?>
				<a href="unlike.php?id=<?php echo $values->Id; ?>" class="btn btn-danger" style="margin: 25px -160% 0;" id="actionn"><i class="fa fa-thumbs-o-down" aria-hidden="true" style="padding-right: 5px;"></i>Unlike</a>
				<?php } ?>
	
				</div>
				
					
					
                </div>
            </div>
        </div>
		<?php } ?>
      
    </div>
</div>


<div id="templatemo_footer">
    <div>
        <p id="footer">Copyright © 2017 Yorkshire Hathaway ( Ruchin’s Demo)</p>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.singlePageNav.min.js"></script>
<script src="js/unslider.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
<script src="js/templatemo_script.js"></script>

</body>
</html>
<?php } else {
print_r($_SESSION);
	?>
<?php //header("Location: ../index.php"); ?>
<?php } ?>

