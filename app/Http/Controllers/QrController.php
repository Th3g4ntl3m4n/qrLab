<?php

namespace App\Http\Controllers;

use App\Models\GeneratedQr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
/*use SimpleSoftwareIO\QrCode\Facades\QrCode;*/
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\GdImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;


// chillerlan
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;



class QrController extends Controller
{
public function store(Request $request)
{
    $data = $request->validate([
        'url' => ['required','url','max:2048'],
    ]);

    $userId = $request->user()->id;
    $uuid   = (string) Str::uuid();

    $dir  = "qrcodes/{$userId}";
    $disk = Storage::disk('public');
    $disk->makeDirectory($dir);

    $scale  = 8;
    $margin = 1;

    // ---------------- PNG (GD) ----------------
    $pngRelPath = null;
    if (extension_loaded('gd')) {
        $pngOptions = new QROptions([
            'outputType'  => QRCode::OUTPUT_IMAGE_PNG, // binario
            'imageBase64' => false,                    // desactivar data-uri/base64
            'scale'       => $scale,
            'margin'      => $margin,
        ]);

        $pngContent = (new QRCode($pngOptions))->render($data['url']);

        // Validar cabecera PNG
        if (is_string($pngContent) && strlen($pngContent) > 8 && strncmp($pngContent, "\x89PNG", 4) === 0) {
            $pngRelPath = "{$dir}/{$uuid}.png";
            $disk->put($pngRelPath, $pngContent);
        }
    }

    // ---------------- SVG ----------------
    $svgOptions = new QROptions([
        'outputType'  => QRCode::OUTPUT_MARKUP_SVG, // markup XML
        'imageBase64' => false,                     // asegurar que NO sea data-uri
        'scale'       => $scale,
        'margin'      => $margin,
    ]);

    $svgContent = (new QRCode($svgOptions))->render($data['url']);

    // Validar que empiece por "<" (XML/markup)
    if (!is_string($svgContent) || $svgContent === '' || $svgContent[0] !== '<') {
        // Como último recurso, si falla SVG, intenta forzar PNG o lanza error controlado
        return back()->withErrors(['url' => 'No se pudo generar el SVG del QR.']);
    }

    $svgRelPath = "{$dir}/{$uuid}.svg";
    $disk->put($svgRelPath, $svgContent);

    // Registrar en BD
    GeneratedQr::create([
        'user_id'  => $userId,
        'url'      => $data['url'],
        'file_png' => $pngRelPath ?? '',
        'file_svg' => $svgRelPath,
    ]);

    return redirect()
        ->route('dashboard')
        ->with('status', $pngRelPath
            ? 'QR generado correctamente (PNG y SVG).'
            : 'QR generado (solo SVG). Habilita/extensión GD para PNG.'
        );
}


    public function download(GeneratedQr $qr, string $format)
{
    abort_unless($qr->user_id === auth()->id(), 403);
    if (!in_array($format, ['png','svg'])) abort(404);

    $path = $format === 'png' ? $qr->file_png : $qr->file_svg;
    abort_if(empty($path), 404);

    $disk = Storage::disk('public');
    abort_unless($disk->exists($path), 404);

    $filename = 'qr-'.pathinfo($path, PATHINFO_FILENAME).".{$format}";

    return $disk->download($path, $filename);
}


public function destroy(GeneratedQr $qr){
  abort_unless($qr->user_id === auth()->id(), 403);
  $disk = Storage::disk('public');
  foreach ([$qr->file_png, $qr->file_svg] as $p){ if($p && $disk->exists($p)) $disk->delete($p); }
  $qr->delete();
  return back()->with('status','QR eliminado.');
}

}
