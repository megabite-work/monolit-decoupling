<?php

declare(strict_types=1);

namespace CourierMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241203142525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create delivery table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, order_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE delivery');
    }
}
