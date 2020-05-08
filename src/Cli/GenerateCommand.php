<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Cli;

use Klitsche\FFIGen\Config;
use Klitsche\FFIGen\ConfigInterface;
use Klitsche\FFIGen\GeneratorInterface;
use Klitsche\FFIGen\ParserInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'ffigen:generate';

    private SymfonyStyle $style;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generate FFI Binding')
            ->addOption(
                'config',
                'c',
                InputOption::VALUE_REQUIRED,
                'Relative or absolute path to yaml file.',
                '.ffigen.yml'
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->style = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configOption = (string) $input->getOption('config');
        $config = Config::fromFile($configOption, getcwd());

        $generator = $this->createGenerator($config);
        $generator->generate();

        $this->style->success(sprintf('Successfully generated in %s.', $config->getOutputPath()));

        return 0;
    }

    private function createGenerator(ConfigInterface $config): GeneratorInterface
    {
        $generatorClass = $config->getGeneratorClass();
        return new $generatorClass($config, $this->createParser($config));
    }

    private function createParser(ConfigInterface $config): ParserInterface
    {
        $parserClass = $config->getParserClass();
        return new $parserClass($config);
    }
}
