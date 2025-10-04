<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
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
            'secret_code' => $hashSecretCode,
            'zodiac_sign' => $this->maleZodiacSign,
            'fate_number' => $this->maleFateNumber,
        ]);

        $femaleUser = User::create([
            'name' => $this->femaleName,
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

    public function getUserById($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'User retrieved successfully',
                'timestamp' => now(),
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Delete old avatar file from storage
     */
    private function deleteOldAvatar($avatarUrl)
    {
        if (!$avatarUrl || str_contains($avatarUrl, 'pravatar.cc')) {
            return false; // Skip default avatars
        }

        try {
            // Extract path from URL, handle both full URLs and relative paths
            $parsedUrl = parse_url($avatarUrl);
            $oldAvatarPath = isset($parsedUrl['path']) ? $parsedUrl['path'] : $avatarUrl;

            // Remove '/storage/' prefix if present
            $oldAvatarPath = ltrim($oldAvatarPath, '/');
            if (str_starts_with($oldAvatarPath, 'storage/')) {
                $oldAvatarPath = substr($oldAvatarPath, 8); // Remove 'storage/' prefix
            }

            // Check if file exists and delete it
            if (Storage::disk('public')->exists($oldAvatarPath)) {
                $deleted = Storage::disk('public')->delete($oldAvatarPath);
                Log::info("Old avatar deleted: " . $oldAvatarPath . " - Success: " . ($deleted ? 'Yes' : 'No'));
                return $deleted;
            } else {
                Log::warning("Old avatar file not found: " . $oldAvatarPath);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error deleting old avatar: " . $e->getMessage());
            return false;
        }
    }

    public function uploadAvatar(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Find the user
            $user = User::findOrFail($request->user_id);

            // Delete old avatar before uploading new one
            $oldAvatarDeleted = $this->deleteOldAvatar($user->avatar);

            // Store the uploaded file
            $avatarFile = $request->file('avatar');
            $avatarName = time() . '_' . $user->id . '_' . $avatarFile->getClientOriginalName();
            $avatarPath = $avatarFile->storeAs('avatars', $avatarName, 'public');

            // Generate the public URL
            $avatarUrl = asset('storage/' . $avatarPath);

            // Update user's avatar in database
            $user->update(['avatar' => $avatarUrl]);

            return response()->json([
                'status' => 'success',
                'message' => 'Avatar uploaded successfully',
                'data' => [
                    'user_id' => $user->id,
                    'avatar_url' => $avatarUrl,
                    'avatar_path' => $avatarPath,
                    'old_avatar_deleted' => $oldAvatarDeleted
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload avatar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
