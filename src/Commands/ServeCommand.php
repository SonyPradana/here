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
        $style = new Style();
        if (Config::get('socket.enable', false) === false) {
            $style->new_lines();
            $style->push(' warm ')->textWhite()->bgLightYellow()->push(' ');
            $style->push('Runing server with socket report disable')->new_lines(2);

            $style->push('Enable socket report?')->textYellow();
            $style->push('(yes)')->textGreen();
            $style->push('(no)')->out();

            if (!$this->promt()) {
                exit;
            }
            $style->new_lines(2);
        }

        /** @var string */
        $uri = $this->OPTION[0] ?? Config::get('socket.uri', '127.0.0.1:8080');

        // header information
        $style->push('socket server')->textGreen()->new_lines(2);

        $style->push(' info ')->textWhite()->bgBlue()->push(' ');
        $style->push($uri)->new_lines(2);

        $style->push('listening...')->textDim()->out();

        // socket
        $socket = new \React\Socket\SocketServer($uri);

        $socket->on('connection', function (\React\Socket\ConnectionInterface $connection) {
            $connection->on('data', function ($chunk) {
                style(now()->__toString())->textDim()->underline()
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
    }

    private function promt(): bool
    {
        $input = fgets(STDIN);

        if ($input === false) {
            throw new \Exception('Cant read input');
        }
        $asal = trim($input);
        if ($asal === 'yes' || $asal === 'y') {
            Config::set('socket.enable', true);

            return true;
        }

        return false;
    }
}
