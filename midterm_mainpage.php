#!/usr/bin/php
<?php
require_once("midterm_logindb.php.inc");
require_once("midterm_gamesdb.php.inc");
function print_help()
{
  echo "Login as Administrator:  ".PHP_EOL;
  echo __FILE__." adminlogin".PHP_EOL;
  echo "Login as User: ".PHP_EOL;
  echo __FILE__." userlogin".PHP_EOL;
  echo "Register as New User: ".PHP_EOL;
  echo __FILE__." register".PHP_EOL;
  echo "Use as guest: ".PHP_EOL;
  echo __FILE__." guest".PHP_EOL;
}
$cArgs = array();
if(!isset($argv[1]))
{
  exit(0);
}
$letter = $argv[1];
function admin_logon($username,$password)
{
  $login = new loginDB("midterm_logindb.ini");
  if($login->checkUserPrivilegeLevel($username,$password))
  {
    echo "Welcome, administrator. Would you like to add/update games to the list?".PHP_EOL;
    echo " update <game_name> <release_date> <status> <username> <password>".PHP_EOL;
  }
  else
  {
    echo "You do not have administrative privileges. Please login using '-u'.".PHP_EOL;
  }
  
  exit(0);
}
function user_logon($username,$password)
{
  $login = new loginDB("midterm_logindb.ini");
  if($login->validateUser($username,$password))
  {
    echo "Welcome, $username. The following commands are available to you: ".PHP_EOL;
    echo __FILE__." view games by date".PHP_EOL;
    echo __FILE__." view games by name".PHP_EOL;
    echo __FILE__." add <game_id> to wishlist <username> <password>".PHP_EOL;
    echo __FILE__." check wishlist <username> <password>".PHP_EOL;
  }
  else
  {
    echo "You are not a registered user, or have entered the incorrect password. Please register using '-r' or login as a guest.".PHP_EOL;
  }
  
  exit(0);
}
function user_register($username,$password)
{
  $login = new loginDB("midterm_logindb.ini");
  $login->addNewUser($username,$password);
  exit(0);
}
function view_games($searchtype)
{
  if ($searchtype == 'date' || $searchtype == 'name')
  {
    $games = new gamesDB("midterm_gamesdb.ini");
    $games->viewGames($searchtype);
    exit(0);
  }
  else
  {
    echo "Please enter a valid search parameter ('date' or 'name')".PHP_EOL;
    exit(0);
  }
}
function add_to_wishlist($gameid,$username,$password)
{
  $login = new loginDB("midterm_logindb.ini");
  if($login->validateUser($username,$password))
  {
    $games = new gamesDB("midterm_gamesdb.ini");
    $games->addToWishlist($gameid,$username);
  }
  else
  {
    echo "You are not a registered user, or have entered the incorrect password. Please register using '-r' or login as a guest.".PHP_EOL;
  }
  
  exit(0);
}
function check_wishlist($username,$password)
{
   $login = new loginDB("midterm_logindb.ini");
   if($login->validateUser($username,$password))
   {
     $games = new gamesDB("midterm_gamesdb.ini");
     $games->checkWishlist($username);
   }
   else
   {
    echo "You are not a registered user, or have entered the incorrect password. Please register using '-r' or login as a guest.".PHP_EOL;
   }
  
  exit(0);
}
function update_list($gamename,$releasedate,$status,$username,$password)
{
  $login = new loginDB("midterm_logindb.ini");
  if($login->checkUserPrivilegeLevel($username,$password))
  {
    $games = new gamesDB("midterm_gamesdb.ini");
    $games->updateGames($gamename,$releasedate,$status);
  }
  else
  {
    echo "You do not have administrative privileges. Please login using '-u'.".PHP_EOL;
  }
  
  exit(0);
}
switch ($letter)
{
  case 'adminlogin':
   echo "Please validate that you are an administrator: ".PHP_EOL;
   echo __FILE__." -a <username> <password>".PHP_EOL;
   break;
  case 'userlogin':
    echo "Please validate that you are a user: ".PHP_EOL;
    echo __FILE__." -u <username> <password>".PHP_EOL;
    break;
  case 'register':
    echo "Please create your username and password: ".PHP_EOL;
    echo __FILE__." -r <username> <password>".PHP_EOL;
    break;
  case 'guest':
    echo "You are a guest. These are the commands available to you: ".PHP_EOL;
    echo __FILE__." view games by date".PHP_EOL;
    echo __FILE__." view games by name".PHP_EOL;
    break;
  case '-a':
      admin_logon($argv[2],$argv[3]);
      break;
  case '-u':
      user_logon($argv[2],$argv[3]);
      break;
  case '-r':
      user_register($argv[2],$argv[3]);
      break;
  case 'view':
      view_games($argv[4]);
      break;
  case 'add':
      add_to_wishlist($argv[2],$argv[5],$argv[6]);
      break;
  case 'check':
      check_wishlist($argv[3],$argv[4]);
      break;
  case 'update':
      update_list($argv[2],$argv[3],$argv[4],$argv[5],$argv[6]);
      break;
  default:
    print_help();
    exit(0);
}
?>
