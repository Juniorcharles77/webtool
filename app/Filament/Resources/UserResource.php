<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Content'; 

    public static $toSanitize = [
        'name',
        'email'
    ];

    public static function form(Form $form): Form
    {
        $form = $form->schema([
            Forms\Components\Grid::make()->schema([
                Forms\Components\TextInput::make('name')->required()->columnSpan(1),
                Forms\Components\TextInput::make('email')->required()->columnSpan(1)->email()->unique(ignorable: fn (?User $record): ?User => $record),

                Forms\Components\Toggle::make('admin')->columnSpan(1)->disabled(!auth()->user()->super_admin),
            ])
        ]);

        return $form;
    }

    public static function table(Table $table): Table
    {
        $table = $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('email')->searchable(),
            Tables\Columns\BooleanColumn::make('admin'),
        ]);

        return $table;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit')
        ];
    }
}
