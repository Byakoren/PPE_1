<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * A console command to display information about the current installation.
 *
 * @author Roland Franssen <franssen.roland@gmail.com>
 * @author Joppe De Cuyper <hello@joppe.dev>
 *
 * @final
 */
#[AsCommand(name: 'about', description: 'Display information about the current project')]
class AboutCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp(<<<'EOT'
The <info>%command.name%</info> command displays information about the current Symfony project.

The <info>PHP</info> section displays important configuration that could affect your application. The values might
be different between web and CLI.
EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var KernelInterface $kernel */
        $kernel = $this->getApplication()->getKernel();

        if (method_exists($kernel, 'getBuildDir')) {
            $buildDir = $kernel->getBuildDir();
        } else {
            $buildDir = $kernel->getCacheDir();
        }

        $xdebugMode = getenv('XDEBUG_MODE') ?: \ini_get('xdebug.mode');

        $rows = [
            ['<info>Symfony</>'],
            new TableSeparator(),
            ['Version', Kernel::VERSION],
            ['Long-Term Support', 4 === Kernel::MINOR_VERSION ? 'Yes' : 'No'],
            ['End of maintenance', Kernel::END_OF_MAINTENANCE.(self::isExpired(Kernel::END_OF_MAINTENANCE) ? ' <error>Expired</>' : ' (<comment>'.self::daysBeforeExpiration(Kernel::END_OF_MAINTENANCE).'</>)')],
            ['End of life', Kernel::END_OF_LIFE.(self::isExpired(Kernel::END_OF_LIFE) ? ' <error>Expired</>' : ' (<comment>'.self::daysBeforeExpiration(Kernel::END_OF_LIFE).'</>)')],
            new TableSeparator(),
            ['<info>Kernel</>'],
            new TableSeparator(),
            ['Type', $kernel::class],
            ['Environment', $kernel->getEnvironment()],
            ['Debug', $kernel->isDebug() ? 'true' : 'false'],
            ['Charset', $kernel->getCharset()],
            ['Cache directory', self::formatPath($kernel->getCacheDir(), $kernel->getProjectDir()).' (<comment>'.self::formatFileSize($kernel->getCacheDir()).'</>)'],
            ['Build directory', self::formatPath($buildDir, $kernel->getProjectDir()).' (<comment>'.self::formatFileSize($buildDir).'</>)'],
            ['Log directory', self::formatPath($kernel->getLogDir(), $kernel->getProjectDir()).' (<comment>'.self::formatFileSize($kernel->getLogDir()).'</>)'],
            new TableSeparator(),
            ['<info>PHP</>'],
            new TableSeparator(),
            ['Version', \PHP_VERSION],
            ['Architecture', (\PHP_INT_SIZE * 8).' bits'],
            ['Intl locale', class_exists(\Locale::class, false) && \Locale::getDefault() ? \Locale::getDefault() : 'n/a'],
            ['Timezone', date_default_timezone_get().' (<comment>'.(new \DateTimeImmutable())->format(\DateTimeInterface::W3C).'</>)'],
            ['OPcache', \extension_loaded('Zend OPcache') ? (filter_var(\ini_get('opcache.enable'), FILTER_VALIDATE_BOOLEAN) ? 'Enabled' : 'Not enabled') : 'Not installed'],
            ['APCu', \extension_loaded('apcu') ? (filter_var(\ini_get('apc.enabled'), FILTER_VALIDATE_BOOLEAN) ? 'Enabled' : 'Not enabled') : 'Not installed'],
            ['Xdebug', \extension_loaded('xdebug') ? ($xdebugMode && 'off' !== $xdebugMode ? 'Enabled (' . $xdebugMode . ')' : 'Not enabled') : 'Not installed'],
        ];

        $io->table([], $rows);

        return 0;
    }

    private static function formatPath(string $path, string $baseDir): string
    {
        return preg_replace('~^'.preg_quote($baseDir, '~').'~', '.', $path);
    }

    private static function formatFileSize(string $path): string
    {
        if (is_file($path)) {
            $size = filesize($path) ?: 0;
        } else {
            if (!is_dir($path)) {
                return 'n/a';
            }

            $size = 0;
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS | \RecursiveDirectoryIterator::FOLLOW_SYMLINKS)) as $file) {
                if ($file->isReadable()) {
                    $size += $file->getSize();
                }
            }
        }

        return Helper::formatMemory($size);
    }

    private static function isExpired(string $date): bool
    {
        $date = \DateTimeImmutable::createFromFormat('d/m/Y', '01/'.$date);

        return false !== $date && new \DateTimeImmutable() > $date->modify('last day of this month 23:59:59');
    }

    private static function daysBeforeExpiration(string $date): string
    {
        $date = \DateTimeImmutable::createFromFormat('d/m/Y', '01/'.$date);

        return (new \DateTimeImmutable())->diff($date->modify('last day of this month 23:59:59'))->format('in %R%a days');
    }
}
