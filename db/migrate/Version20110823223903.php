<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20110823223903 extends AbstractMigration
{
  public function up(Schema $schema)
  {
    $usersTable = $schema->createTable("users");
    $usersTable->addColumn("id", "integer", array("unsigned" => true, "autoincrement" => true, "notnull" => true));
    $usersTable->addColumn("username", "string", array("notnull" => true, "length" => 255));
    $usersTable->addColumn("passwd", "string", array("notnull" => true));
    $usersTable->addColumn("passwd_salt", "string", array("notnull" => true));
    $usersTable->addColumn("created_at", "integer", array("notnull" => true));
    $usersTable->addColumn("updated_at", "integer", array("notnull" => true));
    $usersTable->addColumn("logged_at", "integer", array("notnull" => false));
    $usersTable->setPrimaryKey(array("id"));
    $usersTable->addUniqueIndex(array("username"));
  }

  public function down(Schema $schema)
  {
    $schema->dropTable('users');
  }
}