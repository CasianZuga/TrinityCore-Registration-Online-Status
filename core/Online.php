<?php 

require "Database.php";
$Database = new Database("localhost", "root", "yourdatabasepassword", "yourcharactersdatabase");
class Online
{

	private $horde = array();
	public $horde_players = 0;
	public $horde_width = "50%";

	private $alliance = array();
	public $alliance_players = 0;
	public $alliance_width = "50%";

	public $total_players = 0;

	public function __construct()
	{
		$this->horde 	= array(2,5,6,8,10);
		$this->alliance = array(1,3,4,7,11);
		
		$this->total 	= 0;
	}

	public function getUsersOnline()
	{
		global $Database;
		$result = $Database->select("yourcharactersdatabase", null, null, "online=1");

		if ( $result->num_rows > 1 )
		{
			while ($row = $result->fetch_assoc())
			{
				foreach ($this->horde as $horde)
				{
					if ($horde == $row['race'] )
						$this->horde_players += 1;
				}

				foreach ($this->alliance as $alliance)
				{
					if ($alliance == $row['race'])
						$this->alliance_players += 1;
				}
			}

			$this->total_players = $this->alliance_players + $this->horde_players;


			$this->horde_width 	= ($this->horde_players / $this->total_players) * 100;
			$this->alliance_width 	= ($this->alliance_players / $this->total_players) * 100;
		}

		return array(
				"horde" => $this->horde_players, 
				"alliance" => $this->alliance_players, 
				"total" => $this->total_players);	
	}

}