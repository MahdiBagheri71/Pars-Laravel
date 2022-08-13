<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Tasks;


class ShowTasks extends Component
{

    public $search_tasks = '';

    protected $paginationTheme = 'bootstrap';



    public function paginationView()

    {

        return 'custom-pagination-links-view';

    }

    public function updatingSearch()

    {

        $this->resetPage();

    }
    public function render()
    {
        return view('livewire.show-tasks', [

            'tasks' => Tasks::where('name', 'like', '%'.$this->search_tasks.'%')->paginate(10),

        ]);
    }
}
