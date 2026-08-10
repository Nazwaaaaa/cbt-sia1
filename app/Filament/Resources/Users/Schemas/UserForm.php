<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    //upload foto
                    FileUpload::make('photo_path')
                    ->label('Upload foto profil')
                    ->hiddenLabel()
                    ->image() //khusus uppload gambar
                    ->avatar() //resize otomatis dan circular
                    ->disk('public') //partisi storage
                    ->directory('user-photos') //nama folder
                    ->maxSize(1024) //ukuran max 1 mb
                    ->imageEditor() //edit ukuran foto di profil 
                    ->columnSpanFull()
                    ->alignCenter(),

                    TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->unique('users', 'email')
                    ->prefix('@')
                    ->email(),
                TextInput::make('username')
                    ->label('Login username')
                    ->unique( //harus unique dengan user lain
                        table: 'users',
                        column: 'username',
                    )
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    //->prefixIcon('heroicon-o-phone') = kalau pakai string
                    ->prefixIcon(Heroicon::OutlinedPhone)
                    ->tel()
                    ->default(null),
                TextInput::make('password')
                    ->hiddenOn('edit') //disembunyikan di halaman edit
                    ->revealable() //tampilkan password
                    ->password()
                    ->required(),]),
            ]);
    }
}
