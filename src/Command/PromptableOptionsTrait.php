<?php

namespace Vivait\PromptableOptions\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vivait\PromptableOptions\Transformer\BooleanTransformer;
use Vivait\PromptableOptions\Transformer\FloatTransformer;
use Vivait\PromptableOptions\Transformer\IntegerTransformer;
use Vivait\PromptableOptions\Transformer\StringTransformer;
use Vivait\PromptableOptions\Transformer\ValueTransformer;

/**
 * This trait should only be used on a Symfony command class
 *
 * @mixin Command
 */
trait PromptableOptionsTrait
{

    /**
     * @var SymfonyStyle
     */
    private $consoleIo;

    /**
     * @var InputInterface
     */
    private $consoleInput;

    /**
     * @var OutputInterface
     */
    private $consoleOutput;

    /**
     * @var array
     */
    private $consolePrompts = [];

    /**
     * @var array
     */
    private $promptConfig = [];

    /**
     * @param string $optionName
     *
     * @return string
     * @throws \Exception
     */
    protected function getConsoleOptionInput($optionName)
    {
        $value = $this->consoleInput->getOption($optionName);

        if ($value) {
            $transformedCorrectly = true;

            try {
                $newValue = $this->transformValue($value, $optionName);
            } catch (LogicException $e) {
                $transformedCorrectly = false;

                // Present an error to indicate why the option wasn't accepted
                $this->consoleIo->block($e->getMessage(), 'ERROR', 'fg=white;bg=red;', ' ', true);
            }

            if ($transformedCorrectly) {
                return $newValue;
            }
        }

        $optionIsPromptable = in_array($optionName, $this->consolePrompts);

        if ( ! $optionIsPromptable) {
            return null;
        }

        $description = $this->getQuestionText($optionName);

        if ( ! $this->consoleInput->isInteractive()) {
            throw new \Exception(
                sprintf('Cannot prompt for %s, command is running in non-interactive mode', $optionName)
            );
        }

        /** @var QuestionHelper $question */
        $value = $this->consoleIo->ask(
            $description,
            null,
            function($value) use($optionName) {
                $noValue    = ($value === '' || $value === null);
                $isRequired = $this->promptConfig[$optionName]['required'];
                
                if ($noValue && $isRequired) {
                    throw new LogicException("A value is required.");
                }
                
                $value = $this->transformValue($value, $optionName);
                
                return $value;
            }
        );
        
        // If the transformer returned a null value and the input isn't required, resort to default value
        if ( ! $this->promptConfig[$optionName]['required'] && $value === null) {
            $value = $this->promptConfig[$optionName]['default'];
        }

        return $value;
    }

    /**
     * @param string $value
     * @param string $optionName
     * 
     * @return mixed
     */
    protected function transformValue($value, $optionName)
    {
        $config = $this->promptConfig[$optionName];
        $class  = $this->getTransformers()[$config['type']];

        /**
         * @var ValueTransformer $instance
         */
        $instance = new $class;
        
        return $instance->transform($value);
    }

    /**
     * @param string $optionName
     * @param array  $configuration
     *
     * @return static
     */
    protected function addPrompt($optionName, array $configuration = [])
    {
        $this->consolePrompts[] = $optionName;

        $resolver = $this->getConfigResolver();
        $this->promptConfig[$optionName] = $resolver->resolve($configuration);

        if ( ! $this->getDefinition()->hasOption($optionName)) {
            $this->addOption(
                $optionName,
                null,
                InputOption::VALUE_OPTIONAL,
                $this->promptConfig[$optionName]['description']
            );
        }

        return $this;
    }

    /**
     * @return OptionsResolver
     */
    protected function getConfigResolver()
    {
        $resolver = new OptionsResolver();

        // Expect strings by default
        $resolver->setDefaults([
            'type'        => 'string',
            'required'    => true,
            'description' => '',
            'default'     => null
        ]);

        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('required', 'bool');
        $resolver->setAllowedTypes('description', 'string');

        $resolver->setAllowedValues('type', array_keys($this->getTransformers()));
        
        return $resolver;
    }

    /**
     * Add prompts through a key value array of option names => their configuration.
     * The configuration is optional.
     *
     * e.g.
     *
     * [
     *      'myOption' => ['type' => 'int'],
     *      'myDefaultOption',
     *      'myOtherOption' => ['description' => 'Hello!']
     * ]
     *
     * @param array $options
     *
     * @return static
     */
    protected function addPrompts(array $options = [])
    {
        $resolver = $this->getConfigResolver();

        $optionNames = [];
        foreach ($options as $key => $value) {
            if (is_string($value)) {
                $optionNames[] = $value;
                $this->promptConfig[$key] = $resolver->resolve([]);

                continue;
            }

            if (is_array($value)) {
                $optionNames[] = $key;
                $this->promptConfig[$key] = $resolver->resolve($value);

                continue;
            }

            throw new \InvalidArgumentException("Invalid value passed into `addPrompts`.");
        }

        $this->consolePrompts = array_unique(array_merge($this->consolePrompts, $optionNames));
        foreach ($optionNames as $option) {
            if ($this->getDefinition()->hasOption($option)) {
                continue;
            }

            $this->addOption(
                $option,
                null,
                InputOption::VALUE_OPTIONAL,
                $this->promptConfig[$option]['description']
            );
        }

        return $this;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function setUpPrompt(InputInterface $input, OutputInterface $output)
    {
        $this->consoleIo = new SymfonyStyle($input, $output);
        $this->consoleInput = $input;
        $this->consoleOutput = $output;
    }

    /**
     * @param $optionName
     *
     * @return string
     */
    protected function getQuestionText($optionName)
    {
        $description = $this->getDefinition()->getOption($optionName)->getDescription();

        if ($description === null || trim($description) === '') {
            $description = ucfirst($optionName);
        }

        return $description;
    }

    /**
     * @return array
     */
    private function getTransformers()
    {
        return [
            'int'     => IntegerTransformer::class,
            'integer' => IntegerTransformer::class,
            'string'  => StringTransformer::class,
            'bool'    => BooleanTransformer::class,
            'boolean' => BooleanTransformer::class,
            'float'   => FloatTransformer::class
        ];
    }
}
