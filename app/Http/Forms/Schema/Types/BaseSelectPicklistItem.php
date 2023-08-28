<?php

namespace App\Http\Forms\Schema\Types;

use Closure;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class BaseSelectPicklistItem
{
    protected $relationship;
    protected $fieldName;
    protected $label;
    protected $picklistIdentifier;
    protected $isRequired = false;

    protected function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public static function build(string $fieldName): self
    {
        return new static($fieldName);
    }

    public function relationship(string $relationship): self
    {
        $this->relationship = $relationship;
        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function picklistIdentifier(string $identifier): self
    {
        $this->picklistIdentifier = $identifier;
        return $this;
    }

    public function required(bool $isRequired = true): self
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    public function get(): Field
    {
        $select = Select::make($this->fieldName)
            ->label($this->label)
            ->relationship($this->relationship, 'label', (function (Builder $query) {
                $query->ofPicklistIdentifier($this->picklistIdentifier);
            }));

        if ($this->isRequired) {
            $select->required();
        }

        return $select;
    }
}

