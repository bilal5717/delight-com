<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Global Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the global website.
    |
    */
    'company_details' => 'Chi tiết công ty',
    'company_logo' => 'Logo công ty',
    'company_name' => 'Tên công ty',
    'about_business' => 'Về công ty',
    'Describe what makes your ad unique...' => 'Miêu tả điều gì làm cho quảng cáo của bạn độc đáo...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => 'Wechat',
    'phone' => 'Điện thoại',
    'website' => 'Trang web',
    'business_category' => 'Danh mục kinh doanh',
    'company_size' => 'Kích thước công ty (doanh thu)',
    'registration_number' => 'Số đăng ký công ty',
    'submit' => 'Gửi',

    'company_payment_details' => 'Chi tiết thanh toán công ty',
    // 'bank_account_number' => 'Số tài khoản ngân hàng',
    // 'confirm_bank_account_number' => 'Xác nhận số tài khoản ngân hàng',
    // 'bank_account_holder_name' => 'Tên chủ tài khoản ngân hàng',
    'payment_details' => 'Chi tiết thanh toán',

    'add_new_address' => 'Thêm địa chỉ mới',
    'company_address' => 'Địa chỉ công ty',
    'Set as default address' => 'Đặt làm địa chỉ mặc định',
    'address' => 'Địa chỉ',
    'company_city' => 'Thành phố',
    'company_state' => 'Tiểu bang',
    'company_pincode' => 'Mã pin hoặc số',
    'company_country' => 'Quốc gia',
    'default_address' => 'Địa chỉ mặc định',
    'action' => 'Hành động',
    'Oops...' => 'Rất tiếc...',
    'Are you sure you want to delete this address?' => 'Bạn có chắc chắn muốn xóa địa chỉ này?',
    'You wont be able to revert this !' => 'Bạn sẽ không thể hoàn tác hành động này!',
    'You can not delete default selected address!' => 'Bạn không thể xóa địa chỉ mặc định đã chọn!',
    'Yes, delete it!' => 'Có, xóa nó!',

    'edit_company_details' => 'Chỉnh sửa thông tin công ty',
    'view_company_details' => 'Xem thông tin công ty',
    'company_information' => 'Thông tin công ty',
    'about' => 'Giới thiệu',
    'email' => 'Email',
    'company_email' => 'Email công ty',
    'user_information' => 'Thông tin người dùng',
    
    //company payment
    'select_currency' => 'Chọn đơn vị tiền tệ',
    'add_payment_detail' => 'Thêm chi tiết thanh toán',
    'currently_not_available' => 'Hiện tại chưa có sẵn cho đơn vị tiền tệ này',
    'company_payment_list' => 'Danh sách thanh toán công ty',
    'account_holder_name' => 'Tên chủ tài khoản',
    'account_number' => 'Số tài khoản',
    'country' => 'Quốc gia',
    'default_payment' => 'Thanh toán mặc định',
    'Are you sure you want to delete this Payment?' => 'Bạn có chắc chắn muốn xóa Thanh toán này không?',
    'You can not delete default selected Payment!' => 'Bạn không thể xóa Thanh toán được chọn mặc định!',
    'Set as default Payment' => 'Đặt làm Thanh toán mặc định',
    'invalid_price' => 'Giá tiền bạn nhập phải là một giá hợp lệ hoặc không thấp hơn :price_currency',
    'can_not_use_bad_word_in_description' => 'Không thể sử dụng từ xấu <b>:badword</b> trong mô tả!',
    
    'description_length_not_greater_then' => 'Vui lòng lưu ý rằng chúng tôi tin rằng độ dài tối đa của mô tả là ' . config('settings.single.max_word_description') . ' từ, là tối ưu để đảm bảo trải nghiệm người dùng tích cực và tối ưu hóa quảng cáo của bạn cho SEO.',
    'description_length_not_less_then' => 'Vui lòng lưu ý rằng chúng tôi tin rằng độ dài tối thiểu của mô tả là ' . config('settings.single.min_word_description') . ' từ, là tối ưu để đảm bảo trải nghiệm người dùng tích cực và tối ưu hóa quảng cáo của bạn cho SEO.',
    'address_stored_successfully' => 'Địa chỉ công ty được thêm thành công...',
    'address_updated_successfully' => 'Địa chỉ công ty được cập nhật thành công...',
    'company_profile_updated' => 'Cập nhật hồ sơ công ty thành công...',
    'payment_details_submitted_successfully' => 'Thông tin thanh toán của công ty đã được gửi thành công',

    'payment_details_updated_successfully' => 'Thông tin thanh toán của công ty đã được tạo hoặc cập nhật thành công',
    'min_max_word_description' => 'Mô tả phải có ít nhất ' . config('settings.single.min_word_description') . ' từ và không quá ' . config('settings.single.max_word_description') . ' từ',
    'title_character_limit' => 'Giới hạn số ký tự tiêu đề',
    'title_length_not_greater_then' => 'Để đảm bảo tiêu đề quảng cáo của bạn rõ ràng, cung cấp thông tin và dễ đọc, và tối ưu hóa cho SEO, chúng tôi đã thực hiện một yêu cầu giới hạn số ký tự tối đa. Hiện tại, giới hạn số ký tự tối đa là không quá ' . config('settings.single.title_character_limit') . ' ký tự',
    'title_character_minimum_limit' => 'Giới hạn số ký tự tối thiểu của tiêu đề',
    'title_length_not_less_then' => 'Để đảm bảo tiêu đề quảng cáo của bạn rõ ràng, cung cấp thông tin và dễ đọc, và tối ưu hóa cho SEO, chúng tôi đã thực hiện yêu cầu giới hạn số ký tự tối thiểu. Hiện tại, giới hạn số ký tự tối thiểu là không ít hơn ' . config('settings.single.title_character_minimum_limit') . ' ký tự',
    'max_word_description' => 'Mô tả chỉ cho phép tối đa 500 từ',
    'about_company' => 'Về công ty',
    'company_description_length_not_greater_then' => 'Độ dài mô tả không được vượt quá 500 từ!',

    'primary_email' => 'Email chính',
    'upgrade_post_package' => 'Nâng cấp Gói quảng cáo',
    'default_business_type' => 'Loại hình doanh nghiệp mặc định',
    'information' => 'Thông tin',
    'enter_information' => 'Nhập thông tin',

    'business_social_media' => 'Liên kết trang web mạng xã hội của doanh nghiệp này:',
    'You will receive your payments on your default payment details' => 'Bạn sẽ nhận được thanh toán trên thông tin thanh toán mặc định của bạn'
];
