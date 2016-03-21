<?php
class gamesDB
{
   private $db;
   private $ini;
   public function __construct($iniFile)
   {
	$this->ini = parse_ini_file($iniFile,true);
	$host = $this->ini['gamesDB']['host'];
	$user = $this->ini['gamesDB']['user'];
	$password = $this->ini['gamesDB']['password'];
	$database = $this->ini['gamesDB']['database'];
	$this->db = new mysqli($host,$user,$password,$database);
	if ($this->db->connect_errno > 0)
	{
		echo __FILE__.":".__LINE__.": failed to connect to db, re: ".$this->db->connect_error.PHP_EOL;
		exit(0);
	}
   }
   public function __destruct()
   {
	$this->db->close();
   }
   public function viewGames($searchtype)
   {
	$searchtype = $this->db->real_escape_string($searchtype);
	if($searchtype == 'date')
	{
		$query = "select * from games order by releasedate;";
	}
	else
	{
		$query = "select * from games order by gamename;";
	}
	$results = $this->db->query($query);
	while($row = $results->fetch_assoc())
	{
		echo "ID: ".$row["gameId"]." | Name: ".$row["gamename"]." | Release Date: ".$row["releasedate"]." | Status: ".$row["status"].PHP_EOL; //iterate over array
	}	
   }
   public function addToWishlist($gameid,$username)
   {
	$username = $this->db->real_escape_string($username);
	$val = "select gameId from `".$username."`;";
	$test = mysqli_query($this->db, $val);
	if(empty($test))
	{
		$query = "create table `".$username."` (gameId INT(11) primary key);";
		$results = $this->db->query($query);
	}
	$query = "insert into `".$username."` (gameId) values('$gameid');";
	$results = $this->db->query($query);
	echo "Game added to wishlist.".PHP_EOL;
	if ($this->db->errno > 0)
        {
		echo "DB error: ".$this->db->error.PHP_EOL;
		return;
        }
   }
   public function checkWishlist($username)
   {
	$username = $this->db->real_escape_string($username);
	$query1 = "select * from `".$username."`;";
	$query2 = "select * from games;";
	$results2 = $this->db->query($query2);
	while($row2 = $results2->fetch_assoc())
	{
	    $results1 = $this->db->query($query1);
	    while($row1 = $results1->fetch_assoc())
	      {
		if($row1["gameId"] == $row2["gameId"])
		{
		    echo "ID: ".$row2["gameId"]." | Name: ".$row2["gamename"]." | Release Date: ".$row2["releasedate"]." | Status: ".$row2["status"].PHP_EOL; //iterate over array
		}
	      }
	}
	if ($this->db->errno > 0)
        {
		echo "DB error: ".$this->db->error.PHP_EOL;
		return;
        }
   }
   public function updateGames($gamename,$releasedate,$status)
   {
	$gamename = $this->db->real_escape_string($gamename);
	$status = $this->db->real_escape_string($status);
	$query = "select gamename from games where gamename = '$gamename';";
        $results = $this->db->query($query);
	$row = $results->fetch_assoc();
	if ($row['gamename'] === $gamename)
        {
	    echo "This game already exists. Updating with new date/status.".PHP_EOL;
	    $query2 = "update games set releasedate = '$releasedate' where gamename ='$gamename';";
	    $results2 = $this->db->query($query2);
	    $query3 = "update games set status = '$status' where gamename ='$gamename';";
	    $results3 = $this->db->query($query3);
	}
        else
        {
	    echo "Adding new game to list.".PHP_EOL;
	    $query2 = "insert into games (gamename,releasedate,status) values('$gamename','$releasedate','$status');";
	    $results2 = $this->db->query($query2);
	}
	if ($this->db->errno > 0)
        {
		echo "DB error: ".$this->db->error.PHP_EOL;
		return;
        }
   }
}
?>