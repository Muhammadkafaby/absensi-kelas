<?php

/**
 * Validation language strings (Bahasa Indonesia)
 */
return [
    // Core Messages
    'accepted'             => '{field} harus diterima.',
    'active_url'           => '{field} bukan URL yang valid.',
    'alpha'                => '{field} hanya boleh berisi huruf.',
    'alpha_dash'           => '{field} hanya boleh berisi huruf, angka, strip, dan underscore.',
    'alpha_numeric'        => '{field} hanya boleh berisi huruf dan angka.',
    'alpha_numeric_space'  => '{field} hanya boleh berisi huruf, angka, dan spasi.',
    'alpha_space'          => '{field} hanya boleh berisi huruf dan spasi.',
    'between'              => '{field} harus antara {param} dan {param1}.',
    'decimal'              => '{field} harus berisi angka desimal.',
    'differs'              => '{field} harus berbeda dari {param}.',
    'exact_length'         => '{field} harus tepat {param} karakter.',
    'greater_than'         => '{field} harus lebih besar dari {param}.',
    'greater_than_equal_to' => '{field} harus lebih besar atau sama dengan {param}.',
    'hex'                  => '{field} hanya boleh berisi karakter hexadecimal.',
    'in_list'              => '{field} harus salah satu dari: {param}.',
    'integer'              => '{field} harus berisi bilangan bulat.',
    'is_natural'           => '{field} harus berisi angka positif.',
    'is_natural_no_zero'   => '{field} harus berisi angka lebih dari nol.',
    'is_not_unique'        => '{field} harus berisi nilai yang sudah ada sebelumnya.',
    'is_unique'            => '{field} sudah digunakan, silakan pilih yang lain.',
    'less_than'            => '{field} harus kurang dari {param}.',
    'less_than_equal_to'   => '{field} harus kurang dari atau sama dengan {param}.',
    'matches'              => '{field} tidak sama dengan {param}.',
    'max_length'           => '{field} tidak boleh melebihi {param} karakter.',
    'min_length'           => '{field} minimal {param} karakter.',
    'not_in_list'          => '{field} tidak boleh salah satu dari: {param}.',
    'numeric'              => '{field} harus berisi angka saja.',
    'regex_match'          => '{field} tidak dalam format yang benar.',
    'required'             => '{field} harus diisi.',
    'required_with'        => '{field} harus diisi ketika {param} diisi.',
    'required_without'     => '{field} harus diisi ketika {param} tidak diisi.',
    'string'               => '{field} harus berupa string yang valid.',
    'timezone'             => '{field} harus berupa zona waktu yang valid.',
    'valid_base64'         => '{field} harus berupa string base64 yang valid.',
    'valid_email'          => '{field} harus berisi alamat email yang valid.',
    'valid_emails'         => '{field} harus berisi semua alamat email yang valid.',
    'valid_ip'             => '{field} harus berisi IP yang valid.',
    'valid_url'            => '{field} harus berisi URL yang valid.',
    'valid_url_strict'     => '{field} harus berisi URL yang valid.',
    'valid_date'           => '{field} harus berisi tanggal yang valid.',
    'valid_json'           => '{field} harus berisi JSON yang valid.',

    // Credit Cards
    'valid_cc_num'         => '{field} tidak terlihat sebagai nomor kartu kredit yang valid.',

    // Files
    'uploaded'             => '{field} bukan file upload yang valid.',
    'max_size'             => '{field} ukuran file terlalu besar.',
    'is_image'             => '{field} bukan file gambar yang valid.',
    'mime_in'              => '{field} tidak memiliki tipe mime yang valid.',
    'ext_in'               => '{field} tidak memiliki ekstensi file yang valid.',
    'max_dims'             => '{field} bukan gambar, atau terlalu lebar atau tinggi.',

    // Error Messages (Custom for this project)
    'check_username'       => 'Username atau password salah.',
    'check_password'       => 'Password tidak sesuai.',
    'old_password_check'   => 'Password lama tidak sesuai.',
    'password_strength'    => 'Password harus mengandung huruf besar, huruf kecil, angka, dan minimal 8 karakter.',
];
