<?php
//use PHPUnit\Framework\PHPUnit_Extensions_Database_TestCase;
require_once("PHPUnitDatabase.php");

class GuestbookTest extends PHPUnit_Extensions_Database_TestCase
{
    static private $pdo = null;

    private $conn = null;

	/**
	 * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	protected function getConnection()
	{
		if ($this->conn === null) 
		{
            if (self::$pdo == null) 
            {
          		self::$pdo = new PDO('mysql:dbname=cmpoj;host=localhost', 'root', '');
        	}
           	$this->conn = $this->createDefaultDBConnection(self::$pdo, 'cmpoj');
        }
        return $this->conn;
	}
	
    protected function getSetUpOperation()
    {
        // Override
        return new PHPUnit_Extensions_Database_Operation_Composite([
            PHPUnit_Extensions_Database_Operation_Factory::TRUNCATE(),
            new InsertOperationWithoutFkChecks(),
        ]);
    }
	
	/**
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet()
	{
		// using xml
		
		return $this->createMySQLXMLDataSet('seed.xml');
	}

	public function testAddEntry()
	{
        $queryTable = $this->getConnection()->createQueryTable('account', 'SELECT * FROM 
        	account where id = 23');
		
		$res
		//echo $queryTable;
		print_r($queryTable->getRow(0));
	}


	/*protected function getTearDownOperation() 
	{
   		return PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL();
	}*/

}
?>