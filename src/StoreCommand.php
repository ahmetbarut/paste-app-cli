<?php

namespace AhmetBarut\PasteCli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;

use function Termwind\render;
use function Termwind\terminal;

class StoreCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('paste:create')
            ->setDescription('Create a new paste')
            ->addOption(
                'file',
                'F',
                InputOption::VALUE_NONE,
                'if you want to create a paste from a file'
            )
            ->addArgument(
                'content',
                InputArgument::OPTIONAL,
                'Content of the paste'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('file')) {
            if (!file_exists($input->getArgument('content'))) {
                render('<div class="p-2 bg-red-500">
                    <span class="text-white">File not found!</span>
                </div>' . PHP_EOL);
                return Command::FAILURE;
            }
            $content = file_get_contents($input->getArgument('content'));
        } else {
            $content = $input->getArgument('content');
        }

        if ($content === null || strlen($content) === 0) {
            render('
                <div class="bg-red-500 p-2">
                    Content cannot be empty!
                </div>
            ');
            return Command::FAILURE;
        }

        $paste = $this->paste($content);

        $process = new Process(['pbcopy'], null, null, $paste['url']);
        $process->run();

        render('
            <div class="p-2 bg-green-500">
                Successfully created a new paste! Paste url copied to clipboard.
                or
                <a href="' . $paste['url'] . '" class="text-blue-500 underline">' . $paste['url'] . '</a>
            </div>
        ');

        return Command::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('content')) {
            $content = $this->getHelper('question')->ask(
                $input,
                $output,
                new Question('Please enter the content of the paste: ')
            );
            $input->setArgument('content', $content);
        }
    }

    public function paste($content): array
    {
        $client = new \GuzzleHttp\Client([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-App-Name' => 'paste-cli'
            ],
        ]);

        $response = $client->request('POST', 'http://127.0.0.1:8000/api/pastes', [
            'json' => [
                'content' => $content,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
