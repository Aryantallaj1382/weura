
    <h1>صفحه اطلاعات کاربر</h1>
    <span>api/v1/profile --->برای گرفتن اطلاعات  GET</span><br><br>
    <span>api/v1/profile/update  ->  ویرایش اطلاعات   POST</span><br><br><br>
    <span>کلید ها:<br>
               'name'                  => 'nullable|string|max:255',<br>
            'email'                 => 'nullable|email|max:255',<br>
            'profile'               => 'nullable|file', <br>
            'notify_new_chapter'    => 'nullable|boolean',<br>
            'notify_user_referral'  => 'nullable|boolean',<br>
            'notify_promotions'     => 'nullable|boolean',<br>
            'notify_donation'       => 'nullable|boolean',<br>
    </span><br><br><br>

    <span>api/v1/profile/change-password -> تعییر رمز عبور  POST</span><br><br><br>
    <span>  'old_password' => 'required',<br>
            'password' => 'required',<br>
    </span>
        <br>
        <br>
        <br>
<span>-------------------------------------------------------------------------------------------</span>
    <h1>صفحه ی تیکت</h1>
    <span>api/v1/user_ticket  -> لیست تیکت ها   GET</span><br><br><br>
    <span>api/v1/user_ticket/show/{id} -> نمایش پیام های هر تیکت   GET</span><br><br>
    <span>api/v1/user_ticket/store  -> ثبت تیکت جدید   POST</span><br>
    <span>کلید ها:<br>
                'message_text' => 'required',<br>
            'issue_type' => 'required', ['technical', 'billing', 'general', 'other']<br>
    </span>

    <br>
    <span>api/v1/user_ticket/update/{id}--> پیام در چت  POST</span>
    <br>
    'message_text' => 'required',

    <br>
    <br>
    <br>
    <span>-------------------------------------------------------------------------------------------</span>
    <br>
    <span>api/v1/dashboard   داشبورد</span>
    <br>
    <span>-------------------------------------------------------------------------------------------</span>
    <br>
    <span>api/v1/library  کتابخانه لیست خوانده شده ها</span>
    <br>
    <br>
    <span>api/v1/library/completed    تکمیل شده ها</span>
    <br>
    <br>
    <span>api/v1/library/liked   لایک شده ها</span>
    <br>
    <br>
    <span>api/v1/library/delete    حذف شده ها</span>
    <br>
    <br>
    <span>api/v1/library/deleteOrLiked/{id}   حذف یا لایک  POST</span>

