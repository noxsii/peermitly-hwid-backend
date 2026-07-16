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

    /**
     * A rich, real-world article exercising every styled content element.
     */
    public function showcase(): self
    {
        return $this->state(fn (): array => [
            'content' => $this->showcaseContent(),
            'image_path' => null,
        ]);
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

    private function showcaseContent(): string
    {
        return <<<'HTML'
<p>The new spoofing engine is a full rewrite. It is faster, harder to detect and finally
gives you per-profile hardware identities you can switch between in a single click. Here
is everything that shipped.</p>

<h2>What changed</h2>
<p>We replaced the old registry-only approach with a layered driver that intercepts
identifier reads at multiple levels. In practice that means a spoofed <code>HWID</code>
now stays consistent across reboots and survives most anti-cheat re-scans.</p>

<ul>
<li><strong>Per-profile identities</strong> — every profile gets its own stable machine fingerprint.</li>
<li><strong>One-click switching</strong> — swap identities without a reboot.</li>
<li><mark>Cleaner uninstall</mark> — every change is fully reversible.</li>
</ul>

<h3>Rolling out a spoof</h3>
<ol>
<li>Pick a profile in the dashboard.</li>
<li>Hit <strong>Apply</strong> and wait for the confirmation toast.</li>
<li>Launch your game — the new identity is already live.</li>
</ol>

<blockquote>
<p>Heads up: always apply a spoof <em>before</em> launching the target application, never
while it is running.</p>
</blockquote>

<h2>For developers</h2>
<p>The public API now exposes the spoof lifecycle directly. Check the current identity
with a single call:</p>

<pre><code>curl -X POST https://api.peermitly.com/v2/hwid/apply \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"profile":"gaming-01","persist":true}'</code></pre>

<p>The response returns the freshly generated identity and its time-to-live:</p>

<pre><code>{
  "profile": "gaming-01",
  "hwid": "PRMT-9F2A-4E11-XR07",
  "persisted": true,
  "ttl_seconds": 86400
}</code></pre>

<h3>Endpoint overview</h3>
<table>
<thead>
<tr><th>Method</th><th>Endpoint</th><th>Purpose</th></tr>
</thead>
<tbody>
<tr><td><code>POST</code></td><td>/v2/hwid/apply</td><td>Apply a profile identity</td></tr>
<tr><td><code>GET</code></td><td>/v2/hwid/current</td><td>Read the active identity</td></tr>
<tr><td><code>POST</code></td><td>/v2/hwid/reset</td><td>Restore the real hardware IDs</td></tr>
</tbody>
</table>

<hr>

<p>Questions or something looks off? Reach us any time — and thanks for testing the beta.
<a href="/guide">Read the full guide</a> for advanced configuration.</p>
HTML;
    }
}
