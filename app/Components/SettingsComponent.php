<?php 
	namespace App\Components;

use App\Setting;
use App\Upload;
	
class SettingsComponent{
  private static $settings = null;
  private static $affiliateId = false;
  public static $paymentMethods = [
    'stripe'=>'CreditCard with Stripe',
    'paypal'=>'PayPal',
  ];
  public static $defaults = [
      'emailNotifications'=>'uicab2593@gmail.com',
      'contactPhone'=>'+521998',
      'address'=>'',
      'phoneUs'=>'+521998',
      'phoneMx'=>'+521998',
      'phoneCo'=>'+521998',
      'phoneCl'=>'+521998',
      'logoUrl'=>'/images/no-image.jpg',
      'icoUrl'=>'/images/travel-ico.png',

      'facebookLink'=>'',
      'twitterLink'=>'',
      'googleLink'=>'',
      'instagramLink'=>'',

      'color1'=>'#3C9CD8',
      'color2'=>'#077db6',
      'color3'=>'#FABD0A',
      'color4'=>'#000000',

      'termsText'=>'',
      'privacyText'=>'',
      'hotelPolicy'=>'',
      'hotelRatePolicy'=>'',
      'toursPolicy'=>'',
      'transferPolicy'=>'',

      'paymentMethods'=>[],
      'paypalAccount'=>"",
      'paypalUser'=>"",
      'paypalPass'=>"",
      'paypalSign'=>"",

      'stripeApiKeyLive'=>"",
      'stripeApiKeySandbox'=>"",
  ];
  public static function initAffiliate($affiliateId)
  {
    if($affiliateId == false) $affiliateId = \SC::$affiliateId;
    // echo self::$affiliateId;
    if(self::$affiliateId == false || self::$affiliateId != $affiliateId){
      self::$affiliateId = $affiliateId;
      self::$settings = null;
    }
  }
  public static function get($key=false,$affiliateId=false)
  {
    $settings = self::getSettings($affiliateId);
    if($key === false) return $settings;
    return $settings[$key];
  }
  public static function set($key,$value)
  {
      $setting = Setting::where('key',$key)
      ->where('affiliateId',\SC::$affiliateId)
      ->first();

      if($setting==null){
        $setting = new Setting;
        $setting->key = $key;
        $setting->affiliateId = \SC::$affiliateId;
      }
      $setting->value = serialize($value);
      $setting->save();
      // if ($id=='color1') {
        // var_dump($setting);
      // }
  }
  public static function getSettings($affiliateId=false)
  {
    self::initAffiliate($affiliateId);
    if(self::$settings===null){
      $settings = Setting::where('affiliateId',self::$affiliateId)->get();
      // crea arreglo
      $parseSettings = [];
      foreach ($settings as $s) $parseSettings[$s->key] = @unserialize($s->value);
      self::$settings = array_replace_recursive(self::$defaults,$parseSettings);
    }
    return self::$settings;
  }
  public static function getLogoUrl()
  {
    return \SC::getAffiliate()->getPrimaryImageUrl();
  }
  public static function getIcoUrl()
  {
      $up = Upload::where([
          'referenceId'=>\SC::$affiliateId,
          'folder'=>'icos',
      ])->orderBy('orderx','ASC')->first();
      if($up) return UploadsComponent::url($up);
      return \SC::getAffiliate()->getPrimaryImageUrl();
  }
}