<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                ->required()
                ->maxLength(255)
                ->label('Nama'),
                
                TextInput::make('last_name')
                ->required()
                ->maxLength(255)
                ->label('Nama Belakang'),
                
                TextInput::make('phone')
                ->required()
                ->maxLength(20)
                ->tel()
                ->label('No Telepon'),
                
                TextInput::make('city')
                ->required()
                ->maxLength(255)
                ->label('Kota'),
                
                TextInput::make('state')
                ->required()
                ->maxLength(255)
                ->label('Negara'),
                
                TextInput::make('zip_code')
                ->required()
                ->maxLength(10)
                ->numeric()
                ->label('Kode Pos'),

                

                Forms\Components\TextInput::make('street_address')
                    ->required()
                    ->label('Alamat Lengkap')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                TextColumn::make('fullname')
                ->label('Nama'),
                
                TextColumn::make('phone')
                ->label('No Telepon'),
                
                TextColumn::make('city')
                ->label('Kota'),
                
                TextColumn::make('state')
                ->label('Negara'),

                TextColumn::make('zip_code')
                ->label('Kode Pos'),

                TextColumn::make('street_address')
                ->label('Alamat'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
