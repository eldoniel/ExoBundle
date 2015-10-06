<?php

namespace UJM\ExoBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/10/01 09:44:10
 */
class Version20151001094402 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE ujm_functional_instruction (
                id INT AUTO_INCREMENT NOT NULL, 
                question_id INT DEFAULT NULL, 
                media LONGTEXT NOT NULL, 
                position INT NOT NULL, 
                INDEX IDX_DC1EEBA31E27F6BF (question_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE ujm_functional_instruction 
            ADD CONSTRAINT FK_DC1EEBA31E27F6BF FOREIGN KEY (question_id) 
            REFERENCES ujm_question (id)
        ");
        $this->addSql("
            ALTER TABLE ujm_label 
            DROP feedback
        ");
        $this->addSql("
            ALTER TABLE ujm_coords 
            DROP feedback
        ");
        $this->addSql("
            ALTER TABLE ujm_word_response 
            DROP feedback
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE ujm_functional_instruction
        ");
        $this->addSql("
            ALTER TABLE ujm_coords 
            ADD feedback LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci
        ");
        $this->addSql("
            ALTER TABLE ujm_label 
            ADD feedback LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci
        ");
        $this->addSql("
            ALTER TABLE ujm_word_response 
            ADD feedback LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci
        ");
    }
}