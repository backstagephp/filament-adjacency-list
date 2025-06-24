<?php

namespace Saade\FilamentAdjacencyList\Forms\Components\Concerns;

use Saade\FilamentAdjacencyList\Forms\Components\Actions\Action;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\AddAction;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\AddChildAction;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\DeleteAction;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\EditAction;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\ReorderAction;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\IndentAction;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\DedentAction;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\MoveUpAction;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\MoveDownAction;
use Closure;
use Saade\FilamentAdjacencyList\Forms\Components\Actions;

trait HasActions
{
    protected bool | Closure $isAddable = true;

    protected bool | Closure $isEditable = true;

    protected bool | Closure $isDeletable = true;

    protected bool | Closure $isReorderable = true;

    protected bool | Closure $isIndentable = true;

    protected bool | Closure $isMoveable = true;

    protected ?Closure $modifyAddActionUsing = null;

    protected ?Closure $modifyAddChildActionUsing = null;

    protected ?Closure $modifydeleteActionUsing = null;

    protected ?Closure $modifyEditActionUsing = null;

    protected ?Closure $modifyReorderActionUsing = null;

    protected ?Closure $modifyIndentActionUsing = null;

    protected ?Closure $modifyDedentActionUsing = null;

    protected ?Closure $modifyMoveUpActionUsing = null;

    protected ?Closure $modifyMoveDownActionUsing = null;

    public function getAddAction(): Action
    {
        $action = AddAction::make();

        if ($this->modifyAddActionUsing) {
            $action = $this->evaluate($this->modifyAddActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function addAction(?Closure $callback): static
    {
        $this->modifyAddActionUsing = $callback;

        return $this;
    }

    public function getAddChildAction(): Action
    {
        $action = AddChildAction::make();

        if ($this->modifyAddChildActionUsing) {
            $action = $this->evaluate($this->modifyAddChildActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function addChildAction(?Closure $callback): static
    {
        $this->modifyAddChildActionUsing = $callback;

        return $this;
    }

    public function getDeleteAction(): Action
    {
        $action = DeleteAction::make();

        if ($this->modifydeleteActionUsing) {
            $action = $this->evaluate($this->modifydeleteActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function deleteAction(?Closure $callback): static
    {
        $this->modifydeleteActionUsing = $callback;

        return $this;
    }

    public function getEditAction(): Action
    {
        $action = EditAction::make();

        if ($this->modifyEditActionUsing) {
            $action = $this->evaluate($this->modifyEditActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function editAction(?Closure $callback): static
    {
        $this->modifyEditActionUsing = $callback;

        return $this;
    }

    public function getReorderAction(): Action
    {
        $action = ReorderAction::make();

        if ($this->modifyReorderActionUsing) {
            $action = $this->evaluate($this->modifyReorderActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        $action->extraAttributes([
            'data-sortable-handle' => 'true',
            ...$action->getExtraAttributes(),
        ]);

        return $action;
    }

    public function reorderAction(?Closure $callback): static
    {
        $this->modifyReorderActionUsing = $callback;

        return $this;
    }

    public function getIndentAction(): Action
    {
        $action = IndentAction::make();

        if ($this->modifyIndentActionUsing) {
            $action = $this->evaluate($this->modifyIndentActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function indentAction(?Closure $callback): static
    {
        $this->modifyIndentActionUsing = $callback;

        return $this;
    }

    public function getDedentAction(): Action
    {
        $action = DedentAction::make();

        if ($this->modifyDedentActionUsing) {
            $action = $this->evaluate($this->modifyDedentActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function dedentAction(?Closure $callback): static
    {
        $this->modifyDedentActionUsing = $callback;

        return $this;
    }

    public function getMoveUpAction(): Action
    {
        $action = MoveUpAction::make();

        if ($this->modifyMoveUpActionUsing) {
            $action = $this->evaluate($this->modifyMoveUpActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function moveUpAction(?Closure $callback): static
    {
        $this->modifyMoveUpActionUsing = $callback;

        return $this;
    }

    public function getMoveDownAction(): Action
    {
        $action = MoveDownAction::make();

        if ($this->modifyMoveDownActionUsing) {
            $action = $this->evaluate($this->modifyMoveDownActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function moveDownAction(?Closure $callback): static
    {
        $this->modifyMoveDownActionUsing = $callback;

        return $this;
    }

    public function addable(bool | Closure $condition = true): static
    {
        $this->isAddable = $condition;

        return $this;
    }

    public function isAddable(): bool
    {
        if ($this->isDisabled()) {
            return false;
        }

        return (bool) $this->evaluate($this->isAddable);
    }

    public function deletable(bool | Closure $condition = true): static
    {
        $this->isDeletable = $condition;

        return $this;
    }

    public function isDeletable(): bool
    {
        if ($this->isDisabled()) {
            return false;
        }

        return (bool) $this->evaluate($this->isDeletable);
    }

    public function editable(bool | Closure $condition = true): static
    {
        $this->isEditable = $condition;

        return $this;
    }

    public function isEditable(): bool
    {
        if ($this->isDisabled()) {
            return false;
        }

        return (bool) $this->evaluate($this->isEditable);
    }

    public function reorderable(bool | Closure $condition = true): static
    {
        $this->isReorderable = $condition;

        return $this;
    }

    public function isReorderable(): bool
    {
        if ($this->isDisabled()) {
            return false;
        }

        return (bool) $this->evaluate($this->isReorderable);
    }

    public function indentable(bool | Closure $condition = true): static
    {
        $this->isIndentable = $condition;

        return $this;
    }

    public function isIndentable(): bool
    {
        if ($this->isDisabled()) {
            return false;
        }

        return (bool) $this->evaluate($this->isIndentable);
    }

    public function moveable(bool | Closure $condition = true): static
    {
        $this->isMoveable = $condition;

        return $this;
    }

    public function isMoveable(): bool
    {
        if ($this->isDisabled()) {
            return false;
        }

        return (bool) $this->evaluate($this->isMoveable);
    }
}
