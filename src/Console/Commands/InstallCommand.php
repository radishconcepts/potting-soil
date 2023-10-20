<?php

namespace RadishConcepts\PottingSoil\Console\Commands;

use RadishConcepts\PottingSoil\Console\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class InstallCommand extends Command {

	protected InputInterface $input;
	protected OutputInterface $output;
	protected SymfonyStyle $io;

	protected function configure(): void {
		$this
			->setName( 'install' )
			->setDescription( 'Set up the project to your needs' )
			->setAliases([ 'i' ]);
	}

	protected function execute( InputInterface $input, OutputInterface $output ): int {

		$this->input  = $input;
		$this->output = $output;
		$this->io     = new SymfonyStyle( $input, $output );

		$this->initGit();

		$this->downloadWordPress();

		$this->createWordPressConfig();

		$this->createLocalConfig();

		return Command::SUCCESS;
	}

	private function initGit(): void {
		// Let the user know what we're doing.
		$this->output->writeln( 'Re-initializing GIT.' );

		// Remove the old .git directory.
		( new Process([ 'rm', '-rf', '.git' ]) )->run();

		// Initialize a new GIT repository.
		( new Process([ 'git', 'init' ]) )->run();

		// Ask the user for the repository URL.
		$repositoryURL = $this->io->ask( 'What is the GIT URL of the project? (Example: git@github.com:radishconcepts/scaffold-wordpress)' );

		// Check if an GIT URL is given.
		if ( empty( $repositoryURL ) ) {
			$this->output->writeln( '- No GIT URL given' );
			return;
		}

		// Check if the GIT URL is an correct one.
		if ( (bool) preg_match( '/git@github.com:[a-z0-9-]+\/[a-z0-9-]+.git/', $repositoryURL ) === false ) {
			$this->output->writeln( '- No valid GIT URL given' );
			return;
		}

		// Set the GIT URL.
		( new Process([ 'git', 'remote', 'add', 'origin', $repositoryURL ]) )->run();
	}

	private function downloadWordPress(): void {
		// Let the user know what we're doing.
		$this->output->writeln( 'Downloading WordPress' );

		// Run the WP-CLI core download process.
		$process = new Process([ 'wp', 'core', 'download', '--locale=nl_NL', '--path=app/www', '--quiet' ]);
		$process->run();

		// Check if the process was successful.
		if ( !$process->isSuccessful() ) {
			// If not, retrieve the error.
			$error = $process->getErrorOutput();

			// Check if the error contains the word 'present'.
			if ( str_contains( $error, 'present' ) ) {
				$this->output->writeln( 'There is already an WordPress installation in app/www. Ignoring.' );
				return;
			}

			// If not, throw an error.
			$this->output->writeln( $error );
		}
	}

	private function createWordPressConfig(): void {
		// Let the user know what we're doing.
		$this->output->writeln( 'Creating wp-config.php' );

		// Check if the wp-config.php file already exists.
		if ( file_exists( 'app/www/wp-config.php' ) ) {
			$this->output->writeln( 'wp-config.php already exists. Ignoring.' );
			return;
		}

		// Copy the example file to the public root.
		( new Process([ 'cp', App::path() . 'stubs/wp-config.php', 'app/www/wp-config.php' ]) )->run();

		// Shuffle the salts in the config file.
		( new Process([ 'wp', 'config', 'shuffle-salts', '--config-file=app/www/wp-config.php' ]) )->run();
	}

	private function createLocalConfig(): void {
		// Let the user know what we're doing.
		$this->output->writeln( 'Creating wp-config-local.php' );

		// Check if the wp-config-local.php file already exists.
		if ( file_exists( 'app/www/wp-config-local.php' ) ) {
			$this->output->writeln( 'wp-config-local.php already exists. Ignoring.' );
			return;
		}

		// Copy the example file to the public root.
		( new Process([ 'cp', App::path() . 'stubs/wp-config-local.php', 'app/www/wp-config-local.php' ]) )->run();
	}
}