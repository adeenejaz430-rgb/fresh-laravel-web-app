// app/Services/VerificationService.php
namespace App\Services;

use App\Mail\VerificationCodeMail;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;

class VerificationService
{
    public function send(string $email, string $type = 'login'): bool
    {
        $code = VerificationCode::generate($email, $type);

        try {
            Mail::to($email)->send(new VerificationCodeMail($code, $type));
            return true;
        } catch (\Throwable $e) {
            \Log::error('Error sending verification email', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function verify(string $email, string $code, string $type = 'login'): bool
    {
        return VerificationCode::verify($email, $code, $type);
    }
}
