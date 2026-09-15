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

test('indent levels that skip numbers are collapsed to consecutive steps', function () {
    // Quill counts indent keypresses, not depth — pasted content often lands on
    // only the even levels, which doubles every visual step.
    $html = '<ol>'
        .'<li data-list="bullet">Top</li>'
        .'<li data-list="bullet" class="ql-indent-2">Second level</li>'
        .'<li data-list="bullet" class="ql-indent-4">Third level</li>'
        .'</ol>';

    expect(QuillHtml::normalizeLists($html))->toBe(
        '<ul>'
        .'<li>Top</li>'
        .'<li class="ql-indent-1">Second level</li>'
        .'<li class="ql-indent-2">Third level</li>'
        .'</ul>'
    );
});

test('already-consecutive indent levels are left alone', function () {
    $html = '<ol>'
        .'<li data-list="bullet">Top</li>'
        .'<li data-list="bullet" class="ql-indent-1">Second</li>'
        .'<li data-list="bullet" class="ql-indent-2">Third</li>'
        .'</ol>';

    expect(QuillHtml::normalizeLists($html))->toBe(
        '<ul>'
        .'<li>Top</li>'
        .'<li class="ql-indent-1">Second</li>'
        .'<li class="ql-indent-2">Third</li>'
        .'</ul>'
    );
});

test('indent levels on paragraphs are collapsed too, sharing one scale with lists', function () {
    $html = '<p class="ql-indent-2">Indented paragraph</p>'
        .'<ol><li data-list="bullet" class="ql-indent-4">Deeper item</li></ol>';

    expect(QuillHtml::normalizeLists($html))->toBe(
        '<p class="ql-indent-1">Indented paragraph</p>'
        .'<ul><li class="ql-indent-2">Deeper item</li></ul>'
    );
});
