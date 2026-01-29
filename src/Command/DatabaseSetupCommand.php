<?php

namespace App\Command;

use PDO;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(name: 'app:setup-db')]
class DatabaseSetupCommand extends Command
{
    public function __construct(
        private PDO $db,
        #[Autowire('%sql_dir%')]
        private string $sqlDir,
    ) {
        parent::__construct();
    }

    public function __invoke(OutputInterface $out): int
    {
        $rawQuery = file_get_contents($this->sqlDir . '/dump.sql');
        $this->db->exec($rawQuery);
        $out->writeln('<info>Successfully imported database schema and fixtures</info>');
        return Command::SUCCESS;
    }
}
