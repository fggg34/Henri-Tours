<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\BlogCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('blog_category_id')
                    ->label('Category')
                    ->options(BlogCategory::pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                Select::make('tags')
                    ->label('Tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->dehydrated(true)
                    ->nullable(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                RichEditor::make('excerpt')
                    ->label('Excerpt')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('Content')
                    ->columnSpanFull(),
                FileUpload::make('featured_image')
                    ->label('Featured image')
                    ->image()
                    ->disk('public')
                    ->directory('blog-posts')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                    ->imagePreviewHeight('260')
                    ->panelAspectRatio('16/10')
                    ->panelLayout('integrated'),
                TextInput::make('meta_title'),
                TextInput::make('meta_description'),
                Toggle::make('is_published')
                    ->required(),
                Toggle::make('is_featured')
                    ->label('Featured')
                    ->helperText('Show in the featured section at top of blog archive'),
                DateTimePicker::make('published_at'),
            ]);
    }
}
