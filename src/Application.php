<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles;

use Symfony\Component\Console\Application as BaseApplication;

final class Application extends BaseApplication
{
    public const VERSION = '0.0.1-DEV';

    public const APP_NAME = 'PDot';

    public function __construct()
    {
        parent::__construct(self::APP_NAME, self::VERSION);
    }
}
