<?php
/*******************************************************************************************
 *    Project's Name: VisitME
 *            School: University of Massachusetts Boston
 *        Department: Computer Science
 * Project's website: http://code.google.com/p/visitme/
 *             Class: CS682 - Software Development Laboratory I
 * -----------------------------------------------------------------------------------------
 * Status    Date        Authors       Comments
 * -----------------------------------------------------------------------------------------
 * Created   11/06/2009  Brian Moy     Successfully run on Facebook
 * Modified  11/08/2009  Brian Moy     Able to extract users' info, query Kayak RSS Feeds,
 *                                     and print out info on canvas
 *
 * *****************************************************************************************
 * IMPORTANT:
 * $appapikey and $appsecret variables must be set with your Facebook app API key
 * and secret. If you do not have them yet, you may sign up at
 * http://www.facebook.com/developers/ to get them.
 *******************************************************************************************/
?>

<?php
    /* include the PHP Facebook Client Library to help
      with the API calls and make life easy */
    require_once('/includes/facebook/facebook.php');

    /* initialize the facebook API with your application API Key
      and Secret */
    $appapikey = '';  // SET YOUR FACEBOOK API KEY
    $appsecret = '';  // SET YOUR FACEBOOK SECRET
    $facebook = new Facebook($appapikey, $appsecret);

    /* require the user to be logged into Facebook before
      using the application. If they are not logged in they
      will first be directed to a Facebook login page and then
      back to the application's page. require_login() returns
      the user's unique ID which we will store in fb_user */

    $user_id = $facebook->require_login();
?>


<center>
    <img src="http://irock.comlu.com/logo.JPG" width="342" height="109" alt="logo" />

    <form action="http://apps.facebook.com/visitmebeta/" id="searchForm" method="post">
        Search friend: <fb:friend-selector uid="#request.userID#" name="uid" idname="friend_sel" />
        <input type="submit" value="Go!" />
    </form>
</center>

<br />

<?php
    $userInfo = $facebook->api_client->users_getInfo($user_id, 'last_name, first_name, current_location');
    $data['first_name'] = $userInfo[0]['first_name'];
    $data['last_name'] = $userInfo[0]['last_name'];
    $data['current_location'] = $userInfo[0]['current_location'];


    $targetedFriendId = $_POST['friend_sel'];
    if($targetedFriendId!="")
    {
        $targetUserInfo = $facebook->api_client->users_getInfo($targetedFriendId, 'last_name, first_name, current_location');
        $targetData['last_name'] = $targetUserInfo[0]['last_name'];
        $targetData['first_name'] = $targetUserInfo[0]['first_name'];
        $targetData['current_location'] = $targetUserInfo[0]['current_location'];
    }
    else
    {
        exit(1);
    }

   if((($data['current_location']['city']=="") || ($targetData['current_location']['city']=="")) && ($targetedFriendId!=""))
   {
       echo "<center><br />";
       echo "Your current location: ".$data['current_location']['city']."<br />";
       echo $targetData['first_name']."'s current location: ".$targetData['current_location']['city']."<br />";
       echo "Unable to perform search! Both you and ".$targetData['first_name']."'s current locations are required to search for flight information!";
       echo "</center>";
       exit(1);
   }
   else if($data['current_location']['city']==$targetData['current_location']['city'])
   {
       echo "<center><br />";
       echo "You and ".$targetData['first_name']." are in the same city!<br />";
       exit(0);
   }

   $fileName = "http://www.kayak.com/labs/api/search/airports.txt";
   $file = fopen($fileName, "r");
   $origin = "";
   $destination = "";

   while(! feof($file))
   {
       $readLine = fgetcsv($file, 0,"\t");
       $code = $readLine[0];
       $name = $readLine[1];
       $city = $readLine[2];
       $state = $readLine[3];
       $country = $readLine[4];
       if($data['current_location']['city']==$city)
       {
             $origin = $code;
       }
       if($targetData['current_location']['city']==$city)
       {
            $destination = $code;
       }
       if(($origin!="") && ($destination!=""))
       {
           break;
       }
   }
   fclose($file);


   /************** Airport-to-Airport RSS feeds **************/
   //$tm = "200911"; // Travel month,  YYYYMM format, Optional parameter
   $mc = "USD"; // Currency code, Optional parameter
   //$sort = "searches"; // Optional parameter
   $url = "http://www.kayak.com/h/rss/fare";
   $userAgent = "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)";
   $params = "code=".$origin."&dest=".$destination."&mc=".$mc;

   function searchFlightResults($params, $url, $userAgent)
   {
       /* Send HTTP GET */
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_GET,1);
       curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
       $result=curl_exec ($ch);
       curl_close ($ch);
       /* Parse return XML into an array */
       $p = xml_parser_create();
       xml_parse_into_struct($p, $result, $vals, $index);
       xml_parser_free($p);
       //print_r($result);
       return ($vals);
   }

   if($targetedFriendId!="")
   {
       $vals = searchFlightResults($params, $url, $userAgent);
       echo "<center>";
       echo "<br /><b>You can visit ".$targetData['first_name']." for...</b><br />";
       echo $vals[43]['value']."<br />";
       echo "Depart Date: ".$vals[49]['value']."<br />";
       echo "Return Date: ".$vals[51]['value']."<br />";
       echo $vals[31]['value']." to ".$vals[37]['value']."<br />";
       echo "Price: $".$vals[45]['value']."<br />";
       echo "Description: ".$vals[25]['value']."<br/>";
       echo "</center>";

       /***************************************************/
       $params = "code=".$destination."&dest=".$origin."&mc=".$mc;
       $vals = searchFlightResults($params, $url, $userAgent);
       echo "<center>";
       echo "<br /><b>".$targetData['first_name']." can visit you for...</b><br />";
       echo $vals[43]['value']."<br />";
       echo "Depart Date: ".$vals[49]['value']."<br />";
       echo "Return Date: ".$vals[51]['value']."<br />";
       echo $vals[31]['value']." to ".$vals[37]['value']."<br />";
       echo "Price: $".$vals[45]['value']."<br />";
       echo "Description: ".$vals[25]['value']."<br/>";
       echo "</center>";
   }
?>

<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
