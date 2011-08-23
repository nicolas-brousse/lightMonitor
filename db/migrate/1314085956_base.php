<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class 1314085956_base extends AbstractMigration
{
    public function up(Schema $schema)
    {
      $serversTable = $schema->createTable("servers");
      $serversTable->addColumn("id", "integer", array("unsigned" => true, "autoincrement" => true, "notnull" => true));
      $serversTable->addColumn("servername", "string", array("notnull" => true));
      $serversTable->addColumn("ip", "string", array("notnull" => true));
      $serversTable->addColumn("protocol", "string", array("notnull" => true));
      $serversTable->addColumn("params", "string");
      $serversTable->addColumn("created_at", "integer", array("notnull" => true));
      $serversTable->addColumn("updated_at", "integer", array("notnull" => true));
      $serversTable->addColumn("checked_at", "integer");
      $serversTable->setPrimaryKey(array("id"));
      $serversTable->addUniqueIndex(array("username"));
      $serversTable->addUniqueIndex(array("ip"));

      $softwaresTable = $schema->createTable("softwares");
      $softwaresTable->addColumn("id", "integer", array("unsigned" => true, "autoincrement" => true, "notnull" => true));
      $softwaresTable->addColumn("server_id", "integer", array("notnull" => true));
      $softwaresTable->addColumn("label", "string", array("notnull" => true));
      $softwaresTable->addColumn("port", "integer", array("notnull" => true, "length" => 6));
      $softwaresTable->addColumn("status", "integer", array("notnull" => true, "length" => 1, "default" => '0'));
      $softwaresTable->addColumn("created_at", "integer", array("notnull" => true));
      $softwaresTable->addColumn("updated_at", "integer", array("notnull" => true));
      $softwaresTable->addColumn("checked_at", "integer");
      $softwaresTable->setPrimaryKey(array("id"));

      // TODO
      // $myForeign->addForeignKeyConstraint($myTable, array("server_id"), array("id"), array("onDelete" => "CASCADE"));
    }

    public function down(Schema $schema)
    {
      $schema->dropTable('servers');
      // TODO drop foreignKey
      $schema->dropTable('softwares');
    }
}
