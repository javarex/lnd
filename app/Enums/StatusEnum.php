<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: string implements HasLabel, HasIcon, HasColor
{
    case Pending  = 'Pending';
    case Approved = 'Approved';
    case Disapproved = 'Disapproved';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Disapproved => 'Disapproved',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Approved => 'success',
            self::Disapproved => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-s-clock',
            self::Approved => 'heroicon-s-hand-thumb-up',
            self::Disapproved => 'heroicon-s-hand-thumb-down',
        };
    }
}
