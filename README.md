<div dir="rtl">

<div align="center">

<img src="docs/logo.webp" alt="Variza" width="150">

# پکیج لاراول واریزا

پکیج رسمی واریزا برای اتصال سریع و ساده برنامه‌های لاراول به سرویس پرداخت واریزا.

</div>

<div align="center">

![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Tests](https://img.shields.io/github/actions/workflow/status/the6fallenangel/variza-laravel/ci.yml?label=CI&style=flat-square&logo=github)
![Packagist Version](https://img.shields.io/packagist/v/the6fallenangel/variza-laravel?style=flat-square&logo=packagist)
![Packagist Downloads](https://img.shields.io/packagist/dt/the6fallenangel/variza-laravel?style=flat-square&logo=packagist)
![License](https://img.shields.io/github/license/the6fallenangel/variza-laravel?style=flat-square)

</div>

این پکیج ابزارهای لازم برای ایجاد لینک پرداخت، دریافت و اعتبارسنجی وب‌هوک‌ها و مدیریت رویدادهای پرداخت را در اختیار شما قرار می‌دهد و به‌صورت یکپارچه با اکوسیستم لاراول کار می‌کند.

---

## 📦 نصب

برای نصب پکیج، دستور زیر را اجرا کنید:

<div dir="ltr">

```bash
composer require the6fallenangel/variza-laravel
```

</div>

پس از نصب، فایل تنظیمات پکیج را با دستور زیر منتشر کنید:

<div dir="ltr">

```bash
php artisan vendor:publish --tag=variza-config
```

</div>

---

## ⚙️ پیکربندی

ابتدا کلید API و کلید امضای وب‌هوک واریزا را در فایل `.env` قرار دهید:

<div dir="ltr">

```env
VARIZA_API_TOKEN=your-api-token-here
VARIZA_WEBHOOK_SECRET=your-webhook-secret-here
```

</div>

برای دریافت این کلیدها:

1. وارد [پنل واریزا](https://variza.ir/panel/profile) شوید.
2. در بخش **پروفایل**، گزینه **کلید API** را پیدا کنید و یک کلید جدید بسازید.
3. در همان بخش، **کلید امضای وب‌هوک** را نیز دریافت کنید.

سپس آدرس وب‌هوک برنامه خود را از مسیر **پروفایل ← وب‌هوک** در پنل واریزا ثبت کنید:

<div dir="ltr">

```text
https://yourdomain.com/variza/webhook
```

</div>

---

## 🚀 ایجاد لینک پرداخت

با استفاده از Facade واریزا می‌توانید تنها با چند خط کد یک لینک پرداخت ایجاد کنید:

<div dir="ltr">

```php
use The6FallenAngel\VarizaLaravel\Facades\Variza;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLinkRequest;
use The6FallenAngel\VarizaLaravel\DTOs\Expiry;

$paymentLink = Variza::createPaymentLink(new PaymentLinkRequest(
    amount: 500000,                                    // مبلغ به تومان (حداقل 1000)
    returnUrl: route('order.callback'),                // آدرس بازگشت
    title: 'Order #123',                               // اختیاری
    cardLast4: '1234',                                 // اختیاری — انتخاب کارت مشخص
    expiresIn: Expiry::OneHour,                        // اختیاری — مدت اعتبار لینک
));

// انتقال کاربر به صفحه پرداخت
return redirect($paymentLink->payUrl);
```

</div>

### استفاده از Dependency Injection

در صورت تمایل می‌توانید به‌جای Facade، کلاینت واریزا را با Dependency Injection دریافت کنید:

<div dir="ltr">

```php
use The6FallenAngel\VarizaLaravel\VarizaClient;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLinkRequest;

class CheckoutController extends Controller
{
    public function __construct(private VarizaClient $variza)
    {
    }

    public function pay()
    {
        $paymentLink = $this->variza->createPaymentLink(
            new PaymentLinkRequest(
                amount: 500000,
                returnUrl: route('order.callback'),
            )
        );

        return redirect($paymentLink->payUrl);
    }
}
```

</div>

### مدت اعتبار لینک پرداخت

برای مشخص‌کردن مدت اعتبار لینک، می‌توانید از مقادیر آماده `Expiry` استفاده کنید:

| ثابت                    | مقدار   | توضیح      |
| ----------------------- | ------- | ---------- |
| `Expiry::ThirtyMinutes` | `30m`   | ۳۰ دقیقه   |
| `Expiry::OneHour`       | `1h`    | ۱ ساعت     |
| `Expiry::TwoHours`      | `2h`    | ۲ ساعت     |
| `Expiry::SixHours`      | `6h`    | ۶ ساعت     |
| `Expiry::OneDay`        | `1d`    | ۱ روز      |
| `Expiry::ThreeDays`     | `3d`    | ۳ روز      |
| `Expiry::OneWeek`       | `1w`    | ۱ هفته     |
| `Expiry::Never`         | `never` | بدون انقضا |

---

## 🔔 وب‌هوک و رویدادهای پرداخت

پس از تأیید موفق پرداخت، واریزا یک وب‌هوک به برنامه شما ارسال می‌کند. این پکیج به‌صورت خودکار مراحل زیر را انجام می‌دهد:

1. امضای وب‌هوک را با Middleware بررسی می‌کند.
2. رویداد `PaymentPaid` را اجرا می‌کند.
3. به شما اجازه می‌دهد این رویداد را با Listener یا Queue پردازش کنید.

### ایجاد Listener

برای ساخت Listener دستور زیر را اجرا کنید:

<div dir="ltr">

```bash
php artisan make:listener CompleteOrderPayment
```

</div>

سپس می‌توانید Listener را به شکل زیر پیاده‌سازی کنید:

<div dir="ltr">

```php
namespace App\Listeners;

use The6FallenAngel\VarizaLaravel\Events\PaymentPaid;
use App\Models\Order;

class CompleteOrderPayment
{
    public function handle(PaymentPaid $event): void
    {
        $payload = $event->payload;

        // پیدا کردن سفارش بر اساس slug
        $order = Order::where('payment_slug', $payload->slug)->first();

        if ($order && $order->status !== 'paid') {
            $order->update([
                'status' => 'paid',
                'payment_code' => $payload->attemptCode,
                'paid_amount' => $payload->amount,
                'paid_at' => now(),
            ]);

            // ارسال ایمیل، پیامک و سایر اقدامات موردنیاز
        }
    }
}
```

</div>

### ثبت Listener

Listener را در `EventServiceProvider` برنامه خود ثبت کنید:

<div dir="ltr">

```php
use The6FallenAngel\VarizaLaravel\Events\PaymentPaid;
use App\Listeners\CompleteOrderPayment;

protected $listen = [
    PaymentPaid::class => [
        CompleteOrderPayment::class,
    ],
];
```

</div>

### پردازش ناهمزمان با Queue

اگر پردازش رویداد زمان‌بر است، می‌توانید Listener را به‌صورت queued اجرا کنید:

<div dir="ltr">

```php
namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use The6FallenAngel\VarizaLaravel\Events\PaymentPaid;

class CompleteOrderPayment implements ShouldQueue
{
    public function handle(PaymentPaid $event): void
    {
        // پردازش‌های زمان‌بر
    }
}
```

</div>

### ⚠️ نکته مهم درباره وب‌هوک

پردازش وب‌هوک باید **Idempotent** باشد؛ یعنی اگر یک رویداد بیش از یک‌بار دریافت شد، نباید باعث ثبت دوباره پرداخت یا تغییر اشتباه وضعیت سفارش شود.

واریزا در صورت دریافت پاسخ نامعتبر یا در صورت عدم دریافت پاسخ موفق از سرور شما، رویداد را دوباره ارسال می‌کند. ارسال‌های مجدد با فاصله‌های ۳۰، ۶۰، ۱۸۰ و ۶۰۰ ثانیه انجام می‌شوند و هر رویداد حداکثر ۵ بار ارسال خواهد شد.

به همین دلیل، در Listener خود حتماً بررسی کنید که سفارش قبلاً پرداخت نشده باشد.

---

## 🚨 مدیریت خطاها

| وضعیت HTTP | استثنا                      | توضیح                       |
| ---------- | --------------------------- | --------------------------- |
| `422`      | `ValidationException`       | خطای اعتبارسنجی درخواست     |
| `429`      | `RateLimitException`        | عبور از محدودیت نرخ درخواست |
| سایر       | `ApiException`              | سایر خطاهای API             |
| —          | `InvalidSignatureException` | امضای وب‌هوک نامعتبر است    |

<div dir="ltr">

```php
use The6FallenAngel\VarizaLaravel\Facades\Variza;
use The6FallenAngel\VarizaLaravel\Exceptions\ValidationException;
use The6FallenAngel\VarizaLaravel\Exceptions\RateLimitException;

try {
    $paymentLink = Variza::createPaymentLink($request);
} catch (ValidationException $e) {
    // ورودی‌های نامعتبر
    logger()->error('Variza validation error', [
        'errors' => $e->errors,
        'status' => $e->status,
    ]);
} catch (RateLimitException $e) {
    // محدودیت نرخ درخواست
    return back()->with('error', 'لطفاً چند لحظه صبر کنید و دوباره تلاش کنید.');
} catch (\Exception $e) {
    // سایر خطاها
    logger()->error('Variza API error', ['message' => $e->getMessage()]);
}
```

</div>

---

## 🧪 تست

این پکیج همراه با مجموعه تست ارائه می‌شود. برای نصب وابستگی‌ها و اجرای تست‌ها:

<div dir="ltr">

```bash
composer install
composer test
```

</div>

---

## ⚙️ تنظیمات پیشرفته

برای مشاهده یا شخصی‌سازی تنظیمات پکیج، فایل `config/variza.php` را بررسی کنید:

<div dir="ltr">

```php
return [
    // کلید API واریزا
    'api_token' => env('VARIZA_API_TOKEN'),

    // کلید امضای وب‌هوک
    'webhook_secret' => env('VARIZA_WEBHOOK_SECRET'),

    // آدرس پایه API (معمولاً نیازی به تغییر ندارد)
    'base_url' => env('VARIZA_BASE_URL', 'https://variza.ir/api/v1'),

    // مسیر دریافت وب‌هوک
    'webhook_path' => env('VARIZA_WEBHOOK_PATH', 'variza/webhook'),

    // تایم‌اوت درخواست (بر حسب ثانیه)
    'timeout' => env('VARIZA_TIMEOUT', 30),
];
```

</div>

---

## 📚 مستندات و منابع

- [مستندات فنی واریزا](https://variza.ir/developers)
- [سؤالات متداول](https://variza.ir/faq)
- [پنل کاربری](https://variza.ir/panel)

---

## 🤝 مشارکت

برای گزارش باگ، درخواست قابلیت جدید یا پیشنهاد بهبود، از بخش [Issues](https://github.com/the6fallenangel/variza-laravel/issues) استفاده کنید.

---

## 📄 مجوز

این پکیج تحت مجوز **MIT** منتشر شده است.

برای آشنایی بیشتر با واریزا و سایر قابلیت‌های آن به [variza.ir](https://variza.ir) مراجعه کنید.

</div>

---

## 🇬🇧 English

**Variza Laravel Package** is the official Laravel package for connecting Laravel applications to the Variza payment service — create payment links, receive and verify webhooks, and handle payment events seamlessly integrated with the Laravel ecosystem.

### 📦 Installation

```bash
composer require the6fallenangel/variza-laravel
php artisan vendor:publish --tag=variza-config
```

### Configuration

Add your Variza credentials to `.env`:

```env
VARIZA_API_TOKEN=your-api-token-here
VARIZA_WEBHOOK_SECRET=your-webhook-secret-here
```

Register your webhook URL in the Variza panel:

```text
https://yourdomain.com/variza/webhook
```

### Create a payment link

```php
use The6FallenAngel\VarizaLaravel\Facades\Variza;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLinkRequest;
use The6FallenAngel\VarizaLaravel\DTOs\Expiry;

$paymentLink = Variza::createPaymentLink(new PaymentLinkRequest(
    amount: 500000,                     // amount in Toman (min 1000)
    returnUrl: route('order.callback'),
    title: 'Order #123',                // optional
    expiresIn: Expiry::OneHour,         // optional
));

return redirect($paymentLink->payUrl);
```

### Handle webhook events

Create a listener:

```bash
php artisan make:listener CompleteOrderPayment
```

```php
namespace App\Listeners;

use The6FallenAngel\VarizaLaravel\Events\PaymentPaid;
use App\Models\Order;

class CompleteOrderPayment
{
    public function handle(PaymentPaid $event): void
    {
        $order = Order::where('payment_slug', $event->payload->slug)->first();

        if ($order && $order->status !== 'paid') {
            $order->update([
                'status' => 'paid',
                'payment_code' => $event->payload->attemptCode,
                'paid_at' => now(),
            ]);
        }
    }
}
```

Register the listener in `EventServiceProvider`:

```php
use The6FallenAngel\VarizaLaravel\Events\PaymentPaid;
use App\Listeners\CompleteOrderPayment;

protected $listen = [
    PaymentPaid::class => [
        CompleteOrderPayment::class,
    ],
];
```

### Error handling

```php
use The6FallenAngel\VarizaLaravel\Exceptions\ValidationException;
use The6FallenAngel\VarizaLaravel\Exceptions\RateLimitException;

try {
    $paymentLink = Variza::createPaymentLink($request);
} catch (ValidationException $e) {
    // invalid input
} catch (RateLimitException $e) {
    // rate limited
}
```

### Testing

```bash
composer test
```

### Documentation

- [Variza Developer Docs](https://variza.ir/developers)
- [FAQ](https://variza.ir/faq)

### License

Released under the **MIT** license. Visit [variza.ir](https://variza.ir) to learn more.
