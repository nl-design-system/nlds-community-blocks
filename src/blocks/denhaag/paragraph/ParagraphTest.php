<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../functions/phptest-helpers.php';
require_once('Paragraph.php');

use PHPUnit\Framework\TestCase;

final class ParagraphTest extends TestCase
{
    public function testParagraph(): void
    {
        $output = render_paragraph(array(), '<p>Hello, world!</p>');

        $this->assertSame('<p>Hello, world!</p>', $output);
    }

    public function testLinks(): void
    {
        $output = render_paragraph(array(), '<p><a href="https://example.com/">Hello, world!</a></p>');

        $this->assertSame('<p><a href="https://example.com/" class="denhaag-link"><span class="denhaag-link__label">Hello, world!</span></a></p>', $output);
    }
}
