<?php

namespace Sun\Console\Commands;

use Sun\WordArt;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowCommand extends Command
{
    protected $input;

    protected $output;

    protected $name = 'show';

    protected $option = 'type';

    protected $where = 'where';

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    protected function configure()
    {
        $this->setName($this->name)
            ->setDescription('show beautiful words')
            ->addArgument(
                $this->option,
                InputArgument::OPTIONAL,
                'Get word or weather.'
            )->addArgument(
                $this->where,
                InputArgument::OPTIONAL,
                'weather value where.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {   
    	$args = $input->getArguments();

    	if (empty($args[$this->option]) && empty($args[$this->where])) {
    		$logo = config('logo');
        	$output->writeln($logo);
        	$output->writeln(<<<EOF
The <info>word-art</info> command <comment>v1.0</comment> lists all commands:

<comment>Usage:</comment>
  command [options] [arguments]

<comment>Options:</comment>
  <info>word</info>    		show word
  <info>weather</info> <error>argument</error>      show weather


EOF
			);
			return;
    	}

    	$type = $args[$this->option];
    	$where = isset($args[$this->where]) ? $args[$this->where] : '';

    	switch ($type) {
    		case 'word':
    			$wordObj = $this->getWord();
        		$words = $wordObj->getOne();
        		if (empty($words)) {
        			$output->writeln('<comment>Sorry,Get beautiful words failed! Please try again</comment>');
        			return;
        		}
        		$table = $this->getTable($output);
        		$table->setHeaders(config('table_header'))->setRows($words);
        		$table->render();
    			break;
    		case 'weather':
    			if (!$where) {
    				$output->writeln('<comment>Please input your location！</comment>');
    				return;
    			}
    			$shell = 'curl -s wttr.in/'.$where.'?lang=zh';
    			$output->writeln(shell_exec($shell));
    			break;
    		
    		default:
    			$output->writeln('<comment>Sorry, this option not support！</comment>');
    			break;
    	}
    }

    private function getWord()
    {
    	return new WordArt();
    }

    private function getTable(OutputInterface $output)
    {
    	return new Table($output);
    }
}