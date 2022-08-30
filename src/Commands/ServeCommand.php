<?php

declare(strict_types=1);

namespace Here\Commands;

use Here\Config;
use System\Console\Command;

use function System\Console\style;

use System\Console\Style\Style;

final class ServeCommand extends Command
{
    public function main()
    {
        $option = strtolower($this->CMD);

        switch ($option) {
            case 'serve':
                $this->serve($this->OPTION[0] ?? Config::get('socket.uri', '127.0.0.1:8080'));
                break;

            case 'socket':
                $status      = Config::get('socket.enable');
                $status      = !$status;
                $status_text = $status ? 'enable' : 'disable';
                style('socket status:')->textGreen()
                    ->push($status_text)->out();

                Config::set('socket.enable', $status);
                break;

            default:
                style('Here CLI')->textGreen()
                    ->push(' command line application')->textBlue()
                    ->new_lines()
                    ->out();

                foreach ($this->printHelp()['help'] as $help) {
                    if ($help instanceof Style) {
                        $help->out();
                    }
                }
                break;
        }
    }

    public function serve($uri)
    {
        // header information
        style('socket server')->textGreen()->new_lines()->out();
        style('info')->textWhite()->bgBlue()->push(' ')
            ->push($uri)->new_lines()->out();
        style('listening...')->textDim()->out();

        // socket
        $socket = new \React\Socket\SocketServer($uri);

        $socket->on('connection', function (\React\Socket\ConnectionInterface $connection) {
            $connection->on('data', function ($chunk) {
                style(now())->textDim()->underline()
                    ->push($chunk)
                    ->out(false);
            });
        });
    }

    public function printHelp()
    {
        return [
            'help' => [
                style('command:'),
                style("\t")->push('serve')->textGreen()
                    ->push(' [option]')->textDim()->tabs(2)->push('Start socket server'),
                style("\t")->push('socket')->textGreen()->tabs(3)->push('Togle enable/disable socket reporting'),
                style("\t")->push('help')->textGreen()->tabs(3)->push('Show help command information'),
            ],
        ];
    }
}
