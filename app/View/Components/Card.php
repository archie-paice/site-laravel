<?php

namespace App\View\Components;

use Illuminate\Console\View\Components\Component;

class CardComponent extends Component {
    public $title;

    public function __construct($title) {
        $this->title = $title;
    }
    public function render() {
        return view("components.card");
    }
}
