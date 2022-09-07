<?php

declare(strict_types=1);

namespace Here\Commands;

use Here\Config;
use System\Console\Command;

use function System\Console\style;

use System\Console\Style\Style;

/**
 * @property bool $init
 */
final class ServeCommand extends Command
{
    public function main()
    {
        /** @var string */
        $option =  $this->CMD;
        $option = strtolower($option);

        switch ($option) {
            case 'serve':
                /** @var string */
                $uri = $this->OPTION[0] ?? Config::get('socket.uri', '127.0.0.1:8080');
                $this->serve($uri);
                break;

            case 'socket':
                $status      = Config::get('socket.enable');
                $status      = !$status;
                $status_text = $status ? 'enable' : 'disable';
                style('socket status:')->textGreen()
                    ->push($status_text)->out();

                Config::set('socket.enable', $status);
                break;

            case 'config':
                if ($this->init) {
                    Config::load();
                    $config_file = dirname(__DIR__, 4) . '/here.config.json';
                    $config      = json_encode(Config::all(), JSON_PRETTY_PRINT);
                    if (file_put_contents($config_file, $config) !== false) {
                        style('Success create config file ' . $config_file)
                            ->textYellow()
                            ->out();
                    }
                }
                break;

            default:
                $this->help()->out();
                break;
        }
    }

    /**
     * Serve socket.
     *
     * @param string $uri
     *
     * @return void
     */
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
                style(now()->__toString())->textDim()->underline()
                    ->push($chunk)
                    ->out(false);
            });
        });
    }

    /**
     * @return Style
     */
    public function help()
    {
        return (new Style('Here CLI'))
            ->textGreen()
            ->push(' command line application')->textBlue()
            ->new_lines(2)

            ->push('command:')->new_lines()
            ->push("\t")
            ->push('serve')->textGreen()->push(' [option]')->textDim()
            ->tabs(2)
            ->push('Start socket server')
            ->new_lines()

            ->push("\t")
            ->push('socket')->textGreen()
            ->tabs(3)
            ->push('Togle enable/disable socket reporting')
            ->new_lines()

            ->push("\t")->push('help')->textGreen()
            ->tabs(3)
            ->push('Show help command information')
            ->new_lines();
    }
}
