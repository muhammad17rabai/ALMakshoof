<?php
// DB.class.php
class DB {
    protected $db_host = 'localhost';
    protected $db_user = 'u101161004_root';
    protected $db_pass = '12345678@Almakshoof';
    protected $db_name = 'u101161004_almakshoofs';
    /*
    protected $db_host;
    protected $db_user;
    protected $db_pass;
    protected $db_name;
    public function __construct() {
        $config = include(__DIR__ . '/config.php');

        $this->db_host = $config['DB_HOST'];
        $this->db_user = $config['DB_USER'];
        $this->db_pass = $config['DB_PASS'];
        $this->db_name = $config['DB_NAME'];
    }
    */
	public function connect() {
	
		$connect_db = new mysqli( $this->db_host, $this->db_user, $this->db_pass, $this->db_name );
		
		if ( mysqli_connect_error() ) {
            echo "error ".mysqli_connect_error();
			exit();
		}
		return $connect_db;
	}
}
class role extends DB
    {
        function r($i)
        {
            $sql = $this->connect()->query("SELECT role FROM users WHERE id = '{$_SESSION['id']}'")->fetch_assoc();
            if ($sql['role'] == 'admin') {
                $r = 2;
            }elseif($sql['role'] == 'member'){
                $r = 1;
            }
            $data = array("role"=>$r);
            return $data[$i];
        }
    }
