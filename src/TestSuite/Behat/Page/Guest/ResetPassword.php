<?php
declare(strict_types=1);

namespace OurSociety\TestSuite\Behat\Page\Guest;

use OurSociety\TestSuite\Behat\Page\Page;

class ResetPassword extends Page
{
    protected function getPath(): string
    {
        return '/reset-password';
    }
}
