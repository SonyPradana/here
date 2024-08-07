<?php

declare(strict_types=1);

namespace Here\Commands;

use Here\Config;
use System\Console\Command;
use System\Console\Style\Style;
use System\Console\Traits\PrintHelpTrait;

use function System\Console\info;
use function System\Console\style;
use function System\Console\warn;

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
        $style = new Style();
        if (Config::get('socket.enable', false) === false) {
            $style->tap(
                warn('Runing server with socket report disable')
            );

            $style->push('Enable socket report?')->textYellow();
            $style->push(' (yes) ');
            $style->out(false);

            if (!$this->promt()) {
                exit;
            }
            $style->newLines()->flush();
        }

        /** @var string */
        $uri = $this->OPTION[0] ?? Config::get('socket.uri', '127.0.0.1:8080');

        // header information
        $style->push('socket server')->textGreen()->newLines();
        $style->tap(info($uri));
        $style->push('listening...')->textDim()->out();

        // socket
        $socket = new \React\Socket\SocketServer($uri);

        $socket->on('connection', function (\React\Socket\ConnectionInterface $connection) {
            $connection->on('data', function ($chunk) {
                style((new \DateTime())->format('Y-m-d H:i:s'))->textDim()->underline()
                    ->push($chunk)
                    ->out();
            });
        });
    }

    /**
     * @return Style
     */
    public function help()
    {
        $this->command_describes = [
            'serve'     => 'Start socket server, default - ' . Config::castString('socket.uri', '127.0.0.1:8080'),
            'config'    => 'Set config to config file',
            'help'      => 'Show help command information',
        ];

        $this->option_describes = [
            '--init'    => 'Config, create new Here configuration in root application',
            '--line'    => 'Config, set default printer up/down line count',
            '--var-end' => 'Config, set default printer up/down line count',
            '--var-max' => 'Config, set default printer maximum line to be print',
            '--socket'  => 'Config, enable/disable socket reporting',
            '--uri'     => 'Config, set default socket uri',
        ];

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
            ->newLines(2);

        $print->push('command:')->newLines();
        $print = $this->printCommands($print)->newLines();

        $print->push('option:')->newLines();
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
            $config_file = dirname(__DIR__, 5) . '/here.config.json';
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
            Config::set('socket.uri', $uri);
        }

        // set print.line
        $line = $this->option('line', 2);
        if ($line !== true) {
            Config::set('print.line', (int) $line);
        }

        // set print.line
        $var_end = $this->option('varend', false);
        Config::set('print.var.end', $var_end);

        // set print.var.max
        $max_line = $this->option('varmax', false);
        if ($line !== true) {
            Config::set('print.var.max', (int) $max_line);
        }
    }

    private function promt(): bool
    {
        $input = fgets(STDIN);

        if ($input === false) {
            throw new \Exception('Cant read input');
        }
        $asal = trim($input);
        if ($asal === 'no' || $asal === 'n') {
            return false;
        }

        Config::set('socket.enable', true);

        return true;
    }
}
