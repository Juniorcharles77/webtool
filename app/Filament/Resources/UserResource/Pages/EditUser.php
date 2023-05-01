<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Extensions\BitflanEditRecord;
use App\Filament\Resources\UserResource;

class EditUser extends BitflanEditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        $resource = static::getResource();

        return array_merge(
            (($resource::hasPage('view') && $resource::canView($this->record)) ? [$this->getViewAction()] : []),
            ($resource::canDelete($this->record) ? [$this->getDeleteAction()] : [])
        );
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        $data = parent::mutateFormDataBeforeSave($data);
        
        if($data['password'] && !empty($data['password']))
            $data['password'] = bcrypt($data['password']);
        else
            unset($data['password']);

        return $data;
    }
}
