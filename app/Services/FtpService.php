<?php
namespace App\Services;

use League\Flysystem\Filesystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;

class FtpService
{
    private static $instance = null;
    private $filesystem;

    private function __construct()
    {
        $connectionOptions = FtpConnectionOptions::fromArray([
            'host' => config('filesystems.disks.pl_ftp.host'),
            'username' => config('filesystems.disks.pl_ftp.username'),
            'password' => config('filesystems.disks.pl_ftp.password'),
            'port' => config('filesystems.disks.pl_ftp.port', 21),
            'root' => config('filesystems.disks.pl_ftp.root', ''),
            'ssl' => config('filesystems.disks.pl_ftp.ssl', false),
            'timeout' => config('filesystems.disks.pl_ftp.timeout', 30),
            // Opcjonalne opcje:
            'passive' => config('filesystems.disks.ftp_secure.passive', true),
            'transferMode' => FTP_BINARY,
            'encoding' => 'UTF-8',
        ]);

        // Tworzenie adaptera FTP z obiektem FtpConnectionOptions
        $adapter = new FtpAdapter($connectionOptions);

        $this->filesystem = new Filesystem($adapter);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new FtpService();
        }

        return self::$instance;
    }

    public function getFilesystem()
    {
        return $this->filesystem;
    }
}
