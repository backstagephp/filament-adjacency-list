<?php

namespace Saade\FilamentAdjacencyList\Forms\Components;

use Filament\Forms\Components\Field;
use Saade\FilamentAdjacencyList\Forms\Components\Concerns\HasActions;
use Saade\FilamentAdjacencyList\Forms\Components\Concerns\HasForm;
use Filament\Schemas\Components\Concerns\CanBeCollapsed;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Livewire\Attributes\Renderless;
use Closure;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Saade\FilamentAdjacencyList\Forms\Components\Actions\Action;

abstract class Component extends Field
{
    use HasActions;
    use HasForm;
    use CanBeCollapsed;

    protected string $view = 'filament-adjacency-list::builder';

    protected string | Closure $labelKey = 'label';

    protected string | Closure $childrenKey = 'children';

    protected int | Closure | null $maxDepth = null;

    protected bool | Closure $hasRulers = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (Component $component, ?array $state) {
            if (! $state) {
                $component->state([]);
            }
        });

        $this->default([]);

        $this->registerActions([
            fn(Component $component): Action => $component->getAddAction(),
            fn(Component $component): Action => $component->getAddChildAction(),
            fn(Component $component): Action => $component->getDeleteAction(),
            fn(Component $component): Action => $component->getEditAction(),
            fn(Component $component): Action => $component->getReorderAction(),
            fn(Component $component): Action => $component->getIndentAction(),
            fn(Component $component): Action => $component->getDedentAction(),
            fn(Component $component): Action => $component->getMoveUpAction(),
            fn(Component $component): Action => $component->getMoveDownAction(),
        ]);
    }

    #[ExposedLivewireMethod]
    #[Renderless]
    public function builderSort(string $targetStatePath, array $targetItemsStatePaths)
    {
        if (! str_starts_with($targetStatePath, $this->getStatePath())) {
            return;
        }

        $state = $this->getState();
        $relativeStatePath = $this->getRelativeStatePath($targetStatePath);

        $items = [];
        foreach ($targetItemsStatePaths as $targetItemStatePath) {
            $targetItemRelativeStatePath = $this->getRelativeStatePath($targetItemStatePath);

            $item = data_get($state, $targetItemRelativeStatePath);
            $uuid = Str::afterLast($targetItemRelativeStatePath, '.');

            $items[$uuid] = $item;
        }

        if (! $relativeStatePath) {
            $state = $items;
        } else {
            data_set($state, $relativeStatePath, $items);
        }

        $this->state($state);
    }


    public function removeUploadedFile(string $fileKey): string | TemporaryUploadedFile | null
    {
        $files = $this->getRawState();
        $file = $files[$fileKey] ?? null;

        if (! $file) {
            return null;
        }

        if (is_string($file)) {
            $this->removeStoredFileName($file);
        } elseif ($file instanceof TemporaryUploadedFile) {
            $file->delete();
        }

        unset($files[$fileKey]);

        $this->rawState($files);
        $this->callAfterStateUpdated();

        return $file;
    }

    public function labelKey(string | Closure $key): static
    {
        $this->labelKey = $key;

        return $this;
    }

    public function getLabelKey(): string
    {
        return $this->evaluate($this->labelKey);
    }

    public function childrenKey(string | Closure $key): static
    {
        $this->childrenKey = $key;

        return $this;
    }

    public function getChildrenKey(): string
    {
        return $this->evaluate($this->childrenKey);
    }

    public function maxDepth(int | Closure $maxDepth): static
    {
        $this->maxDepth = $maxDepth;

        return $this;
    }

    public function getMaxDepth(): ?int
    {
        return $this->evaluate($this->maxDepth);
    }

    public function rulers(bool | Closure $condition = true): static
    {
        $this->hasRulers = $condition;

        return $this;
    }

    public function hasRulers(): bool
    {
        return $this->evaluate($this->hasRulers);
    }

    public function getRelativeStatePath(string $path): string
    {
        return str($path)->after($this->getStatePath())->trim('.')->toString();
    }
}
