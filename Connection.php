<?php

class Connection {

    private $host = 'srv792.hstgr.io';
    private $dbname = 'u687282614_db_fiquebemseg';
    private $user = 'u687282614_adminSeguro';
    private $pass = 'NKeXEYPfHn5gZsN';

    public function conectar() {
        try {

            $conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                "$this->user",
                "$this->pass",
                array(PDO::ATTR_PERSISTENT => true)
            );

            // Configure PDO to throw exceptions
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;

        } catch (PDOException $e) {
            // Log the error message and display a generic error message to the user
            error_log($e->getMessage());
            die('Erro ao conectar ao banco de dados.');
        }
    }
}

/*
class Connection {

	private $host = 'srv792.hstgr.io';
	private $dbname = 'u687282614_db_fiquebemseg';
	private $user = 'u687282614_adminSeguro';
	private $pass = 'NKeXEYPfHn5gZsN';


	public function conectar() {
		try {

			$conn = new PDO(
				"mysql:host=$this->host;dbname=$this->dbname",
				"$this->user",
				"$this->pass"
			);
			return $conn;

		} catch (PDOException $e) {
			echo '<p>'.$e->getMessage().'</p>';
		}
	}
}*/

?>