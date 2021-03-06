<?php
declare(strict_types=1);

namespace OurSociety\Test\TestCase\Controller;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use OurSociety\Model\Entity\User;
use OurSociety\Model\Table\UsersTable;
use OurSociety\Test\Fixture\UsersFixture;
use OurSociety\TestSuite\IntegrationTestCase;

/**
 * OurSociety\Controller\Citizen\PoliticiansController Test Case
 */
class PoliticiansControllerTest extends IntegrationTestCase
{
    public function testIndex(): void
    {
        $this->get(['_name' => 'politicians']);
        $this->assertResponseOk();
        $this->assertResponseContains(UsersFixture::POLITICIAN_NAME);
    }

    public function testView(): void
    {
        $this->get(['_name' => 'politician', 'politician' => UsersFixture::POLITICIAN_SLUG]);
        $this->assertResponseOk();
        $this->assertResponseContains(UsersFixture::POLITICIAN_NAME);
    }

    public function testViewClaim(): void
    {
        $this->get(['_name' => 'politician', 'politician' => 'imported-politician']);
        $this->assertResponseOk();
        $this->assertResponseContains('This profile has not been claimed.');
        $this->assertResponseContains(sprintf('Click here to see <a href="/politicians/%s">an example profile</a>', UsersFixture::POLITICIAN_SLUG));
        $this->assertResponseContains('or choose <a href="/politicians/imported-politician/claim">Claim Profile</a>');
        $this->assertResponseContains('to create your account.');
        $this->assertResponseContains('Imported Politician');
        $this->assertResponseContains('Claim Your Profile');
        $this->assertResponseContains('/politicians/imported-politician/claim');
    }

    public function testClaim(): void
    {
        /** @var UsersTable $users */
        $users = TableRegistry::get('Users');
        /** @var User $politician */
        $politician = $users->getBySlug('imported-politician');

        $this->get(['_name' => 'politician:claim', 'politician' => 'imported-politician']);
        $this->assertResponseOk();
        $this->assertResponseContains('Claim Your Profile');
        $this->assertResponseContains('Please enter your activation code');
        $this->assertResponseContains('Activation code');
        $this->assertResponseContains('Email address');
        $this->assertResponseContains('Password');
        $this->assertResponseNotContains($politician->token);
        $this->assertResponseNotContains($politician->password);

        $expectedEmail = 'real.email@example.com';
        $expectedPassword = 'claim_password';

        // error
        $this->post(['_name' => 'politician:claim', 'politician' => 'imported-politician'], [
            'token' => '12345',
            'email' => $expectedEmail,
            'password' => $expectedPassword,
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('Sorry, the code you have entered does not match our records.');

        // duplicate_email
        $this->post(['_name' => 'politician:claim', 'politician' => 'imported-politician'], [
            'token' => '123456',
            'email' => UsersFixture::POLITICIAN_EMAIL,
            'password' => $expectedPassword,
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains(UsersTable::ERROR_EMAIL_UNIQUE);

        // success
        $claimMessage = 'You have claimed the profile of Imported Politician and are now logged in. ' .
            'Please update the remaining sections and see the <a href="/docs/onboarding">Getting Started</a> guide.';
        $this->post(['_name' => 'politician:claim', 'politician' => 'imported-politician'], [
            'token' => '123456',
            'email' => $expectedEmail,
            'password' => $expectedPassword,
        ]);
        $this->assertResponseSuccess();
        $this->assertRedirect(['_name' => 'politician:profile']);
        $this->assertFlash($claimMessage);

        $politician = $users->getBySlug('imported-politician');
        self::assertNotNull($politician->verified);
        self::assertTrue((new DefaultPasswordHasher)->check($expectedPassword, $politician->password));

        $this->resumeSession();
        $this->get(['_name' => 'politician:profile']);
        $this->assertResponseOk();
        $this->assertResponseContains($claimMessage);

        $this->post(['_name' => 'users:login'], [
            'email' => $expectedEmail,
            'password' => $expectedPassword,
        ]);
        $this->assertResponseSuccess();

        $this->assertResponseCode(302);
        $this->assertRedirect(['_name' => 'politician:dashboard']);
        $this->assertFlash($claimMessage);
    }
}
