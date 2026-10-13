<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum CardStatus: string implements HasLabel,HasColor
{
    //active', 'inactive', 'lost', 'expired', 'demo'
    case active = 'active';
    case inactive = 'inactive';
    case lost = 'lost';
    case expired = 'expired';
    case demo = 'demo';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or

        return match ($this) {
            self::active => __('reg.active'),
            self::inactive =>  __('reg.inactive'),
            self::lost =>  __('reg.lost'),
            self::expired =>  __('reg.expired'),
            self::demo =>  __('reg.demo'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::active => 'gray',
            self::inactive => 'warning',
            self::lost => 'success',
            self::expired => 'danger',
            self::demo => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::active => 'heroicon-m-pencil',
            self::inactive => 'heroicon-m-eye',
            self::lost => 'heroicon-m-check',
            self::expired => 'heroicon-m-x-mark',
            self::demo => 'heroicon-m-x-mark',
        };
    }
}
