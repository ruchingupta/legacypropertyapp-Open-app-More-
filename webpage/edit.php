<?php session_start();
$attId = $_REQUEST['id'];

$username = $_SESSION['username'];
$password = $_SESSION['password'];


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



$query = "SELECT id,Like__c,Number_of_Bathrooms__c,Number_Of_Bedrooms__c,Price__c,Property_Image__c,Sold__c,Type__c,Zipcode__c,Address__c,Image_URL__c,MLS__c from Property_Detail__c where id='".$attId."'";
			
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
		

if(!empty($_POST['price'])){		
	
	$upadatePrice = $_POST['price'];
	
	$content = json_encode(array('Price__c' => $upadatePrice));
	$url = "$instance_url/services/data/v39.0/sobjects/Property_Detail__c/".$attId;

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER,array("Authorization: OAuth $access_token","Content-type: application/json"));
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
	curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

	curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if ($status != 204) {
	//die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) .", curl_errno " . curl_errno($curl));
	}

	curl_close($curl);
	
	header("Location: site.php");
			
}
?>

<?php echo $res['instance_url'];exit;if($res['instance_url'] == "https://ruchin-dev-ed.my.salesforce.com"){ ?>
<html>
<head>

<script>
$("#frmDemo").submit(function(e) {
	e.preventDefault();
	var price = $("#price").val();
	
	
	if(price == "") {
		$("#error_message").show().html("All Fields are Required");
	} else {
		$("#error_message").html("").hide();
		$.ajax({
			type: "POST",
			url: "edit.php?id=<?php echo $attId; ?>",
			data: "price="+price+,
			success: function(data){
				$('#success_message').fadeIn().html(data);
				setTimeout(function() {
					$('#success_message').fadeOut("slow");
				}, 2000 );

			}
		});
	}
})
</script>	
</head>
<body style="background-color: rgba(0, 0, 0, 0.44);">
<div style="position: absolute;
    top: 30%;
    left: 50%;
    margin: -150px 0 0 -150px;
    width: 300px;
    height: 300px;
    ">
<img src="<?php echo $res1['records'][0]->Image_URL__c; ?>" class="img-responsive" alt="Property Image" style="max-width: 100%;"/>
<form id="frmDemo" method="POST"  style="margin: 17px 0;
    ">


<label style="color: white;
    font-family: sans-serif;
    font-weight: bold;">Property Price : </label>

	<input id="price" type="text" value="<?php echo $res1['records'][0]->Price__c; ?>" name="price" required style="width:37%;
    margin-bottom: 10px;
    background: rgba(0,0,0,0.3);
    border: none;
    outline: none;
    padding: 10px;
    font-size: 13px;
    color: #fff;
    text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
    border: 1px solid rgba(0,0,0,0.3);
    border-radius: 4px;
    box-shadow: inset 0 -5px 45px rgba(100,100,100,0.2), 0 1px 1px rgba(255,255,255,0.2);
    -webkit-transition: box-shadow .5s ease;
    -moz-transition: box-shadow .5s ease;
    -o-transition: box-shadow .5s ease;
    -ms-transition: box-shadow .5s ease;
    transition: box-shadow .5s ease;margin-left: 63px;"/>
	
	<label style="color: white;
    font-family: sans-serif;
    font-weight: bold;margin-bottom: 10px;display: block;">Property Type : <span style = "font-size:17px;font-weight:normal;color:white;padding-left:65px;"><?php echo $res1['records'][0]->Type__c; ?></span></label>
	
	
	<label style="color: white;
    font-family: sans-serif;
    font-weight: bold;margin-bottom: 10px;    display: block;">Zipcode : <span style = "font-size:17px;font-weight:normal;color:white;padding-left:109px;"><?php echo $res1['records'][0]->Zipcode__c; ?></span></label>
	
	
	<label style="color: white;
    font-family: sans-serif;
    font-weight: bold;margin-bottom: 10px;display: flex;">Property Address: <span style = "font-size:17px;font-weight:normal;color:white;padding-left:118px;"><?php echo $res1['records'][0]->Address__c; ?></span></label>
	
	<label style="color: white;
    font-family: sans-serif;
    font-weight: bold;margin-bottom: 10px;    display: block;">MLS : <span style = "font-size:17px;font-weight:normal;color:white;padding-left:137px;"><?php echo $res1['records'][0]->MLS__c; ?></span></label>
	
	<label style="color: white;
    font-family: sans-serif;
    font-weight: bold;margin-bottom: 10px;    display: block;">Number of Bathrooms : <span style = "font-size:17px;font-weight:normal;color:white;"><?php echo $res1['records'][0]->Number_of_Bathrooms__c; ?></span></label>
	
	<label style="color: white;
    font-family: sans-serif;
    font-weight: bold;display: block;">Number of Bedrooms : <span style = "font-size:17px;font-weight:normal;color:white;padding-left:4px;"><?php echo $res1['records'][0]->Number_Of_Bedrooms__c; ?></span></label>
	

	
	<button type="submit" style="margin: 25px 0;
    background-color: #204d74;
    color: white;
    border-color: #122b40;
    border: 1px solid #122b40;
    padding: 7.5px 12px;
    border-radius: 4px;
    cursor: pointer;">Save</button>
	<div id="error_message" class="ajax_response" style="float:left;"></div>
	<div id="success_message" class="ajax_response" style="float:left;"></div>
</form>
</div>
</body>

</html>
<?php } else { ?>
<?php header("Location: ../index.php"); ?>
<?php } ?>
