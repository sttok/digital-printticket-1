<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Setting;
use App\Models\ContadorSms;
use App\Models\ContadorSmsDetalle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    public function sendsms($data)
    {
        $mensaje = $data['mensaje'];
        $telefono = $data['telefono'];
        $setting = Setting::findorfail(1);
        if ($setting->sms_notification == 1) {
            $mensaje = $mensaje . '%0d%0a' .
                urlencode('Descarga nuestra app de android: ' . 'https://bit.ly/2ZrBDEi') . '%0d%0a' .
                urlencode('Visitanos en: ') . urlencode(route('inicio.frontend')) . '&';

            $url = 'http://api.labsmobile.com/get/send.php?';
            $url .= 'username=' . $setting->labsmobile_account . '&';
            $url .= 'password=' . $setting->labsmobile_token . '&';
            $url .= 'msisdn=' . urlencode($telefono) . '&';
            $url .= 'message=' . $mensaje;
            //$url .= 'test=1';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $result = curl_exec($ch);
            curl_close($ch);
            $setting->contador_sms++;
            $setting->update();

            return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
        } else {
            return response()->json(['success' => false, 'msg' => __('Ha ocurrido un error, contacta al administrador'), 'data' => $data], 200);
        }
    }

    public function sendsms2($data)
    {
        $mensaje = $data['mensaje'];
        $telefono = $data['telefono'];
        $setting = Setting::findorfail(1);

        if ($setting->sms_notification == 1) {
            $mensaje = $mensaje . '%0d%0a' .
                urlencode('Siguenos en nuestra redes sociales como @Printticket, tu like nos ayudara a llenar este evento') . '&';
            //urlencode('Facebook: ' . 'https://www.facebook.com/Printticket') . '%0d%0a' .
            //urlencode('Instagram: ' . 'https://www.instagram.com/printticket/') .'&';
            // urlencode('Descarga nuestra app de android: ' . 'https://bit.ly/2ZrBDEi') . '%0d%0a' .
            // urlencode('Visitanos en: ') . urlencode( route('inicio.frontend') ) .'&';

            $url = 'http://api.labsmobile.com/get/send.php?';
            $url .= 'username=' . $setting->labsmobile_account . '&';
            $url .= 'password=' . $setting->labsmobile_token . '&';
            $url .= 'msisdn=' . urlencode($telefono) . '&';
            $url .= 'message=' . $mensaje;
            //$url .= 'test=1';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $result = curl_exec($ch);
            curl_close($ch);
            $setting->contador_sms++;
            $setting->update();
            return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
        } else {
            return response()->json(['success' => false, 'msg' => __('Ha ocurrido un error, contacta al administrador'), 'data' => $data], 200);
        }
    }

    public function sendsms3($data)
    {
        $mensaje = $data['mensaje'];
        $telefono = $data['telefono'];
        $setting = Setting::findorfail(1);

        if ($setting->sms_notification == 1) {
            $mensaje = $mensaje . '%0d%0a' .
                urlencode('Visitanos en: ') . urlencode(route('inicio.frontend')) . '&';

            $url = 'http://api.labsmobile.com/get/send.php?';
            $url .= 'username=' . $setting->labsmobile_account . '&';
            $url .= 'password=' . $setting->labsmobile_token . '&';
            $url .= 'msisdn=' . urlencode($telefono) . '&';
            $url .= 'message=' . $mensaje;
            //$url .= 'test=1';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $result = curl_exec($ch);
            curl_close($ch);
            $setting->contador_sms++;
            $setting->update();
            return response()->json(['success' => true, 'msg' => null, 'data' => $data], 200);
        } else {
            return response()->json(['success' => false, 'msg' => __('Ha ocurrido un error, contacta al administrador'), 'data' => $data], 200);
        }
    }
}
