<?php

namespace Sun\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class App extends Application
{
	const NAME = 'WordArt';

	const VERSION = '1.0';

    /**
	 * constructor
	 */
	public function __construct()
	{
		parent::__construct(self::NAME, self::VERSION);
	}

	public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();
        return $inputDefinition;
    }

	/**
	 * get command name
	 * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'show';
    }
}