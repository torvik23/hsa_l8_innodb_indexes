<?php

declare(strict_types=1);

namespace App\Console;

use App\Domain\User\Service\UserGenerator;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UserGenerateCommand extends Command
{
    private const USER_AMOUNT_TO_CREATE_ARGUMENT = 'user-amount';
    private const DEFAULT_USER_AMOUNT = 1;

    /** @var UserGenerator */
    private UserGenerator $userGenerator;

    /**
     * @param UserGenerator $userGenerator
     * @param string|null $name
     */
    public function __construct(
        UserGenerator $userGenerator,
        string $name = null
    ) {
        parent::__construct($name);
        $this->userGenerator = $userGenerator;
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('user:generate');
        $this->addArgument(
            self::USER_AMOUNT_TO_CREATE_ARGUMENT,
            InputArgument::OPTIONAL,
            'User Amount To Create. Default=1',
            self::DEFAULT_USER_AMOUNT
        );
        $this->setDescription('Creates new user with random data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userAmountToCreate = (int) $input->getArgument(self::USER_AMOUNT_TO_CREATE_ARGUMENT);
        if ($userAmountToCreate < 1) {
            $userAmountToCreate = self::DEFAULT_USER_AMOUNT;
        }

        try {
            $createdUsers = $this->userGenerator->generateMultiple($userAmountToCreate);
            $output->writeln(sprintf('<info>Created user amount: %d</info>', $createdUsers));
            return self::SUCCESS;
        } catch (Exception $exception) {
            $output->writeln('<error>User creation is failed.</error>');
            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
            return self::FAILURE;
        }
    }
}
