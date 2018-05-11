<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180511215744 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_3AF34668989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobs (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, how_to_apply LONGTEXT NOT NULL, token VARCHAR(255) NOT NULL, public TINYINT(1) NOT NULL, activated TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A8936DC512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE affiliates (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_affiliates (affiliate_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_B1022A879F12C49A (affiliate_id), INDEX IDX_B1022A8712469DE2 (category_id), PRIMARY KEY(affiliate_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories_affiliates ADD CONSTRAINT FK_B1022A879F12C49A FOREIGN KEY (affiliate_id) REFERENCES affiliates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_affiliates ADD CONSTRAINT FK_B1022A8712469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC512469DE2');
        $this->addSql('ALTER TABLE categories_affiliates DROP FOREIGN KEY FK_B1022A8712469DE2');
        $this->addSql('ALTER TABLE categories_affiliates DROP FOREIGN KEY FK_B1022A879F12C49A');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE affiliates');
        $this->addSql('DROP TABLE categories_affiliates');
    }
}
