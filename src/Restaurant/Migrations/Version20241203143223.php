<?php

declare(strict_types=1);

namespace RestaurantMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241203143223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create restaurant table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO restaurant (name) VALUES ("My restaurant")');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE restaurant');
    }
}
