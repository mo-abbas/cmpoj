<?php

// Custom subclass
class InsertOperationWithoutFkChecks extends PHPUnit_Extensions_Database_Operation_Insert
{
    public function execute(
        PHPUnit_Extensions_Database_DB_IDatabaseConnection $connection,
        PHPUnit_Extensions_Database_DataSet_IDataSet $dataSet
    ) {
        $connection->getConnection()->exec("SET foreign_key_checks = 0");
        parent::execute($connection, $dataSet);
        $connection->getConnection()->exec("SET foreign_key_checks = 1");
    }
}

?>