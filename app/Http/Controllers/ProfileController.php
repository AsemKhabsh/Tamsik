<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * عرض صفحة الملف الشخصي
     */
    public function index()
    {
        $user = Auth::user();
        
        // إحصائيات المستخدم
        $stats = [
            'sermons_count' => $user->sermons()->count(),
            'articles_count' => $user->articles()->count(),
            'lectures_count' => $user->lectures()->count(),
            'fatwas_count' => $user->fatwas()->count(),
            'favorites_count' => $user->favorites()->count(),
        ];

        return view('profile.index', compact('user', 'stats'));
    }

    /**
     * تحديث معلومات الملف الشخصي
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'specialization' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // معالجة رفع الصورة الشخصية
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة إن وجدت
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // رفع الصورة الجديدة
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        // تحديث البيانات
        $user->update($validated);

        return redirect()->route('profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * تغيير كلمة المرور
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        // تحديث كلمة المرور
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    /**
     * حذف الصورة الشخصية
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return redirect()->route('profile')->with('success', 'تم حذف الصورة الشخصية بنجاح');
    }
}

