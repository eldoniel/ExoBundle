<?php

namespace UJM\ExoBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/07/13 03:46:21
 */
class Version20150713154619 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE ujm_content (
                id INT AUTO_INCREMENT NOT NULL, 
                question_id INT DEFAULT NULL, 
                media LONGTEXT NOT NULL, 
                position INT NOT NULL, 
                INDEX IDX_60B4B3AA1E27F6BF (question_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE ujm_content 
            ADD CONSTRAINT FK_60B4B3AA1E27F6BF FOREIGN KEY (question_id) 
            REFERENCES ujm_question (id)
        ");
        $this->addSql("
            ALTER TABLE ujm_instruction 
            DROP FOREIGN KEY FK_ABEF1D9581C06096
        ");
        $this->addSql("
            DROP INDEX IDX_ABEF1D9581C06096 ON ujm_instruction
        ");
        $this->addSql("
            ALTER TABLE ujm_instruction CHANGE activity_id question_id INT DEFAULT NULL
        ");
        $this->addSql("
            ALTER TABLE ujm_instruction 
            ADD CONSTRAINT FK_ABEF1D951E27F6BF FOREIGN KEY (question_id) 
            REFERENCES ujm_question (id)
        ");
        $this->addSql("
            CREATE INDEX IDX_ABEF1D951E27F6BF ON ujm_instruction (question_id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE ujm_content
        ");
        $this->addSql("
            ALTER TABLE ujm_instruction 
            DROP FOREIGN KEY FK_ABEF1D951E27F6BF
        ");
        $this->addSql("
            DROP INDEX IDX_ABEF1D951E27F6BF ON ujm_instruction
        ");
        $this->addSql("
            ALTER TABLE ujm_instruction CHANGE question_id activity_id INT DEFAULT NULL
        ");
        $this->addSql("
            ALTER TABLE ujm_instruction 
            ADD CONSTRAINT FK_ABEF1D9581C06096 FOREIGN KEY (activity_id) 
            REFERENCES ujm_question (id)
        ");
        $this->addSql("
            CREATE INDEX IDX_ABEF1D9581C06096 ON ujm_instruction (activity_id)
        ");
    }
}