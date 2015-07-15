<?php

namespace UJM\ExoBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/07/15 11:07:53
 */
class Version20150715110748 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE ujm_complementary_information (
                id INT AUTO_INCREMENT NOT NULL, 
                question_id INT DEFAULT NULL, 
                media LONGTEXT NOT NULL, 
                position INT NOT NULL, 
                INDEX IDX_F9B1D9BD1E27F6BF (question_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE ujm_complementary_information 
            ADD CONSTRAINT FK_F9B1D9BD1E27F6BF FOREIGN KEY (question_id) 
            REFERENCES ujm_question (id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE ujm_complementary_information
        ");
    }
}