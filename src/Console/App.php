<?php

namespace RadishConcepts\PottingSoil\Console;

use Exception;
use Symfony\Component\Console\Application;
use RadishConcepts\PottingSoil\Helpers\StringHelpers;
use RadishConcepts\PottingSoil\Console\Commands\InstallCommand;

class App extends Application {

	protected static self $instance;

	protected string $name = 'Potting Soil';
	protected string $version = '1.0.0';

	protected string $logo = <<<LOGO
  _____   ____ _______ _______ _____ _   _  _____    _____  ____ _____ _      
 |  __ \ / __ \__   __|__   __|_   _| \ | |/ ____|  / ____|/ __ \_   _| |     
 | |__) | |  | | | |     | |    | | |  \| | |  __  | (___ | |  | || | | |     
 |  ___/| |  | | | |     | |    | | | . ` | | |_ |  \___ \| |  | || | | |     
 | |    | |__| | | |     | |   _| |_| |\  | |__| |  ____) | |__| || |_| |____ 
 |_|     \____/  |_|     |_|  |_____|_| \_|\_____| |_____/ \____/_____|______|
                                                                              
                                                                              

LOGO;

	/**
	 * Initialize the CLI application.
	 *
	 * @throws Exception
	 */
	public function __construct() {
		parent::__construct();

		$this->setName( $this->name );
		$this->setVersion( $this->version );

		$this->add( new InstallCommand() );

		$this->run();
	}

	/**
	 * Add extra help to the main command.
	 *
	 * @return string
	 */
	public function getHelp(): string {
		$extra_help  = "More information of this tool can be found on:\n";
		$extra_help .= "https://github.com/radishconcepts/potting-soil\n\n";
		$extra_help .= "When you encounter problems you can add an issue in the Github repo, see here:\n";
		$extra_help .= "https://github.com/radishconcepts/potting-soil/issues\n\n";

		return $this->logo . $extra_help . parent::getHelp();
	}

	/**
	 * Get the path to the root of this package. Optionally append a path to it.
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function path( $path = null ): string {
		return StringHelpers::trailingslashit( dirname( __DIR__, 2 ) ) . $path;
	}

	/**
	 * Init the CLI application. This will be called from the bin/potting-soil file.
	 *
	 * @return void
	 */
	public static function init(): void {
		self::$instance = new static();
	}
}
