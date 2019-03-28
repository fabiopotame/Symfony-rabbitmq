<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumerCommand extends Command
{
    protected function configure()
    {
        $this->setName('consumer')
            ->setDescription('Consumer from AMQP protocol.')
            ->setHelp('Consumer from AMQP protocol.')
            ->addArgument('queue', InputArgument::REQUIRED, 'Pass the queue.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new AMQPStreamConnection('container_rabbit', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare($input->getArgument('queue'), false, false, false, false);

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };

        $channel->basic_consume($input->getArgument('queue'), '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }
}
