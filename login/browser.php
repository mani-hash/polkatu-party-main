<?php
//uses PHPMailer's class to get information on user browser
//and operating system
//Read the wolfcast_README.md file in the class/browser_class folder for more info

require_once("class/browser_class/BrowserDetection.php");

  $browser_req = new Wolfcast\BrowserDetection();

  $req_userAgent = $browser_req->getUserAgent();
  $req_userBrowser = $browser_req->getName();
  $req_browserVer = $browser_req->getVersion();
  $req_platformName = $browser_req->getPlatform();
  $req_platform64 = $browser_req->is64bitPlatform();
  $req_mobile = $browser_req->isMobile();
  $req_robot = $browser_req->isRobot();

  $platform64_bool = (int)$req_platform64;
  $mobile_bool = (int)$req_mobile;
  $robot_bool = (int)$req_robot; 


?>
