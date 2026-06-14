# 🛠️ SKILLS — TitikAman

Dokumen ini berisi panduan teknis implementasi (skill) yang digunakan developer/agent dalam membangun proyek TitikAman. Setiap skill dilengkapi dengan contoh kode siap pakai yang sesuai konvensi proyek ini.

---

## 📦 BACKEND SKILLS (Laravel 12)

---

### SKILL-BE-01: Membuat Service Class

Semua logika bisnis ditulis di dalam Service class, bukan di Controller.

```php
// app/Services/SosRequestService.php
namespace App\Services;

use App\Models\SosRequest;
use App\Events\SosReceived;

class SosRequestService
{
    public function store(array $data, int $userId): SosRequest
    {
        $sos = SosRequest::create([
            'sender_id'      => $userId,
            'latitude'       => $data['latitude'],
            'longitude'      => $data['longitude'],
            'people_trapped' => $data['people_trapped'],
            'elderly_count'  => $data['elderly_count'] ?? 0,
            'infant_count'   => $data['infant_count'] ?? 0,
            'pregnant_count' => $data['pregnant_count'] ?? 0,
            'description'    => $data['description'] ?? null,
        ]);

        // Broadcast ke relawan terdekat via WebSocket
        broadcast(new SosReceived($sos))->toOthers();

        return $sos;
    }
}
```

---

### SKILL-BE-02: Membuat Form Request

Semua validasi input masuk melalui Form Request — tidak langsung di Controller.

```php
// app/Http/Requests/StoreSosRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'latitude'       => 'required|numeric|between:-90,90',
            'longitude'      => 'required|numeric|between:-180,180',
            'people_trapped' => 'required|integer|min:1|max:100',
            'elderly_count'  => 'nullable|integer|min:0',
            'infant_count'   => 'nullable|integer|min:0',
            'pregnant_count' => 'nullable|integer|min:0',
            'description'    => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.between'  => 'Koordinat latitude tidak valid.',
            'longitude.between' => 'Koordinat longitude tidak valid.',
        ];
    }
}
```

---

### SKILL-BE-03: Membuat API Resource

Semua response API JSON harus menggunakan Resource — tidak return model langsung.

```php
// app/Http/Resources/SosRequestResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SosRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'status'         => $this->status,
            'location'       => [
                'latitude'  => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'victims'        => [
                'total'    => $this->people_trapped,
                'elderly'  => $this->elderly_count,
                'infant'   => $this->infant_count,
                'pregnant' => $this->pregnant_count,
            ],
            'is_priority'    => $this->elderly_count > 0 || $this->infant_count > 0 || $this->pregnant_count > 0,
            'description'    => $this->description,
            'created_at'     => $this->created_at->toISOString(),
        ];
    }
}
```

---

### SKILL-BE-04: Haversine Formula (Cari Posko Terdekat)

Mencari shelter terdekat dari koordinat korban SOS menggunakan query Haversine.

```php
// Di dalam ShelterRepository atau ShelterService
use App\Models\Shelter;
use Illuminate\Support\Collection;

public function findNearest(float $lat, float $lng, int $limit = 5): Collection
{
    return Shelter::selectRaw("
            *,
            (6371 * acos(
                cos(radians(?)) * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?)) * sin(radians(latitude))
            )) AS distance_km
        ", [$lat, $lng, $lat])
        ->where('status', 'active')
        ->having('distance_km', '<=', 10) // radius 10km
        ->orderBy('distance_km')
        ->limit($limit)
        ->get();
}
```

---

### SKILL-BE-05: Broadcast Event (Laravel Reverb)

Mengirim event real-time ke client saat ada SOS masuk.

```php
// app/Events/SosReceived.php
namespace App\Events;

use App\Models\SosRequest;
use App\Http\Resources\SosRequestResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SosReceived implements ShouldBroadcast
{
    public function __construct(public SosRequest $sos) {}

    public function broadcastOn(): Channel
    {
        // Semua relawan aktif mendengarkan channel ini
        return new Channel('sos.alerts');
    }

    public function broadcastWith(): array
    {
        return (new SosRequestResource($this->sos))->resolve();
    }

    public function broadcastAs(): string
    {
        return 'sos.new';
    }
}
```

---

### SKILL-BE-06: Job Queue (Kirim FCM Push Notification)

Mengirim push notification ke HP warga menggunakan Job agar tidak memblokir request.

```php
// app/Jobs/SendFloodAlertNotification.php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFloodAlertNotification implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;
    public array $backoff = [10, 60, 300];

    public function __construct(
        private string $fcmToken,
        private string $title,
        private string $body,
        private array $data = []
    ) {}

    public function handle(): void
    {
        // Implementasi kirim FCM di sini
        // Gunakan HTTP client ke Firebase FCM v1 API
    }
}

// Cara dispatch:
SendFloodAlertNotification::dispatch($user->fcm_token, 'Siaga Banjir!', 'TMA di Pintu Air Bekasi naik ke Siaga 1.')->afterResponse();
```

---

### SKILL-BE-07: Policy (Otorisasi Berbasis Peran)

Menggunakan Laravel Policy untuk otorisasi — tidak cek role manual di Controller.

```php
// app/Policies/FloodReportPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\FloodReport;

class FloodReportPolicy
{
    public function verify(User $user, FloodReport $report): bool
    {
        // Hanya admin BPBD yang boleh memverifikasi laporan
        return $user->hasRole('admin');
    }

    public function delete(User $user, FloodReport $report): bool
    {
        // Hanya admin atau pemilik laporan sendiri
        return $user->hasRole('admin') || $user->id === $report->reporter_id;
    }
}

// Cara pakai di Controller:
// $this->authorize('verify', $floodReport);
```

---

### SKILL-BE-08: Upload & Kompres Foto (Intervention Image)

Memvalidasi, mengompres, dan menyimpan foto bukti laporan banjir.

```php
// Di dalam FloodReportService
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;

public function storePhoto(UploadedFile $file): string
{
    $filename = 'flood_' . uniqid() . '.webp';
    $path = storage_path('app/public/flood-reports/' . $filename);

    Image::make($file)
        ->resize(1280, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })
        ->encode('webp', 80) // Konversi ke WebP, kualitas 80%
        ->save($path);

    return 'flood-reports/' . $filename;
}
```

---

## 🎨 FRONTEND SKILLS (Blade + Leaflet.js)

---

### SKILL-FE-01: Inisialisasi Peta Leaflet dengan CartoDB Tiles

```javascript
// resources/js/map.js

const map = L.map('map', {
    center: [-6.2383, 106.9756], // Kota Bekasi
    zoom: 13,
    zoomControl: false,
});

// CartoDB Voyager — terang, modern, bersih
L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
    attribution: '© OpenStreetMap contributors © CARTO',
    subdomains: 'abcd',
    maxZoom: 19,
}).addTo(map);

// Custom zoom control di pojok kanan bawah
L.control.zoom({ position: 'bottomright' }).addTo(map);
```

---

### SKILL-FE-02: Custom SVG Marker (Bukan Default Leaflet)

```javascript
// resources/js/markers.js

const createSosMarker = (isPriority = false) => L.divIcon({
    className: '',
    html: `
        <div class="sos-marker ${isPriority ? 'sos-marker--priority' : ''}">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
    `,
    iconSize: [40, 40],
    iconAnchor: [20, 40],
});
```

---

### SKILL-FE-03: Listen Real-Time Event via Laravel Echo

```javascript
// resources/js/echo-listeners.js

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

// Dengarkan SOS baru
window.Echo.channel('sos.alerts')
    .listen('.sos.new', (event) => {
        // Tampilkan toast notifikasi
        showToast(`🚨 SOS masuk: ${event.victims.total} orang terjebak`, 'danger');

        // Tambahkan marker baru di peta tanpa reload
        const marker = createSosMarker(event.is_priority);
        L.marker([event.location.latitude, event.location.longitude], { icon: marker })
            .addTo(map)
            .bindPopup(`<b>SOS!</b><br>${event.description ?? 'Butuh evakuasi segera.'}`);
    });
```

---

### SKILL-FE-04: Blade Component (Contoh: Alert Card)

```php
{{-- resources/views/components/alert-card.blade.php --}}
@props(['type' => 'info', 'title', 'message'])

@php
    $classes = match($type) {
        'danger'  => 'border-red-500 bg-red-50 text-red-800',
        'warning' => 'border-amber-500 bg-amber-50 text-amber-800',
        'success' => 'border-green-500 bg-green-50 text-green-800',
        default   => 'border-blue-500 bg-blue-50 text-blue-800',
    };
@endphp

<div class="border-l-4 rounded-r-lg p-4 {{ $classes }}" role="alert">
    <p class="font-semibold text-sm">{{ $title }}</p>
    <p class="text-sm mt-1">{{ $message }}</p>
</div>

{{-- Cara pakai di Blade: --}}
{{-- <x-alert-card type="danger" title="Siaga 1!" message="TMA Pintu Air Bekasi melebihi ambang batas." /> --}}
```

---

## 🧪 TESTING SKILLS

---

### SKILL-TEST-01: Feature Test API Endpoint

```php
// tests/Feature/SosRequestTest.php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SosRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_send_sos(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/sos', [
            'latitude'       => -6.2383,
            'longitude'      => 106.9756,
            'people_trapped' => 3,
            'elderly_count'  => 1,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => ['id', 'status', 'location', 'victims', 'is_priority']]);

        $this->assertDatabaseHas('sos_requests', ['sender_id' => $user->id]);
    }

    public function test_guest_cannot_send_sos(): void
    {
        $this->postJson('/api/sos', ['latitude' => -6.2383, 'longitude' => 106.9756])
             ->assertStatus(401);
    }
}
```
