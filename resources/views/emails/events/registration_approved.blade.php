@component('mail::message')
# تمت الموافقة على تسجيلك!

مرحباً {{ $studentName }},

يسرنا إعلامك بأنه تمت الموافقة على طلب تسجيلك في فعالية: **{{ $eventName }}**.

**تفاصيل الفعالية:**
- **التاريخ والوقت:** {{ $eventDate }}

نتطلع لرؤيتك هناك!

شكراً,<br>
{{ config('app.name') }}
@endcomponent