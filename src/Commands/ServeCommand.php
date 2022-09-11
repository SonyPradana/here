<?php

declare(strict_types=1);

namespace Here\Commands;

use Here\Config;
use System\Console\Command;

use function System\Console\style;

use System\Console\Style\Style;
use System\Console\Traits\PrintHelpTrait;

final class ServeCommand extends Command
{
    use PrintHelpTrait;

    public function main()
    {
        Config::load();
        /** @var string */
        $option =  $this->CMD;
        $option = strtolower($option);

        switch ($option) {
            case 'serve':
                $this->serve();
                break;

            case 'config':
                $this->config();
                break;

            default:
                $this->help()->out();
                break;
        }
    }

    /**
     * Serve socket.
     *
     * @return void
     */
    public function serve()
    {
        /** @var string */
        $uri = $this->OPTION[0] ?? Config::get('socket.uri', '127.0.0.1:8080');
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
        $this->command_describes = [
            'serve'     => 'Start socket server, default - ' . Config::get('socket.uri', '127.0.0.1:8080'),
            'config'    => 'Set config to config file',
            'help'      => 'Show help command information',
        ];

        $this->option_describes = [
            '--init'    => 'Config, create new Here configuration in root application',
            '--line'    => 'Config, set default printer up/down line count',
            '--var-end' => 'Config, set default printer up/down line count',
            '--socket'  => 'Config, enable/disable socket reporting',
            '--uri'     => 'Config, set default socket uri',
        ];

        // @phpstan-ignore-next-line
        $this->command_relation = [
            'serve'     => ['[uri:port]'],
            'config'    => ['[option]'],
        ];

        $this->print_help = [
            'margin-left'         => 4,
            'column-1-min-lenght' => 8,
        ];

        $print = new Style('Here CLI');

        $print
            ->textGreen()
            ->push(' command line application')->textBlue()
            ->new_lines(2);

        $print->push('command:')->new_lines();
        $print = $this->printCommands($print)->new_lines();

        $print->push('option:')->new_lines();
        $print = $this->printOptions($print);

        return $print;
    }

    /**
     * Config.
     *
     * @return void
     */
    public function config()
    {
        // init config
        if ($this->option('init')) {
            $config_file = dirname(__DIR__, 4) . '/here.config.json';
            $config      = json_encode(Config::all(), JSON_PRETTY_PRINT);
            if (file_put_contents($config_file, $config) !== false) {
                style('Success create config file ' . $config_file)
                    ->textYellow()
                    ->out();
            }
        }

        // set socket.uri
        $socket = $this->option('socket');
        if ($socket !== null) {
            if (is_string($socket)) {
                $socket = strtolower($socket) === 'true' ? true : false;
            }
            Config::set('socket.enable', $socket);
        }

        // set socket.enable
        $uri = $this->option('uri', '127.0.0.1:8080');
        if ($uri !== true) {
            // @phpstan-ignore-next-line
            Config::set('socket.uri', $uri);
        }

        // set print.line
        // @phpstan-ignore-next-line
        $line = $this->option('line', 2);
        if ($line !== true) {
            Config::set('print.line', (int) $line);
        }

        // set print.line
        // @phpstan-ignore-next-line
        $var_end = $this->option('varend', false);
        // @phpstan-ignore-next-line
        Config::set('print.var.end', $var_end);
    }
}
