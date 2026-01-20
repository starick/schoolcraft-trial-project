<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Worksheet;

new class extends Component {
    #[Computed]
    function worksheets()
    {
        $user = auth()->user();

        return Worksheet::where('user_id', $user->id)->get();
    }
};
?>

<div>
    <ul>
        @foreach ($this->worksheets as $worksheet)
            <li class="my-4 p-2 bg-amber-100">
                {{ $worksheet->title }}
            </li>
        @endforeach
    </ul>
</div>
