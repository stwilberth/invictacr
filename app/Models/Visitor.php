<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    public const COOKIE_NAME = 'invicta_visitor';
    public const CONSENT_COOKIE = 'invicta_consent';

    protected $fillable = [
        'uuid',
        'user_id',
        'ip',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'referrer',
        'landing_page',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'name',
        'email',
        'phone',
        'whatsapp_clicked_at',
        'visits_count',
        'pageviews_count',
        'total_time_seconds',
        'first_seen_at',
        'last_seen_at',
    ];

    protected $casts = [
        'whatsapp_clicked_at' => 'datetime',
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'visits_count' => 'integer',
        'pageviews_count' => 'integer',
        'total_time_seconds' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(VisitorEvent::class);
    }

    public function productEvents(): HasMany
    {
        return $this->hasMany(VisitorEvent::class)->whereNotNull('product_id');
    }

    /**
     * Vincula este perfil de visitante con un usuario registrado.
     */
    public function linkToUser(User $user, ?string $phone = null): void
    {
        $this->fill([
            'user_id' => $user->id,
            'name' => $this->name ?: $user->name,
            'email' => $this->email ?: $user->email,
            'phone' => $this->phone ?: ($phone ?: $user->phone),
        ]);

        $this->save();
    }

    /**
     * Busca el visitante actual por la cookie del request.
     */
    public static function currentFromRequest(\Illuminate\Http\Request $request): ?self
    {
        $uuid = $request->cookies->get(self::COOKIE_NAME);

        if (!$uuid || !is_string($uuid) || strlen($uuid) > 36) {
            return null;
        }

        return static::where('uuid', $uuid)->first();
    }

    /**
     * Crea un nuevo perfil de visitante a partir del request actual.
     */
    public static function createFromRequest(\Illuminate\Http\Request $request): self
    {
        $ua = (string) $request->userAgent();
        $now = now();

        return static::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $request->user()?->id,
            'ip' => $request->header('CF-Connecting-IP') ?: $request->ip(),
            'user_agent' => \Illuminate\Support\Str::limit($ua, 1000, ''),
            'device_type' => static::parseDeviceType($ua),
            'browser' => static::parseBrowser($ua),
            'platform' => static::parsePlatform($ua),
            'referrer' => \Illuminate\Support\Str::limit((string) $request->headers->get('referer'), 1000, '') ?: null,
            'landing_page' => \Illuminate\Support\Str::limit($request->fullUrl(), 1000, ''),
            'utm_source' => $request->query('utm_source'),
            'utm_medium' => $request->query('utm_medium'),
            'utm_campaign' => $request->query('utm_campaign'),
            'name' => $request->user()?->name,
            'email' => $request->user()?->email,
            'visits_count' => 1,
            'first_seen_at' => $now,
            'last_seen_at' => $now,
        ]);
    }

    public static function isBot(string $ua): bool
    {
        return (bool) preg_match(
            '/bot|crawl|slurp|spider|mediapartners|facebookexternalhit|whatsapp|telegrambot|preview|headless|lighthouse|pingdom|uptime|curl|wget|python-requests|go-http-client/i',
            $ua
        );
    }

    public static function parseDeviceType(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|iemobile|wpdesktop/i', $ua) => 'mobile',
            (bool) preg_match('/tablet|ipad|playbook|silk/i', $ua) => 'tablet',
            default => 'desktop',
        };
    }

    public static function parseBrowser(string $ua): ?string
    {
        return match (true) {
            stripos($ua, 'Edg') !== false => 'Edge',
            stripos($ua, 'OPR') !== false || stripos($ua, 'Opera') !== false => 'Opera',
            stripos($ua, 'Chrome') !== false => 'Chrome',
            stripos($ua, 'Safari') !== false => 'Safari',
            stripos($ua, 'Firefox') !== false => 'Firefox',
            stripos($ua, 'MSIE') !== false || stripos($ua, 'Trident') !== false => 'IE',
            default => null,
        };
    }

    public static function parsePlatform(string $ua): ?string
    {
        return match (true) {
            (bool) preg_match('/iphone|ipad|ipod/i', $ua) => 'iOS',
            stripos($ua, 'Android') !== false => 'Android',
            stripos($ua, 'Windows') !== false => 'Windows',
            stripos($ua, 'Mac OS') !== false => 'macOS',
            stripos($ua, 'Linux') !== false => 'Linux',
            default => null,
        };
    }

    public function getTotalTimeHumanAttribute(): string
    {
        $seconds = $this->total_time_seconds;

        if ($seconds < 60) {
            return $seconds . 's';
        }

        $minutes = intdiv($seconds, 60);
        if ($minutes < 60) {
            return $minutes . 'm';
        }

        $hours = intdiv($minutes, 60);
        $minutes = $minutes % 60;

        return $hours . 'h ' . $minutes . 'm';
    }

    public function getDeviceIconAttribute(): string
    {
        return match ($this->device_type) {
            'mobile' => 'fa-mobile-screen',
            'tablet' => 'fa-tablet-screen-button',
            default => 'fa-desktop',
        };
    }
}
