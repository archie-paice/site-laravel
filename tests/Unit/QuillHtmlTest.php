<?php

use App\Support\QuillHtml;

test('a bulleted Quill list becomes a real ul', function () {
    // This is what Quill actually emits for a bullet list: an <ol> whose type is
    // carried only by data-list.
    $html = '<ol><li data-list="bullet">One</li><li data-list="bullet">Two</li></ol>';

    expect(QuillHtml::normalizeLists($html))
        ->toBe('<ul><li>One</li><li>Two</li></ul>');
});

test('a numbered Quill list stays an ol and drops the attribute', function () {
    $html = '<ol><li data-list="ordered">One</li><li data-list="ordered">Two</li></ol>';

    expect(QuillHtml::normalizeLists($html))
        ->toBe('<ol><li>One</li><li>Two</li></ol>');
});

test('a mixed list splits into separate ul and ol blocks in order', function () {
    $html = '<ol>'
        .'<li data-list="bullet">Bullet one</li>'
        .'<li data-list="bullet">Bullet two</li>'
        .'<li data-list="ordered">Number one</li>'
        .'<li data-list="bullet">Bullet three</li>'
        .'</ol>';

    expect(QuillHtml::normalizeLists($html))->toBe(
        '<ul><li>Bullet one</li><li>Bullet two</li></ul>'
        .'<ol><li>Number one</li></ol>'
        .'<ul><li>Bullet three</li></ul>'
    );
});

test('indent classes are preserved', function () {
    $html = '<ol><li data-list="bullet" class="ql-indent-1">Nested</li></ol>';

    expect(QuillHtml::normalizeLists($html))
        ->toBe('<ul><li class="ql-indent-1">Nested</li></ul>');
});

test('surrounding content and inline formatting are left intact', function () {
    $html = '<p>Intro <strong>bold</strong></p>'
        .'<ol><li data-list="bullet">Item <em>italic</em></li></ol>'
        .'<p>Outro</p>';

    expect(QuillHtml::normalizeLists($html))->toBe(
        '<p>Intro <strong>bold</strong></p>'
        .'<ul><li>Item <em>italic</em></li></ul>'
        .'<p>Outro</p>'
    );
});

test('content with no list is returned untouched', function () {
    $html = '<p>Just a paragraph with a <a href="https://example.com">link</a>.</p>';

    expect(QuillHtml::normalizeLists($html))->toBe($html);
});

test('non-ascii characters survive', function () {
    $html = '<ol><li data-list="bullet">Café — naïve “quotes”</li></ol>';

    expect(QuillHtml::normalizeLists($html))
        ->toBe('<ul><li>Café — naïve “quotes”</li></ul>');
});
