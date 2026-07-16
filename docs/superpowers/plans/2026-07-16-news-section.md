# News Section Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Admin postet News in Filament; Frontend zeigt News als Karten-Übersicht und SEO-optimierte Detailseiten; Footer verlinkt die Übersicht.

**Architecture:** Klon des bestehenden Changelog-Features, erweitert um `slug`, `description`, `image` und eine eigene Detailseite. Model = Thin Active Record (HasUuids). Slug-Erzeugung in einer Action. Öffentliche Inertia-Pages, Bild über `public` disk.

**Tech Stack:** Laravel 13, PHP 8.4, Filament v5, Inertia v3 + Vue 3, Pest v4, Tailwind v4.

## Global Constraints

- `declare(strict_types=1);` in allen PHP-Dateien (Pflicht).
- PHPStan Level 9: keine `mixed`, explizite Typen, DocBlock-Generics für Collections.
- Thin Model: keine Logik/Scopes im Model; Business-Logik nur in Actions.
- Migrations: nie `$table->enum()`.
- Enums: nur UpperCase-Cases (hier keiner nötig).
- Keine Prosa-Kommentare im Code — nur PHPDoc-Typannotationen.
- `vendor/bin/pint --dirty --format agent` vor Abschluss jeder PHP-Task.
- Tests laufen mit `php artisan test --compact --filter=News`.
- Filament-Labels im Code englisch (konsistent mit bestehenden Resources).
- Admin in Tests: `User::factory()->create(['role' => UserRole::SUPER_ADMIN])` + `actingAs`.
- Doku-Pflicht: nach Implementierung `docs/help/2026_07_16-news-section.md` anlegen.

---

## File Structure

**Neu:**
- `database/migrations/2026_07_16_120000_create_news_table.php` — Tabelle `news`.
- `app/Models/News.php` — Thin Model.
- `database/factories/NewsFactory.php` — Factory + States.
- `app/Actions/News/GenerateNewsSlugAction.php` — eindeutigen Slug erzeugen.
- `app/Http/Resources/NewsResource.php` — JSON-Shape inkl. `image_url`.
- `app/Http/Controllers/NewsController.php` — `index` + `show`.
- `app/Filament/Resources/News/NewsResource.php` (+ `Schemas/NewsForm.php`, `Tables/NewsTable.php`, `Pages/{List,Create,Edit}News.php`).
- `resources/js/pages/news/Index.vue`, `resources/js/pages/news/Show.vue`.
- `resources/js/types/news.ts`.
- `tests/Unit/Actions/GenerateNewsSlugActionTest.php`.
- `tests/Feature/News/NewsPageTest.php`.
- `tests/Feature/Filament/NewsResourceTest.php`.
- `docs/help/2026_07_16-news-section.md`.

**Geändert:**
- `routes/web.php` — zwei öffentliche Routen.
- `resources/js/types/index.ts` — Re-Export.
- `resources/js/components/landing/LandingFooter.vue` — News-Link.

---

## Task 1: Datenmodell, Migration, Factory, Slug-Action

**Files:**
- Create: `database/migrations/2026_07_16_120000_create_news_table.php`
- Create: `app/Models/News.php`
- Create: `database/factories/NewsFactory.php`
- Create: `app/Actions/News/GenerateNewsSlugAction.php`
- Test: `tests/Unit/Actions/GenerateNewsSlugActionTest.php`

**Interfaces:**
- Produces:
  - `App\Models\News` — `$uuid, $slug, $title, $description, $image_path, $content, $published_at`; Route-Key = `slug`.
  - `App\Actions\News\GenerateNewsSlugAction::handle(string $title, ?int $ignoreId = null): string`
  - `NewsFactory` States: `unpublished()`, `withoutImage()`, `publishedAt(DateTimeInterface $d)`.

- [ ] **Step 1: Migration schreiben**

Create `database/migrations/2026_07_16_120000_create_news_table.php`:

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', static function (Blueprint $table): void {
            $table->id();
            $table->uuid()->unique();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->longText('content');
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
```

- [ ] **Step 2: Migration ausführen**

Run: `php artisan migrate`
Expected: `... create_news_table ... DONE`

- [ ] **Step 3: Model schreiben**

Create `app/Models/News.php`:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string|null $image_path
 * @property string $content
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static NewsFactory factory($count = null, $state = [])
 * @method static Builder<static>|News newModelQuery()
 * @method static Builder<static>|News newQuery()
 * @method static Builder<static>|News query()
 *
 * @mixin Model
 */
#[Fillable('slug', 'title', 'description', 'image_path', 'content', 'published_at')]
final class News extends Model
{
    /** @use HasFactory<NewsFactory> */
    use HasFactory;

    use HasUuids;

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'slug' => 'string',
            'title' => 'string',
            'description' => 'string',
            'image_path' => 'string',
            'content' => 'string',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
```

- [ ] **Step 4: Slug-Action schreiben**

Create `app/Actions/News/GenerateNewsSlugAction.php`:

```php
<?php

declare(strict_types=1);

namespace App\Actions\News;

use App\Models\News;
use Illuminate\Support\Str;

final class GenerateNewsSlugAction
{
    public function handle(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = 'news';
        }

        $slug = $base;
        $suffix = 2;

        while ($this->exists($slug, $ignoreId)) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function exists(string $slug, ?int $ignoreId): bool
    {
        return News::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn (Builder $query): Builder => $query->whereKeyNot($ignoreId))
            ->exists();
    }
}
```

Note: `Builder` import ergänzen — `use Illuminate\Database\Eloquent\Builder;`.

- [ ] **Step 5: Factory schreiben**

Create `database/factories/NewsFactory.php`:

```php
<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\News;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<News>
 */
final class NewsFactory extends Factory
{
    /**
     * @var class-string<News>
     */
    protected $model = News::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 999999),
            'title' => $title,
            'description' => fake()->sentence(12),
            'image_path' => 'news/'.fake()->uuid().'.jpg',
            'content' => $this->richContent(),
            'published_at' => now()->subDays(fake()->numberBetween(0, 60)),
        ];
    }

    public function unpublished(): self
    {
        return $this->state(fn (): array => ['published_at' => null]);
    }

    public function withoutImage(): self
    {
        return $this->state(fn (): array => ['image_path' => null]);
    }

    public function publishedAt(DateTimeInterface $date): self
    {
        return $this->state(fn (): array => ['published_at' => $date]);
    }

    private function richContent(): string
    {
        $paragraphs = collect(range(1, fake()->numberBetween(2, 4)))
            ->map(fn (): string => '<p>'.fake()->paragraph().'</p>')
            ->implode('');

        return <<<HTML
<h2>{$this->faker->sentence(3)}</h2>
{$paragraphs}
HTML;
    }
}
```

- [ ] **Step 6: Failing test für Slug-Action schreiben**

Create `tests/Unit/Actions/GenerateNewsSlugActionTest.php`:

```php
<?php

declare(strict_types=1);

use App\Actions\News\GenerateNewsSlugAction;
use App\Models\News;

test('it slugifies the title', function (): void {
    $slug = app(GenerateNewsSlugAction::class)->handle('Hello World News');

    expect($slug)->toBe('hello-world-news');
});

test('it appends a suffix on collision', function (): void {
    News::factory()->create(['slug' => 'launch-day']);

    $slug = app(GenerateNewsSlugAction::class)->handle('Launch Day');

    expect($slug)->toBe('launch-day-2');
});

test('it ignores the current record when checking uniqueness', function (): void {
    $news = News::factory()->create(['slug' => 'launch-day']);

    $slug = app(GenerateNewsSlugAction::class)->handle('Launch Day', $news->id);

    expect($slug)->toBe('launch-day');
});

test('it falls back when the title has no sluggable characters', function (): void {
    $slug = app(GenerateNewsSlugAction::class)->handle('...');

    expect($slug)->toBe('news');
});
```

- [ ] **Step 7: Tests ausführen**

Run: `php artisan test --compact --filter=GenerateNewsSlugAction`
Expected: 4 passed.

- [ ] **Step 8: Pint + Commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Models/News.php app/Actions/News app/Http database/migrations/2026_07_16_120000_create_news_table.php database/factories/NewsFactory.php tests/Unit/Actions/GenerateNewsSlugActionTest.php
git commit -m "feat: add News model, migration, factory and slug action"
```

---

## Task 2: Öffentlicher Controller, Resource, Routen

**Files:**
- Create: `app/Http/Resources/NewsResource.php`
- Create: `app/Http/Controllers/NewsController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/News/NewsPageTest.php`

**Interfaces:**
- Consumes: `App\Models\News`, `NewsFactory` (Task 1).
- Produces:
  - Routen `news.index` (`GET /news`), `news.show` (`GET /news/{news:slug}`).
  - Inertia-Pages `news/Index` (Prop `entries`), `news/Show` (Props `article`, `url`).
  - `NewsResource`-Shape: `uuid, slug, title, description, image_url, content, published_at`.

- [ ] **Step 1: NewsResource schreiben**

Create `app/Http/Resources/NewsResource.php`:

```php
<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin News
 */
final class NewsResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->image_path !== null
                ? Storage::disk('public')->url($this->image_path)
                : null,
            'content' => $this->content,
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
```

- [ ] **Step 2: Controller schreiben**

Create `app/Http/Controllers/NewsController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Models\News;
use Inertia\Inertia;
use Inertia\Response;

final class NewsController
{
    public function index(): Response
    {
        return Inertia::render('news/Index', [
            'entries' => Inertia::scroll(static fn () => NewsResource::collection(
                News::query()
                    ->whereNotNull('published_at')
                    ->latest('published_at')
                    ->orderByDesc('id')
                    ->cursorPaginate(12),
            )),
        ]);
    }

    public function show(News $news): Response
    {
        abort_if($news->published_at === null, 404);

        return Inertia::render('news/Show', [
            'article' => new NewsResource($news),
            'url' => route('news.show', $news),
        ]);
    }
}
```

- [ ] **Step 3: Routen ergänzen**

Modify `routes/web.php` — Import und Routen neben Changelog ergänzen:

```php
use App\Http\Controllers\NewsController;
```

```php
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
```

- [ ] **Step 4: Failing Feature-Tests schreiben**

Create `tests/Feature/News/NewsPageTest.php`:

```php
<?php

declare(strict_types=1);

use App\Models\News;
use Inertia\Testing\AssertableInertia;

test('news index is public and lists only published entries', function (): void {
    News::factory()->create(['title' => 'Published one']);
    News::factory()->unpublished()->create(['title' => 'Draft']);

    $this->get('/news')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->component('news/Index')
                ->has('entries.data', 1)
                ->where('entries.data.0.title', 'Published one'),
        );
});

test('news index is cursor paginated at 12', function (): void {
    News::factory()->count(15)->create();

    $this->get('/news')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page->has('entries.data', 12),
        );
});

test('news show returns a published article with content', function (): void {
    $news = News::factory()->create(['slug' => 'launch-day', 'title' => 'Launch Day']);

    $this->get('/news/launch-day')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->component('news/Show')
                ->where('article.title', 'Launch Day')
                ->where('article.content', $news->content)
                ->has('url'),
        );
});

test('news show 404s for a draft', function (): void {
    News::factory()->unpublished()->create(['slug' => 'secret']);

    $this->get('/news/secret')->assertNotFound();
});

test('news show 404s for an unknown slug', function (): void {
    $this->get('/news/does-not-exist')->assertNotFound();
});

test('news show exposes an absolute image url when an image exists', function (): void {
    News::factory()->create(['slug' => 'with-image', 'image_path' => 'news/x.jpg']);

    $this->get('/news/with-image')
        ->assertOk()
        ->assertInertia(
            fn (AssertableInertia $page): AssertableInertia => $page
                ->where('article.image_url', fn (?string $url): bool => is_string($url) && str_contains($url, 'news/x.jpg')),
        );
});

test('news route is named news.index', function (): void {
    expect(route('news.index', absolute: false))->toBe('/news');
});
```

- [ ] **Step 5: Tests ausführen**

Run: `php artisan test --compact --filter=NewsPage`
Expected: 7 passed. (Inertia-Tests rendern die Vue-Datei nicht — Pages folgen in Task 4.)

- [ ] **Step 6: Pint + Commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Http/Resources/NewsResource.php app/Http/Controllers/NewsController.php routes/web.php tests/Feature/News/NewsPageTest.php
git commit -m "feat: add public News controller, resource and routes"
```

---

## Task 3: Filament NewsResource

**Files:**
- Create: `app/Filament/Resources/News/NewsResource.php`
- Create: `app/Filament/Resources/News/Schemas/NewsForm.php`
- Create: `app/Filament/Resources/News/Tables/NewsTable.php`
- Create: `app/Filament/Resources/News/Pages/ListNews.php`
- Create: `app/Filament/Resources/News/Pages/CreateNews.php`
- Create: `app/Filament/Resources/News/Pages/EditNews.php`
- Test: `tests/Feature/Filament/NewsResourceTest.php`

**Interfaces:**
- Consumes: `App\Models\News`, `GenerateNewsSlugAction` (Task 1).
- Produces: Filament CRUD unter `/admin/news`; Slug wird auto-generiert wenn leer.

- [ ] **Step 1: Form-Schema schreiben**

Create `app/Filament/Resources/News/Schemas/NewsForm.php`:

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->helperText('Leave empty to generate from the title.')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                DateTimePicker::make('published_at')
                    ->label('Published at')
                    ->seconds(false)
                    ->native(false),
                Textarea::make('description')
                    ->label('Description')
                    ->helperText('Short summary shown on the overview cards.')
                    ->required()
                    ->maxLength(500)
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('news')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('Content')
                    ->required()
                    ->columnSpanFull()
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
                        ['h1', 'h2', 'h3', 'small'],
                        ['link', 'highlight'],
                        ['code', 'codeBlock', 'blockquote'],
                        ['bulletList', 'orderedList'],
                        ['table', 'grid', 'attachFiles'],
                        ['undo', 'redo'],
                    ]),
            ]);
    }
}
```

- [ ] **Step 2: Table-Schema schreiben**

Create `app/Filament/Resources/News/Tables/NewsTable.php`:

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Tables;

use App\Models\News;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(60),
                IconColumn::make('published_at')
                    ->label('Published')
                    ->boolean()
                    ->getStateUsing(fn (News $record): bool => $record->published_at !== null),
                TextColumn::make('published_at')
                    ->label('Published at')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('Draft')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
```

- [ ] **Step 3: Resource schreiben**

Create `app/Filament/Resources/News/NewsResource.php`:

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\News;

use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Pages\EditNews;
use App\Filament\Resources\News\Pages\ListNews;
use App\Filament\Resources\News\Schemas\NewsForm;
use App\Filament\Resources\News\Tables\NewsTable;
use App\Models\News;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationLabel(): string
    {
        return 'News';
    }

    public static function getModelLabel(): string
    {
        return 'News Article';
    }

    public static function getPluralModelLabel(): string
    {
        return 'News';
    }

    public static function form(Schema $schema): Schema
    {
        return NewsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsTable::configure($table);
    }

    /**
     * @return array<class-string, class-string>
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * @return array<string, \Filament\Resources\Pages\PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListNews::route('/'),
            'create' => CreateNews::route('/create'),
            'edit' => EditNews::route('/{record}/edit'),
        ];
    }
}
```

- [ ] **Step 4: Pages schreiben (mit Slug-Auto-Fill)**

Create `app/Filament/Resources/News/Pages/ListNews.php`:

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    /**
     * @return array<int, \Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
```

Create `app/Filament/Resources/News/Pages/CreateNews.php`:

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Pages;

use App\Actions\News\GenerateNewsSlugAction;
use App\Filament\Resources\News\NewsResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['slug'])) {
            $data['slug'] = app(GenerateNewsSlugAction::class)->handle((string) $data['title']);
        }

        return $data;
    }
}
```

Create `app/Filament/Resources/News/Pages/EditNews.php`:

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources\News\Pages;

use App\Actions\News\GenerateNewsSlugAction;
use App\Filament\Resources\News\NewsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    /**
     * @return array<int, \Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['slug'])) {
            $data['slug'] = app(GenerateNewsSlugAction::class)->handle(
                (string) $data['title'],
                $this->record->getKey(),
            );
        }

        return $data;
    }
}
```

- [ ] **Step 5: Failing Filament-Tests schreiben**

Create `tests/Feature/Filament/NewsResourceTest.php`:

```php
<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Pages\ListNews;
use App\Models\News;
use App\Models\User;
use Livewire\Livewire;

test('the news list shows created articles', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $article = News::factory()->create(['title' => 'Big Launch']);

    $this->actingAs($admin);

    Livewire::test(ListNews::class)
        ->assertCanSeeTableRecords([$article])
        ->assertCanRenderTableColumn('title');
});

test('the create page renders the expected fields', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $this->actingAs($admin);

    Livewire::test(CreateNews::class)
        ->assertOk()
        ->assertFormFieldExists('title')
        ->assertFormFieldExists('description')
        ->assertFormFieldExists('image_path')
        ->assertFormFieldExists('content');
});

test('creating a news article auto-generates a slug when left empty', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    $this->actingAs($admin);

    Livewire::test(CreateNews::class)
        ->fillForm([
            'title' => 'Our Big Launch',
            'description' => 'A short summary of the launch.',
            'content' => '<p>Full content here.</p>',
            'published_at' => now(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(News::query()->where('slug', 'our-big-launch')->exists())->toBeTrue();
});

test('slug must be unique', function (): void {
    $admin = User::factory()->create(['role' => UserRole::SUPER_ADMIN]);
    News::factory()->create(['slug' => 'taken']);
    $this->actingAs($admin);

    Livewire::test(CreateNews::class)
        ->fillForm([
            'title' => 'Another',
            'slug' => 'taken',
            'description' => 'Summary.',
            'content' => '<p>Body.</p>',
        ])
        ->call('create')
        ->assertHasFormErrors(['slug' => 'unique']);
});
```

- [ ] **Step 6: Tests ausführen**

Run: `php artisan test --compact --filter=NewsResource`
Expected: 4 passed.

- [ ] **Step 7: Pint + Commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Filament/Resources/News tests/Feature/Filament/NewsResourceTest.php
git commit -m "feat: add Filament News resource with slug auto-generation"
```

---

## Task 4: Frontend-Pages (Übersicht + Detail mit SEO), Types, Footer

**Files:**
- Create: `resources/js/types/news.ts`
- Modify: `resources/js/types/index.ts`
- Create: `resources/js/pages/news/Index.vue`
- Create: `resources/js/pages/news/Show.vue`
- Modify: `resources/js/components/landing/LandingFooter.vue`

**Interfaces:**
- Consumes: Inertia-Props `entries` (Index) und `article` + `url` (Show) aus Task 2.
- Produces: `News`-TS-Type; funktionierende Übersicht + Detailseite; Footer-Link.

- [ ] **Step 1: TS-Type schreiben**

Create `resources/js/types/news.ts`:

```ts
export interface News {
    uuid: string;
    slug: string;
    title: string;
    description: string;
    image_url: string | null;
    content: string;
    published_at: string | null;
}
```

Modify `resources/js/types/index.ts` — Re-Export ergänzen (neben Changelog):

```ts
export type { News } from "./news";
```

- [ ] **Step 2: Übersichtsseite schreiben**

Create `resources/js/pages/news/Index.vue`:

```vue
<script setup lang="ts">
import { Head, InfiniteScroll, Link } from "@inertiajs/vue3";
import { Newspaper } from "@lucide/vue";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LogoMark from "@/components/Logo.vue";
import type { News } from "@/types";

defineOptions({ layout: "" });

defineProps<{
    entries: { data: News[] };
}>();

const formatDate = (iso: string | null): string => {
    if (!iso) return "";
    try {
        return new Date(iso).toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    } catch {
        return "";
    }
};
</script>

<template>
    <Head title="News — Peermitly">
        <meta
            name="description"
            content="Product news, announcements and stories from the Peermitly team."
            head-key="description"
        />
    </Head>

    <div class="bg-background text-foreground min-h-screen">
        <header
            class="mx-auto flex max-w-5xl items-center justify-between px-6 py-5"
        >
            <Link href="/" class="flex items-center gap-2.5">
                <LogoMark size="size-9" />
                <span class="text-lg font-semibold tracking-tight">Peermitly</span>
            </Link>
            <Link
                href="/login"
                class="text-muted-foreground hover:text-foreground text-sm"
                >Log in</Link
            >
        </header>

        <main class="mx-auto max-w-5xl px-6 py-16 md:py-24">
            <header class="mb-16 space-y-3">
                <p
                    class="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase"
                >
                    What's new
                </p>
                <h1
                    class="text-foreground text-4xl font-semibold tracking-tight md:text-5xl"
                >
                    News
                </h1>
                <p class="text-muted-foreground max-w-xl text-sm leading-6">
                    Product news, announcements and stories from the Peermitly
                    team.
                </p>
            </header>

            <InfiniteScroll
                v-if="entries.data.length"
                data="entries"
                only-next
                preserve-url
            >
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="entry in entries.data"
                        :key="entry.uuid"
                        :href="`/news/${entry.slug}`"
                        class="group border-border/60 bg-card hover:border-primary/40 flex flex-col overflow-hidden rounded-xl border transition-colors"
                    >
                        <div
                            class="bg-muted aspect-video w-full overflow-hidden"
                        >
                            <img
                                v-if="entry.image_url"
                                :src="entry.image_url"
                                :alt="entry.title"
                                loading="lazy"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                v-else
                                class="from-primary/20 to-primary/5 h-full w-full bg-gradient-to-br"
                                aria-hidden="true"
                            />
                        </div>
                        <div class="flex flex-1 flex-col gap-2 p-5">
                            <time
                                v-if="entry.published_at"
                                :datetime="entry.published_at"
                                class="text-muted-foreground text-xs"
                            >
                                {{ formatDate(entry.published_at) }}
                            </time>
                            <h2
                                class="text-foreground group-hover:text-primary text-lg font-semibold tracking-tight transition-colors"
                            >
                                {{ entry.title }}
                            </h2>
                            <p
                                class="text-muted-foreground line-clamp-3 text-sm leading-6"
                            >
                                {{ entry.description }}
                            </p>
                        </div>
                    </Link>
                </div>

                <template #loading>
                    <div
                        class="text-muted-foreground flex items-center justify-center gap-2 py-10 text-sm"
                    >
                        <span
                            class="border-primary size-4 animate-spin rounded-full border-2 border-t-transparent"
                            aria-hidden="true"
                        />
                        Loading more news…
                    </div>
                </template>
            </InfiniteScroll>

            <div
                v-else
                class="border-border/60 flex flex-col items-center gap-2 rounded-xl border py-16 text-center"
            >
                <Newspaper
                    class="text-muted-foreground size-8"
                    aria-hidden="true"
                />
                <p class="text-foreground text-sm font-medium">No news yet.</p>
                <p class="text-muted-foreground text-xs">
                    New articles will appear here as soon as they are published.
                </p>
            </div>
        </main>

        <LandingFooter />
    </div>
</template>
```

- [ ] **Step 3: Detailseite mit SEO-Meta schreiben**

Create `resources/js/pages/news/Show.vue`:

```vue
<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { ArrowLeft } from "@lucide/vue";
import LandingFooter from "@/components/landing/LandingFooter.vue";
import LogoMark from "@/components/Logo.vue";
import type { News } from "@/types";

defineOptions({ layout: "" });

const props = defineProps<{
    article: News;
    url: string;
}>();

const formatDate = (iso: string | null): string => {
    if (!iso) return "";
    try {
        return new Date(iso).toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    } catch {
        return "";
    }
};

const twitterCard = props.article.image_url
    ? "summary_large_image"
    : "summary";
</script>

<template>
    <Head :title="`${article.title} — Peermitly`">
        <meta
            name="description"
            :content="article.description"
            head-key="description"
        />
        <link rel="canonical" :href="url" head-key="canonical" />

        <meta property="og:type" content="article" head-key="og:type" />
        <meta property="og:site_name" content="Peermitly" head-key="og:site_name" />
        <meta property="og:title" :content="article.title" head-key="og:title" />
        <meta
            property="og:description"
            :content="article.description"
            head-key="og:description"
        />
        <meta property="og:url" :content="url" head-key="og:url" />
        <meta
            v-if="article.image_url"
            property="og:image"
            :content="article.image_url"
            head-key="og:image"
        />
        <meta
            v-if="article.published_at"
            property="article:published_time"
            :content="article.published_at"
            head-key="article:published_time"
        />

        <meta name="twitter:card" :content="twitterCard" head-key="twitter:card" />
        <meta
            name="twitter:title"
            :content="article.title"
            head-key="twitter:title"
        />
        <meta
            name="twitter:description"
            :content="article.description"
            head-key="twitter:description"
        />
        <meta
            v-if="article.image_url"
            name="twitter:image"
            :content="article.image_url"
            head-key="twitter:image"
        />
    </Head>

    <div class="bg-background text-foreground min-h-screen">
        <header
            class="mx-auto flex max-w-3xl items-center justify-between px-6 py-5"
        >
            <Link href="/" class="flex items-center gap-2.5">
                <LogoMark size="size-9" />
                <span class="text-lg font-semibold tracking-tight">Peermitly</span>
            </Link>
            <Link
                href="/login"
                class="text-muted-foreground hover:text-foreground text-sm"
                >Log in</Link
            >
        </header>

        <main class="mx-auto max-w-3xl px-6 py-12 md:py-16">
            <Link
                href="/news"
                class="text-muted-foreground hover:text-foreground mb-8 inline-flex items-center gap-1.5 text-sm"
            >
                <ArrowLeft class="size-4" aria-hidden="true" />
                All news
            </Link>

            <article class="space-y-6">
                <div class="space-y-3">
                    <time
                        v-if="article.published_at"
                        :datetime="article.published_at"
                        class="text-muted-foreground text-xs"
                    >
                        {{ formatDate(article.published_at) }}
                    </time>
                    <h1
                        class="text-foreground text-3xl font-semibold tracking-tight md:text-4xl"
                    >
                        {{ article.title }}
                    </h1>
                    <p class="text-muted-foreground text-lg leading-7">
                        {{ article.description }}
                    </p>
                </div>

                <img
                    v-if="article.image_url"
                    :src="article.image_url"
                    :alt="article.title"
                    class="max-h-[440px] w-full rounded-xl object-cover"
                />

                <div
                    class="prose prose-sm dark:prose-invert text-foreground/90 max-w-none [&_a]:text-primary [&_a]:underline [&_h1]:mt-4 [&_h1]:text-xl [&_h1]:font-semibold [&_h2]:mt-3 [&_h2]:text-lg [&_h2]:font-semibold [&_h3]:mt-2 [&_h3]:font-semibold [&_ol]:my-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:my-2 [&_p]:leading-6 [&_pre]:bg-muted [&_pre]:rounded-md [&_pre]:p-3 [&_pre]:text-xs [&_ul]:my-2 [&_ul]:list-disc [&_ul]:pl-5"
                    v-html="article.content"
                />
            </article>
        </main>

        <LandingFooter />
    </div>
</template>
```

- [ ] **Step 4: Footer-Link ergänzen**

Modify `resources/js/components/landing/LandingFooter.vue` — direkt vor dem Changelog-`<Link>` einfügen:

```vue
                    <Link
                        href="/news"
                        class="text-muted-foreground hover:text-foreground text-sm transition-colors"
                    >
                        News
                    </Link>
```

- [ ] **Step 5: Build prüfen**

Run: `npm run build`
Expected: Build erfolgreich, keine TS-/Vue-Fehler zu `news/Index.vue`, `news/Show.vue`, `news.ts`.

- [ ] **Step 6: Commit**

```bash
git add resources/js/types/news.ts resources/js/types/index.ts resources/js/pages/news resources/js/components/landing/LandingFooter.vue
git commit -m "feat: add News overview and SEO detail pages with footer link"
```

---

## Task 5: Storage-Link, Doku, Gesamtlauf

**Files:**
- Create: `docs/help/2026_07_16-news-section.md`

- [ ] **Step 1: Public-Storage-Link sicherstellen**

Run: `php artisan storage:link`
Expected: `The [public/storage] link has been connected...` oder `The links already exist.`

- [ ] **Step 2: Doku-Datei schreiben**

Create `docs/help/2026_07_16-news-section.md` (benutzerorientiert, deutsch):

```markdown
# News-Bereich

## Datum

2026-07-16

## Bereich

News, Startseite, Admin

## Kurzbeschreibung

Es gibt jetzt einen News-Bereich. Im Admin können Beiträge erstellt werden, im
Frontend erscheinen sie als Übersicht und als einzelne Detailseite.

## Was ist neu?

- Neue Seite „News" unter `/news` mit einer Karten-Übersicht (Bild, Titel,
  Kurzbeschreibung, Datum).
- Detailseite pro Beitrag unter `/news/<slug>` mit großem Bild und vollem Text.
- Neuer Admin-Bereich „News" (Gruppe „Content") zum Anlegen und Bearbeiten.
- Link „News" im Footer.

## Warum wurde das geändert?

Es fehlte eine Möglichkeit, Ankündigungen und Neuigkeiten ansprechend zu
veröffentlichen.

## Wie funktioniert es?

1. Im Admin „News" öffnen und „Create" klicken.
2. Titel, Kurzbeschreibung, optional ein Bild und den Textinhalt eingeben.
3. „Published at" setzen, damit der Beitrag öffentlich sichtbar wird. Ohne
   Datum bleibt der Beitrag ein Entwurf.
4. Der Link (Slug) wird automatisch aus dem Titel erzeugt, kann aber überschrieben
   werden.

## Betroffene Bereiche

- Frontend: `/news`, `/news/<slug>`, Footer
- Admin: News-Verwaltung

## Wichtige Hinweise

- Nur Beiträge mit gesetztem „Published at" erscheinen im Frontend.
- Das Bild ist optional; ohne Bild wird ein Farbverlauf angezeigt.
- Detailseiten enthalten SEO-Meta-Tags (Titel, Beschreibung, Open Graph, Twitter).
```

- [ ] **Step 3: Gesamten News-Testlauf**

Run: `php artisan test --compact --filter=News`
Expected: alle Tests grün (Slug-Action, NewsPage, NewsResource).

- [ ] **Step 4: Commit**

```bash
git add docs/help/2026_07_16-news-section.md
git commit -m "docs: add News section help page"
```

---

## Self-Review Notes

- **Spec coverage:** Model/Migration/Factory (T1), Slug-Action (T1), Controller/Resource/Routes + published-only + 404 (T2), Filament Form/Table/Pages + Slug-Autofill (T3), Übersicht+Detail+SEO-Meta+Footer+Types (T4), storage:link + Doku (T5). Alle Spec-Abschnitte abgedeckt.
- **Type-Konsistenz:** `image_path` (DB/Model/Filament) vs. `image_url` (Resource/TS/Vue) durchgängig. `GenerateNewsSlugAction::handle(string, ?int)` identisch in T1/T3. Props `entries`/`article`/`url` stimmen mit Controller überein.
- **Bekannte Prüfpunkte:** `@lucide/vue` Icon-Import (`Newspaper`, `ArrowLeft`) analog Changelog; falls Paketname im Repo `lucide-vue-next` o.ä., Import an Changelog anpassen — beim Build (T4 Step 5) sichtbar.