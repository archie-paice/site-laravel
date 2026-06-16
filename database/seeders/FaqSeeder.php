<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            // Getting Started
            [
                'category' => 'Getting Started',
                'question' => 'How do I join vZJX?',
                'answer' => "If your VATUSA home facility is Jacksonville (ZJX), you'll be added to our roster automatically. If you're with another ARTCC and want to control here too, submit a [visiting request](/visit). Make sure your VATSIM account is in good standing and you've completed the basic VATSIM training first.",
                'sort_order' => 1,
            ],
            [
                'category' => 'Getting Started',
                'question' => 'Do I need to be on the Discord server?',
                'answer' => "It's strongly recommended — most training coordination and announcements happen there — but this FAQ exists so you can find the key information even if you're not yet on Discord. You can join via the link on our homepage.",
                'sort_order' => 2,
            ],

            // Training
            [
                'category' => 'Training',
                'question' => 'How long does it take to get a trainer/mentor?',
                'answer' => "Wait times vary depending on mentor availability and how many students are currently in the queue. Once you request training, you'll be placed in a queue and assigned to a mentor as one becomes available. We ask for your patience — our mentors are volunteers. While you wait, the best thing you can do is study (see below).",
                'sort_order' => 1,
            ],
            [
                'category' => 'Training',
                'question' => 'What should I do while waiting for a trainer?',
                'answer' => "It's **recommended that you go over the VATUSA Academy courses several times** before your first session. Review the relevant rating course thoroughly so you arrive prepared — this makes training faster and more enjoyable for both you and your mentor. You can access the courses at the [VATUSA Academy](https://academy.vatusa.net).",
                'sort_order' => 2,
            ],
            [
                'category' => 'Training',
                'question' => 'What happens after I get assigned a trainer?',
                'answer' => "Once you're assigned a mentor, they'll reach out to coordinate your first session and schedule training around your availability. Sessions are typically conducted on the live network or sweatbox. Keep an eye on your email and Discord DMs for their message, and respond promptly so scheduling goes smoothly.",
                'sort_order' => 3,
            ],
            [
                'category' => 'Training',
                'question' => 'Where can I find training resources?',
                'answer' => "Start with the [VATUSA Academy](https://academy.vatusa.net) for your rating courses. ZJX-specific standard operating procedures (SOPs) and letters of agreement (LOAs) are available in our facility resources. If you're unsure where to look, ask your mentor or a member of the training staff.",
                'sort_order' => 4,
            ],

            // General Info
            [
                'category' => 'General Info',
                'question' => 'Where can I find ZJX SOPs and procedures?',
                'answer' => "Facility documents including SOPs and LOAs are published in our resources section. These are essential reading before controlling any position. If a link is broken or you can't find a document, reach out to the facilities staff.",
                'sort_order' => 1,
            ],
            [
                'category' => 'General Info',
                'question' => 'Who do I contact if I have a question not answered here?',
                'answer' => "For training questions, contact the Training Administrator or your assigned mentor. For roster or membership questions, contact the Air Traffic Manager (ATM) or Deputy ATM. You can find current staff on the [Facility Staff page](/staff).",
                'sort_order' => 2,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                $faq + ['is_published' => true],
            );
        }
    }
}
