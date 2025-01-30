<div align="center">
    <img alt="Packagist Downloads" src="https://img.shields.io/packagist/dd/rayiumir/vordia">
    <img alt="Packagist Downloads" src="https://img.shields.io/packagist/dm/rayiumir/vordia">
    <img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/rayiumir/vordia">
    <img alt="Packagist License" src="https://img.shields.io/packagist/l/rayiumir/vordia">
    <img alt="GitHub Release" src="https://img.shields.io/github/v/release/rayiumir/vordia">
    <img alt="Packagist Dependency Version" src="https://img.shields.io/packagist/dependency-v/rayiumir/vordia/PHP">
</div>

# Vordia

A simple and lightweight mobile authentication package for Laravel And it has a default admin panel.

# How to use

Install Package :

```bash
composer require rayiumir/vordia
```

After Publish Config Files:

```bash
php artisan vendor:publish --provider="Rayiumir\\Vordia\\ServiceProvider\\VordiaServiceProvider"
```

And Migration Database:

```bash
php artisan migrate
```

Add Fields in Model user.php :

```php
protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'otp',
        'login_token'
    ];
```

# SMS API KEY

Currently, the Vordia package uses the `sms.ir` panel, and other panels will be added in the near future.

Add this code to `.env`

```bash
SMSIR_API_KEY= Add API KEY
SMSIR_OTP_TEMPLATE_ID= ADD OTP TEMPLATE ID
```
