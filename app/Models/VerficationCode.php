// app/Models/VerificationCode.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class VerificationCode extends Model
{
    protected $fillable = ['email', 'code', 'type', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public static function generate(string $email, string $type = 'login'): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        static::where('email', $email)->where('type', $type)->delete();

        static::create([
            'email'      => $email,
            'code'       => $code,
            'type'       => $type,
            'expires_at' => now()->addMinutes(15),
        ]);

        return $code;
    }

    public static function verify(string $email, string $code, string $type = 'login'): bool
    {
        $record = static::where('email', $email)
            ->where('code', $code)
            ->where('type', $type)
            ->first();

        if (!$record) return false;

        if (now()->greaterThan($record->expires_at)) {
            $record->delete();
            return false;
        }

        $record->delete(); // one-time use
        return true;
    }
}
