<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * عرض صفحة عن المنصة
     */
    public function about()
    {
        return view('about');
    }

    /**
     * عرض صفحة اتصل بنا
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * معالجة نموذج الاتصال
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // هنا يمكن إضافة منطق إرسال البريد الإلكتروني
        // Mail::to('info@tamsik.org')->send(new ContactMessage($request->all()));

        return back()->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
    }
}
