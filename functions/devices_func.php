<?php
require_once 'functions.php';
class UserDetails extends DB{
  private $agent = "";
  private $browsers = array(
      'SamsungBrowser'=> 'Samsung Browser',
      'MiuiBrowser'   => 'Xiaomi Browser',
      'NokiaBrowser'  => 'Nokia Browser',
      'UCBrowser'     => 'UC Browser',
      'OPR'           => 'Opera',
      'Flock'         => 'Flock',
      'Edge'          => 'Edge',
      'edg'           => 'Edge',
      'Maxthon'       => 'Maxthon',           
      'Opera.*?Version' => 'Opera',
      'Opera'         => 'Opera',
      'MSIE'          => 'Internet Explorer',
      'Internet Explorer' => 'Internet Explorer',
      'Trident.* rv'  => 'Internet Explorer',
      'YaBrowser'     => 'Yandex Browser',
      'Chrome'        => 'Chrome',
      'Shiira'        => 'Shiira',
      'Firefox'       => 'Firefox',
      'Chimera'       => 'Chimera',
      'Phoenix'       => 'Phoenix',
      'Firebird'      => 'Firebird',
      'Camino'        => 'Camino',
      'Netscape'      => 'Netscape',
      'OmniWeb'       => 'OmniWeb',
      'Safari'        => 'Safari',
      'Mozilla'       => 'Mozilla',
      'Konqueror'     => 'Konqueror',
      'icab'          => 'iCab',
      'Lynx'          => 'Lynx',
      'Links'         => 'Links',
      'hotjava'       => 'HotJava',
      'amaya'         => 'Amaya',
      'IBrowse'       => 'IBrowse',
      'Ubuntu'        => 'Ubuntu Web Browser',
      
  );
  private $device_name = array(
      'MI '           => 'MI',
      'vivo'          => 'Vivo',
      'Ubuntu'        => 'Ubuntu',
      'S25'           => 'Smart S-25',
      'Moto G'        => 'Moto G',
      'LAVA'          => 'LAVA',
      'RMX'           => 'Oppo Realme',
      'Lenovo'        => 'Lenovo',
      'iPhone'        => 'iPhone',
      'pixel'         => 'Google Pixel',
      'BB'            => 'BlackBerry',
      'HUAWEI'        => 'HUAWEI',
      'LM'            => 'LG',
      'LGM'           => 'LG',
      'LG'            => 'LG',
      'windows phone' => 'Windows Phone',
      'ASUS_'         => 'Asus ZenFone',
      'KFAPWI'        => 'Kindle Fire',
      'SM-'           => 'Samsung',
      'HONOR'         => 'Huawei Honor',
      'A37F'          => 'Oppo',
      'ONEPLUS'       => 'One Plus',
      'Karbonn'       => 'Karbonn',
      'XT'            => 'Motorola',
      'os x'          => 'Apple Mac',
      'mobileexplorer' => 'Mobile Explorer',
      'palmsource'    => 'Palm',
      'palmscape'     => 'Palmscape',
      'motorola'      => 'Motorola',
      'nokia'         => 'Nokia',
      'nexus'         => 'Nexus',
      'palm'          => 'Palm',
      
      'ipad'          => 'iPad',
      'ipod'          => 'Apple iPod Touch',
      'sony'          => 'Sony Ericsson',
      'ericsson'      => 'Sony Ericsson',
      'blackberry'    => 'BlackBerry',
      'cocoon'        => 'O2 Cocoon',
      'blazer'        => 'Treo',
      'lg'            => 'LG',
      'amoi'          => 'Amoi',
      'xda'           => 'XDA',
      'mda'           => 'MDA',
      'vario'         => 'Vario',
      'htc'           => 'HTC',
      'samsung'       => 'Samsung',
      'sharp'         => 'Sharp',
      'sie-'          => 'Siemens',
      'alcatel'       => 'Alcatel',
      'benq'          => 'BenQ',
      'ipaq'          => 'HP iPaq',
      'mot-'          => 'Motorola',
      'playstation portable'  => 'PlayStation Portable',
      'playstation 3'     => 'PlayStation 3',
      'playstation vita'      => 'PlayStation Vita',
      'hiptop'        => 'Danger Hiptop',
      'nec-'          => 'NEC',
      'panasonic'     => 'Panasonic',
      'philips'       => 'Philips',
      'sagem'         => 'Sagem',
      'sanyo'         => 'Sanyo',
      'spv'           => 'SPV',
      'zte'           => 'ZTE',
      'sendo'         => 'Sendo',
      'nintendo dsi'  => 'Nintendo DSi',
      'nintendo ds'   => 'Nintendo DS',
      'nintendo 3ds'  => 'Nintendo 3DS',
      'wii'           => 'Nintendo Wii',
      'open web'      => 'Open Web',
      'openweb'       => 'OpenWeb',
      'android'       => 'Android',
      'symbian'       => 'Symbian',
      'SymbianOS'     => 'SymbianOS',
      'elaine'        => 'Palm',
      'series60'      => 'Symbian S60',
      'windows ce'    => 'Windows CE',
      'windows'       => 'Desktop',
      'obigo'         => 'Obigo',
      'netfront'      => 'Netfront Browser',
      'openwave'      => 'Openwave Browser',
      'mobilexplorer' => 'Mobile Explorer',
      'operamini'     => 'Opera Mini',
      'opera mini'    => 'Opera Mini',
      'opera mobi'    => 'Opera Mobile',
      'fennec'        => 'Firefox Mobile',
      'digital paths' => 'Digital Paths',
      'avantgo'       => 'AvantGo',
      'xiino'         => 'Xiino',
      'novarra'       => 'Novarra Transcoder',
      'vodafone'      => 'Vodafone',
      'docomo'        => 'NTT DoCoMo',
      'o2'            => 'O2',
      'mobile'        => 'Generic Mobile',
      'wireless'      => 'Generic Mobile',
      'j2me'          => 'Generic Mobile',
      'midp'          => 'Generic Mobile',
      'cldc'          => 'Generic Mobile',
      'up.link'       => 'Generic Mobile',
      'up.browser'    => 'Generic Mobile',
      'smartphone'    => 'Generic Mobile',
      'cellphone'     => 'Generic Mobile',
      'Linux'         => 'Linux',
  );
  private $os = array(
      'windows nt 10.0'   => 'Windows 10',
      'windows nt 6.3'    => 'Windows 8.1',
      'windows nt 6.2'    => 'Windows 8',
      'windows nt 6.1'    => 'Windows 7',
      'windows nt 6.0'    => 'Windows Vista',
      'windows nt 5.2'    => 'Windows 2003',
      'windows nt 5.1'    => 'Windows XP',
      'windows nt 5.0'    => 'Windows 2000',
      'windows nt 4.0'    => 'Windows NT 4.0',
      'winnt4.0'          => 'Windows NT 4.0',
      'winnt 4.0'         => 'Windows NT',
      'winnt'             => 'Windows NT',
      'windows 98'        => 'Windows 98',
      'win98'             => 'Windows 98',
      'windows 95'        => 'Windows 95',
      'win95'             => 'Windows 95',
      'windows phone'     => 'Windows Phone',
      'windows'           => 'Unknown Windows OS',
      'android'           => 'Android',
      'blackberry'        => 'BlackBerry',
      'BB'                => 'BlackBerry',
      'iphone'            => 'iOS',
      'ipad'              => 'iOS',
      'ipod'              => 'iOS',
      'os x'              => 'Mac OS X',
      'ppc mac'           => 'Power PC Mac',
      'freebsd'           => 'FreeBSD',
      'ppc'               => 'Macintosh',
      'Linux'             => 'Linux',
      'debian'            => 'Debian',
      'sunos'             => 'Sun Solaris',
      'beos'              => 'BeOS',
      'apachebench'       => 'ApacheBench',
      'aix'               => 'AIX',
      'irix'              => 'Irix',
      'osf'               => 'DEC OSF',
      'hp-ux'             => 'HP-UX',
      'netbsd'            => 'NetBSD',
      'bsdi'              => 'BSDi',
      'openbsd'           => 'OpenBSD',
      'gnu'               => 'GNU/Linux',
      'unix'              => 'Unknown Unix OS',
      'symbian'           => 'Symbian OS',
      'Nokia'             => 'Nokia Mobile',
  );

  function __construct() {
      $this->agent = $_SERVER['HTTP_USER_AGENT'];
  }
  public function get_browser() {
      $browser_name = "Unknown Browser";
      foreach ($this->browsers as $key => $val) {
          if (preg_match('|'.$key.'.|i', $this->agent, $match)) {
              $browser_name = $val;
              break;
          }
      }
      return $browser_name;
  }

  public function get_os() {
      $device_name = "Unknown Platform";
      foreach ($this->os as $key => $val) {
          if (preg_match('|'.preg_quote($key).'|i', $this->agent)) {
              $device_name = $val;
              break;
          }
      }
      return $device_name;
  }

  public function get_device_name() {
      $mobile = "unknown";
      foreach ($this->device_name as $key => $val) {
          if (FALSE !== (stripos($this->agent, $key))) {
              $mobile = $val;
              break;
          }
      }
      return $mobile;
  }

public  function get_country() {
    $file = file_get_contents('http://ip4.me/');
    // Trim IP based on HTML formatting
    $pos = strpos( $file, '+3' ) + 3;
    $ip = substr( $file, $pos, strlen( $file ) );
    
    // Trim IP based on HTML formatting
    $pos = strpos( $ip, '</' );
    function getUserIP() {
        $ip = '';
    
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP من خلال proxy
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP من خلال proxy أو load balancer
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]; // أحيانًا يحتوي على أكثر من IP
        } else {
            // IP الحقيقي
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    
        return trim($ip);
    }
    //$external_ip = explode('/',substr( $ip, 0, $pos ))[0];
    $external_ip =  getUserIP();
    
// Output the IP address of your box
      $output = array(
          "city"           => 'unknown',
          "state"          => '',
          "country"        => '',
          "country_code"   => '',
          "continent"      => '',
          "continent_code" => ''
      );
      $ip = $this->get_ip();
      $continents = array(
          "AF" => "Africa",
          "AN" => "Antarctica",
          "AS" => "Asia",
          "EU" => "Europe",
          "OC" => "Australia (Oceania)",
          "NA" => "North America",
          "SA" => "South America"
      );

      if (filter_var($ip, FILTER_VALIDATE_IP)) {

        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$external_ip"));

          if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {

              $output = array(
                  "external_ip"    =>$external_ip,
                  "country"        => @$ipdat->geoplugin_countryName,
                  "city"           => @$ipdat->geoplugin_city,
                  "region"   => @$ipdat->geoplugin_regionName,
                  "city_time"           => @$ipdat->geoplugin_timezone,
                  "width"          => @$ipdat->geoplugin_latitude,
                  "hieght"   => @$ipdat->geoplugin_longitude,
              );
          }
      }
      return $output;
  }

  public function get_device() {
      $tablet_browser = 0;
      $mobile_browser = 0;
      if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($this->agent))) {
          $tablet_browser++;
      }
      if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($this->agent))) {
          $mobile_browser++;
      }
      if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
          $mobile_browser++;
      }
      $mobile_ua = strtolower(substr($this->agent, 0, 4));
      $mobile_agents = array(
          'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
          'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
          'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
          'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
          'newt','noki','palm','pana','pant','phil','play','port','prox',
          'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
          'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
          'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
          'wapr','webc','winw','winw','xda ','xda-');
      if (in_array($mobile_ua,$mobile_agents)) {
          $mobile_browser++;
      }
      if (strpos(strtolower($this->agent),'opera mini') > 0) {
          $mobile_browser++;
              //Check for tablets on opera mini alternative headers
          $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
          if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
              $tablet_browser++;
          }
      }
      if ($tablet_browser > 0) {
          return 'Tablet';
      }
      else if ($mobile_browser > 0) {
          return 'Mobile';
      }
      else {
          return 'Desktop';
      }   
  }
  public function get_ip() {
      $mainIp = '';
      if(getenv('REMOTE_ADDR'))
          $mainIp = getenv('REMOTE_ADDR');
      else
          $mainIp = 'UNKNOWN';
      return $mainIp;
  }
}

class Devices extends DB
{
    function edit_devices()
    {
        $id = validation($_POST['id']);
        $status = validation($_POST['status']);
        $sql = $this->connect()->query("UPDATE devices SET active = '$status' WHERE id = '$id'");
        if ($sql) {
            echo '<input type="hidden" id="success" value="success" />';
        }
    }
    function delete_devices()
    {
        $id = validation($_POST['id']);
        $sql = $this->connect()->query("DELETE FROM devices WHERE id = '$id'");
        if ($sql) {
            echo '<input type="hidden" id="success" value="success" />';
        }
    }
}
$devices = new Devices;
switch (validation($_GET['f'])) {
    case 'edit_status':
        $devices->edit_devices();
        break;
    case 'del_device':
        $devices->delete_devices();
        break;
}
?>

