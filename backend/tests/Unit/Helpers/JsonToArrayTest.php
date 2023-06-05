<?php

namespace App\Tests\Unit\Helpers;

use App\Tests\WebTestCase;

class JsonToArrayTest extends WebTestCase
{
    /**
     * @test
     */
    public function it_can_correct_infinite_valued_fields_of_string_json(): void
    {
        $raw = '{"id":1,"content":"some content","amount":' . INF . ',"cost":' . INF . '}';

        $data = jsonToArray($raw, autoCorrect: true);

        $this->assertIsArray($data);
        $this->assertIsInfinite($data['amount']);
        $this->assertIsInfinite($data['cost']);
    }

    /**
     * @test
     */
    public function it_can_correct_json_strings_containing_multiple_not_a_number_value(): void
    {
        $raw = '{"id":1,"content":"some content","amount":' . NAN . ',"cost":' . NAN . '}';

        $data = jsonToArray($raw, autoCorrect: true);

        $this->assertIsArray($data);
        $this->assertIsNan($data['amount']);
        $this->assertIsNan($data['cost']);
    }

    /**
     * @test
     */
    public function it_can_correct_json_strings_containing_not_a_number_value(): void
    {
        $raw = '{"id":1,"content":"some content","amount":' . NAN . '}';

        $data = jsonToArray($raw, autoCorrect: true);

        $this->assertIsArray($data);
        $this->assertIsNan($data['amount']);
    }

    /**
     * @test
     */
    public function it_can_decode_json_string(): void
    {
        $raw = json_encode($payload = [
            'id' => 1,
            'content' => 'some content',
        ]);

        $data = jsonToArray($raw);

        $this->assertIsArray($data);
        $this->assertSame($payload, $data);
    }
}
