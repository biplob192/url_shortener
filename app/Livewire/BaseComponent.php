<?php
namespace App\Livewire;

use Livewire\Component;
use App\View\Components\Admin\Layout;

class BaseComponent extends Component
{
    protected $layout = "layouts.app";
    /**
     * get all the attribute and properties of parent layout blade component
     *
     * @return array
     */
    protected function getLayoutAttributes(): array
    {
        return app( Layout::class )->data();
    }

    /**
     * alert message for handled by livewire browser event
     *
     * @param  string $message
     * @param  string $type
     * @return void
     */
    protected function alertMessage( $message, $type = "success" ): void
    {
        // $this->dispatchBrowserEvent( 'alert', ['type' => $type, 'message' => $message] );
        $this->dispatch( 'alert', ['type' => $type, 'message' => $message] );
    }

    /**
     * Notification message
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    protected function toastMessage( $message, $type = "success" ): void
    {
        $this->dispatchBrowserEvent( 'notify', ['type' => $type, 'message' => $message] );
    }

    /**
     * raise browser event to display confirm dialog
     * example: confirm before deleting user
     *
     * @param  string         $actionProp
     * @param  string|integer $value
     * @return void
     */
    public function showConfirmDialog( $actionProp, $value = null )
    {
        $this->{$actionProp} = $value;
        $this->dispatchBrowserEvent( 'showConfirmDialog', $value );
    }

    /**
     * raise event to hide modal dialog, it is supposed to call from component method,
     * example: after deleting something
     *
     * @return void
     */
    protected function hideModal()
    {
        $this->dispatch( 'hideModal' );
    }

    /**
     * raise event to hide confirm dialog, it is supposed to call from component method,
     * example: after deleting something
     *
     * @return void
     */
    protected function hideConfirm()
    {
        $this->dispatch( 'hideConfirmDialog' );
    }

    protected function view( string $view, array $data = [] )
    {
        return view( $view, $data )->extends( $this->layout );
    }

    protected function hideOffCanvas()
    {
        // $this->dispatchBrowserEvent('hideOffCanvas');
        $this->dispatch('hideOffCanvas');
    }
}
