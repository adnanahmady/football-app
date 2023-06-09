<?php

namespace App\Tests\Feature\Auth\Form;

use App\Form\RegistrationFormType as Form;
use App\Tests\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class RegistrationFormTest extends WebTestCase
{
    /**
     * @test
     */
    public function it_should_contain_expected_fields(): void
    {
        $formName = 'registration_form';
        $crawler = static::createClient()->request(
            'GET',
            $this->route('registration.form')
        );

        $email = $this->getHtml($crawler, $formName, Form::EMAIL);
        $password = $this->getHtml($crawler, $formName, Form::PLAIN_PASSWORD);
        $agreeTerms = $this->getHtml($crawler, $formName, Form::AGREE_TERMS);
        $token = $this->getHtml($crawler, $formName, '_token');

        $this->assertNotEmpty($email);
        $this->assertNotEmpty($password);
        $this->assertNotEmpty($agreeTerms);
        $this->assertNotEmpty($token);
        $this->assertResponseIsSuccessful();
    }

    private function getHtml(
        Crawler $crawler,
        string $formName,
        string $field
    ): string {
        return $crawler
            ->filter("input[name=\"{$formName}[$field]\"]")
            ->outerHtml();
    }

    /**
     * @test
     */
    public function it_should_return_proper_status_code(): void
    {
        static::createClient()->request(
            'GET',
            $this->route('registration.form')
        );

        $this->assertResponseIsSuccessful();
    }
}
