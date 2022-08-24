<?php

namespace App\Http\Livewire;

use App\Models\ColumnsModel;
use App\Models\ViewUserModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColumnList extends Component
{

    //columns task
    public $column_add;

    /**
     * update column
     * @param $column_id
     */
    public function updatedColumnAdd($column_id)
    {
        ViewUserModel::updateOrInsert(
            ['column_id' => $column_id, 'user_id' => Auth::id()],
            ['sorting' => 0],
        );
        $this->columns_task = ViewUserModel::with('columns')->whereHas(
            'columns', function($q){
            $q->where('columns_model.model',  'Task');
        }
        )->where('user_id',  Auth::id())
            ->orderBy('sorting','ASC')->get()->keyBy('columns.field')->all();
//        $this->emit('closeColumnModal');

        session()->flash('type', 'success');
        session()->flash('message', __('Columns updated successfully'));
    }


    /**
     * update sorting column
     * @param $columns_list
     */
    public function updateColumnSorting($columns_list){
        foreach ($columns_list as $column_id=>$sorting){
            ViewUserModel::updateOrInsert(
                ['column_id' => $column_id, 'user_id' => Auth::id()],
                ['sorting' => $sorting],
            );
        }
        $this->columns_task = ViewUserModel::with('columns')->whereHas(
            'columns', function($q){
            $q->where('columns_model.model',  'Task');
        }
        )->where('user_id',  Auth::id())
            ->orderBy('sorting','ASC')->get()->keyBy('columns.field')->all();
        session()->flash('type', 'success');
        session()->flash('message', __('Columns updated successfully'));
    }

    /**
     * @param $column_id
     * delete Columns
     */
    public function deleteColumns($column_id){
        $count = ViewUserModel::with('columns')->whereHas(
            'columns', function($q){
            $q->where('columns_model.model',  'Task');
        }
        )->where('user_id',  Auth::id())->count();
        if($count>2){
            ViewUserModel::where('user_id',Auth::id())->where('id',$column_id)->delete();
            $this->columns_task = ViewUserModel::with('columns')->whereHas(
                'columns', function($q){
                $q->where('columns_model.model',  'Task');
            }
            )->where('user_id',  Auth::id())
                ->orderBy('sorting','ASC')
                ->get()->keyBy('columns.field')->all();
            session()->flash('type', 'success');
            session()->flash('message', __('Columns deleted successfully'));
        }else{
            session()->flash('type', 'danger');
            session()->flash('message', __('Not Allow!!!'));
        }
    }

    public function render()
    {
        $columns_task = ViewUserModel::with('columns')->whereHas(
            'columns', function($q){
            $q->where('columns_model.model',  'Task');
        }
        )->where('user_id',  Auth::id())
            ->orderBy('sorting','ASC')->get()->keyBy('columns.field')->all();
        $columns_list_task = ColumnsModel::where('model',  'Task')->get();
        return view('livewire.column-list',
        [
            'columns_task' => $columns_task,
            'columns_list_task' => $columns_list_task
        ]);
    }
}
