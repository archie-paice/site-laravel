<?php

namespace App\Support;

use DOMDocument;
use DOMElement;

class QuillHtml
{
    /**
     * Rewrite Quill's list markup into plain semantic HTML.
     *
     * Quill renders *every* list as <ol>, bulleted or not, and carries the real
     * type in a data-list attribute on each <li> (bullet / ordered / checked /
     * unchecked). Its own stylesheet has no <ul> rules at all. That attribute has
     * no business surviving into stored content, and the sanitizer strips it — at
     * which point a bulleted list is indistinguishable from a numbered one and
     * renders with numbers.
     *
     * So convert the type into the tag itself before sanitizing: runs of bulleted
     * items become a <ul>, runs of numbered items stay an <ol>. Indent classes
     * (ql-indent-N) are left alone; they're allow-listed in config/purify.php.
     */
    public static function normalizeLists(string $html): string
    {
        if (! str_contains($html, '<li')) {
            return $html;
        }

        $dom = new DOMDocument;

        $previous = libxml_use_internal_errors(true);
        // The XML declaration forces UTF-8; without it DOMDocument assumes Latin-1
        // and mangles anything non-ASCII. The wrapper div gives a single, known
        // root to read the children back out of.
        $dom->loadHTML(
            '<?xml encoding="utf-8"?><div id="quill-root">'.$html.'</div>',
            LIBXML_NOERROR | LIBXML_NOWARNING
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementById('quill-root');

        if (! $root) {
            return $html;
        }

        foreach (iterator_to_array($dom->getElementsByTagName('ol')) as $list) {
            self::splitByListType($dom, $list);
        }

        $result = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $result .= $dom->saveHTML($child);
        }

        return $result;
    }

    /**
     * Replace one Quill <ol> with one element per run of same-typed items.
     */
    private static function splitByListType(DOMDocument $dom, DOMElement $list): void
    {
        $items = [];
        foreach ($list->childNodes as $child) {
            if ($child instanceof DOMElement && $child->tagName === 'li') {
                $items[] = $child;
            }
        }

        if ($items === []) {
            return;
        }

        // Group adjacent items by tag, so a bulleted run followed by a numbered
        // run inside one Quill <ol> becomes a <ul> then an <ol>.
        $groups = [];
        foreach ($items as $item) {
            $tag = $item->getAttribute('data-list') === 'bullet' ? 'ul' : 'ol';
            $item->removeAttribute('data-list');

            if ($groups === [] || end($groups)['tag'] !== $tag) {
                $groups[] = ['tag' => $tag, 'items' => []];
            }

            $groups[array_key_last($groups)]['items'][] = $item;
        }

        // A single numbered run is already correct markup — leave the node alone
        // so untouched content round-trips byte for byte.
        if (count($groups) === 1 && $groups[0]['tag'] === 'ol') {
            return;
        }

        foreach ($groups as $group) {
            $replacement = $dom->createElement($group['tag']);

            foreach ($group['items'] as $item) {
                $replacement->appendChild($item);
            }

            $list->parentNode->insertBefore($replacement, $list);
        }

        $list->parentNode->removeChild($list);
    }
}
