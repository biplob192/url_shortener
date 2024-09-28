<?php

namespace App\Traits;

use Livewire\WithPagination;

trait WithPerPagePagination
{
    use WithPagination;

    public $perPage = 10;

    protected $paginationTheme = "bootstrap";

    public function mountWithPerPagePagination()
    {
        $this->perPage = session()->get( 'perPage', $this->perPage );
    }

    public function updatedPerPage( $value )
    {
        session()->put( 'perPage', $value );
        $this->resetPage();
    }

    public function applyPagination( $query )
    {
        return $query->paginate( $this->perPage );
    }
}
