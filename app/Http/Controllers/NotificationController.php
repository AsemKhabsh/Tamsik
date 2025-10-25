<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض جميع الإشعارات
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        $unreadCount = Auth::user()->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * الحصول على الإشعارات غير المقروءة (للـ dropdown)
     */
    public function unread()
    {
        $notifications = Auth::user()->unreadNotifications()->take(5)->get();
        $unreadCount = Auth::user()->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // إعادة التوجيه إلى رابط الإشعار إذا كان موجوداً
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        // إذا كان الإشعار عن فتوى ولا يوجد url، نبني الرابط
        if (isset($notification->data['fatwa_id'])) {
            return redirect()->route('fatwas.show', $notification->data['fatwa_id']);
        }

        return redirect()->route('notifications.index')->with('info', 'تم تحديد الإشعار كمقروء');
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
    }

    /**
     * حذف إشعار
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'تم حذف الإشعار بنجاح');
    }
}
