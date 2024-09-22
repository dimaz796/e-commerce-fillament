<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    // Section for Order Information
                    Section::make('Informasi Order')->schema([
                        Select::make('user_id')
                            ->label('Pembeli')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(6), // Mengatur kolom menjadi setengah halaman
    
                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'stripe' => 'Stripe',
                                'cod' => 'Cash On Delivery',
                            ])
                            ->required()
                            ->columnSpan(6), // Mengatur kolom menjadi setengah halaman
    
                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required()
                            ->columnSpanFull(),
    
                            ToggleButtons::make('status')
                            ->label('Status Order')
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'canceled' => 'Canceled',
                            ])
                            ->default('new')
                            ->inline() // Pastikan ini digunakan
                            ->columnSpanFull() // Membuat tombol menggunakan lebar penuh
                            ->colors([
                                'new' => 'info', 
                                'processing' => 'warning', 
                                'shipped' => 'success', 
                                'delivered' => 'success', 
                                'canceled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'canceled' => 'heroicon-m-x-circle',
                            ])
                            ->required(),

                        
                        
    
                        Select::make('currency')
                            ->label('Mata Uang')
                            ->options([
                                'idr' => 'Rp',
                                'usd' => '$',
                            ])
                            ->required()
                            ->columnSpan(6),
    
                        Select::make('shipping_method')
                            ->label('Metode Pengiriman')
                            ->options([
                                'jne' => 'JNE',
                                'tiki' => 'TIKI',
                                'pos' => 'POS Indonesia',
                            ])
                            ->required()
                            ->columnSpan(6),
    
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(), // Mengatur textarea agar full width
                    ])->columns(12), // Mengatur agar setiap field mengambil kolom dari 12 grid sistem
                    // Section for Order Items
                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set) => $set('unit_amount', Product::find($state)->price ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set) => $set('total_amount', Product::find($state)->price ?? 0))
                                    ->preload()
                                    ->required()
                                    ->columnSpan(4),
    
                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->default(1)
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount'))),
    
                                TextInput::make('unit_amount')
                                    ->label('Harga Barang')
                                    ->numeric()
                                    ->required()
                                    ->readOnly()
                                    ->columnSpan(3),
    
                                TextInput::make('total_amount')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(3),
                            ])
                            ->columns(12), // Mengatur setiap item dalam repeater mengambil grid 12
                        // Placeholder for Grand Total calculation
                        Placeholder::make('grand_total_placeholder')
                            ->label('Grand Total')
                            ->content(function (Get $get, Set $set) {
                                $total = 0;
                                if (!$repeaterItems = $get('items')) {
                                    return $total;
                                }
    
                                foreach ($repeaterItems as $key => $repeaterItem) {
                                    $total += $get("items.{$key}.total_amount");
                                }
    
                                $set('grand_total', $total);
                                return Number::currency($total, 'idr');
                            }),
    
                        // Hidden field for storing Grand Total value
                        Hidden::make('grand_total')
                            ->label('Grand Total')
                            ->reactive()
                            ->dehydrated()
                            ->afterStateHydrated(function (Set $set, Get $get) {
                                $total = 0;
                                if ($items = $get('items')) {
                                    foreach ($items as $key => $item) {
                                        $total += $item['total_amount'];
                                    }
                                }
                                $set('grand_total', $total);
                            }),
                    ]),
                ])->columns(12), // Mengatur agar field dalam section bisa menggunakan grid sistem 12 kolom
            ])->columns(1);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                ->label('Pembeli')
                ->sortable()
                ->searchable(),

                TextColumn::make('grand_total')
                ->label('Total')
                ->sortable()
                ->formatStateUsing(fn (string $state): string => 'Rp. ' . number_format($state, 2)),

                TextColumn::make('payment_method')
                ->label('Metode Pembayaran')
                ->sortable()
                ->searchable(),

                TextColumn::make('payment_status')
                ->label('Status Pembayaran')
                ->sortable(),
                
                TextColumn::make('shipping_method')
                ->label('Expedisil Pengiriman')
                ->sortable()
                ->sortable(),

                SelectColumn::make('status')
                ->label('Status Order')
                ->options([
                    'new' => 'New',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'canceled' => 'Canceled',
                ])
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }
    
    public static function getNavigationBadge(): ? string
    {
        return static::getModel()::count();
    } 
    
    public static function getNavigationBagdeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'success' : 'danger';
    } 

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
