<?php

declare(strict_types=1);

namespace Kreait\Firebase\Tests\Unit\Messaging;

use Kreait\Firebase\Exception\InvalidArgumentException;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Tests\UnitTestCase;

/**
 * @internal
 */
class NotificationTest extends UnitTestCase
{
    public function testCreateWithEmptyStrings()
    {
        $notification = Notification::create('', '', '');
        $this->assertSame('', $notification->title());
        $this->assertSame('', $notification->body());
        $this->assertSame('', $notification->imageUrl());
        $this->assertEquals(['title' => '', 'body' => '', 'image' => ''], $notification->jsonSerialize());
    }

    public function testCreateWithValidFields()
    {
        $notification = Notification::create('title', 'body')
            ->withTitle($title = 'My Title')
            ->withBody($body = 'My Body')
            ->withImageUrl($imageUrl = 'https://domain.tld/image.ext');

        $this->assertSame($title, $notification->title());
        $this->assertSame($body, $notification->body());
        $this->assertSame($imageUrl, $notification->imageUrl());
    }

    public function testCreateFromValidArray()
    {
        $notification = Notification::fromArray($array = [
            'title' => $title = 'My Title',
            'body' => $body = 'My Body',
            'image' => $imageUrl = 'https://domain.tld/image.ext',
        ]);

        $this->assertSame($title, $notification->title());
        $this->assertSame($body, $notification->body());
        $this->assertSame($imageUrl, $notification->imageUrl());
        $this->assertEquals($array, $notification->jsonSerialize());
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testCreateWithInvalidData(array $data)
    {
        $this->expectException(InvalidArgumentException::class);
        Notification::fromArray($data);
    }

    public function invalidDataProvider(): array
    {
        return [
            'empty_title_and_body' => [['title' => null, 'body' => null]],
            'non_string_title' => [['title' => 1]],
            'non_string_body' => [['body' => 1]],
            'non_string_image_url' => [['image' => 1]],
        ];
    }
}
