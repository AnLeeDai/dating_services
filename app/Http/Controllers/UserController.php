<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    private array $users = [];

    private string $maleName;
    private string $femaleName;

    private string $maleAvatar;
    private string $femaleAvatar;

    private string $secretCode;

    private string $maleZodiacSign;
    private string $femaleZodiacSign;

    private string $maleFateNumber;
    private string $femaleFateNumber;

    public function __construct()
    {
        $this->maleName = 'Đại An';
        $this->femaleName = 'Khánh Duyên';

        $this->maleAvatar = 'https://i.pravatar.cc/300?img=1';
        $this->femaleAvatar = 'https://i.pravatar.cc/300?img=2';

        $this->secretCode = '12082025';

        $this->maleZodiacSign = 'Xử Nữ';
        $this->femaleZodiacSign = 'Song Ngư';

        $this->maleFateNumber = 'Thổ';
        $this->femaleFateNumber = 'Thủy';

        $this->initializeUsers();
    }

    private function initializeUsers()
    {
        $this->users = User::all()->toArray();

        if (!empty($this->users)) {
            return;
        }

        $this->createDefaultUsers();
    }

    private function createDefaultUsers()
    {

        $hashSecretCode = Hash::make($this->secretCode);

        $maleUser = User::create([
            'name' => $this->maleName,
            'avatar' => $this->maleAvatar,
            'secret_code' => $hashSecretCode,
            'zodiac_sign' => $this->maleZodiacSign,
            'fate_number' => $this->maleFateNumber,
        ]);

        $femaleUser = User::create([
            'name' => $this->femaleName,
            'avatar' => $this->femaleAvatar,
            'secret_code' => $hashSecretCode,
            'zodiac_sign' => $this->femaleZodiacSign,
            'fate_number' => $this->femaleFateNumber,
        ]);

        $this->users = [$maleUser->toArray(), $femaleUser->toArray()];
    }

    public function getUser()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Users retrieved successfully',
                'timestamp' => now(),
                'data' => $this->users
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved successfully',
            'timestamp' => now(),
            'data' => $users
        ]);
    }
}
