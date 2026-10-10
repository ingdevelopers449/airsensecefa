<?php
/**
 * Generador de PDF del esquema de base de datos - AirSense CEFA
 * Convierte docs/database_schema.md → docs/database_schema.pdf
 * Ejecutar: php generate_schema_pdf.php
 */

// Leer el markdown
$mdPath = __DIR__ . '/docs/database_schema.md';
$pdfPath = __DIR__ . '/docs/database_schema.pdf';

if (!file_exists($mdPath)) {
    die("Error: No se encontró docs/database_schema.md\n");
}

$markdown = file_get_contents($mdPath);

// ── Conversión Markdown → HTML ──────────────────────────────────────────────

function mdToHtml(string $md): string
{
    // Títulos
    $md = preg_replace('/^# (.+)$/m',  '<h1>$1</h1>', $md);
    $md = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $md);
    $md = preg_replace('/^### (.+)$/m','<h3>$1</h3>', $md);

    // Negrita e itálica
    $md = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $md);
    $md = preg_replace('/\*(.+?)\*/',     '<em>$1</em>',         $md);

    // Código inline
    $md = preg_replace('/`([^`]+)`/', '<code>$1</code>', $md);

    // Listas con guión
    $md = preg_replace('/^- (.+)$/m', '<li>$1</li>', $md);
    $md = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $md);

    // Separadores
    $md = preg_replace('/^---$/m', '<hr>', $md);

    // Párrafos (líneas que no son HTML ni vacías)
    $lines   = explode("\n", $md);
    $html    = '';
    $inPara  = false;
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '') {
            if ($inPara) { $html .= "</p>\n"; $inPara = false; }
            continue;
        }
        // Si ya es etiqueta HTML, la pasa directamente
        if (preg_match('/^<[a-zA-Z\/]/', $trimmed)) {
            if ($inPara) { $html .= "</p>\n"; $inPara = false; }
            $html .= $trimmed . "\n";
        } else {
            if (!$inPara) { $html .= "<p>"; $inPara = true; }
            $html .= $trimmed . ' ';
        }
    }
    if ($inPara) $html .= "</p>\n";

    return $html;
}

$body = mdToHtml($markdown);

// ── Plantilla HTML completa ──────────────────────────────────────────────────

$html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Esquema de Base de Datos — AirSense CEFA</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Inter', Arial, sans-serif;
    font-size: 11pt;
    color: #1e293b;
    line-height: 1.6;
    padding: 40px 50px;
    max-width: 960px;
    margin: auto;
  }

  /* ── ENCABEZADO ──────────────────────── */
  .header {
    background: linear-gradient(135deg, #002235 0%, #004d6e 100%);
    color: white;
    padding: 28px 32px;
    border-radius: 10px;
    margin-bottom: 36px;
  }
  .header h1 { font-size: 22pt; font-weight: 700; color: #39A900; margin: 0 0 6px 0; }
  .header p  { font-size: 10pt; color: #94a3b8; margin: 0; }
  .header .badge {
    display: inline-block;
    background: #39A900;
    color: white;
    font-size: 8pt;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
    margin-top: 10px;
  }

  /* ── TÍTULOS ─────────────────────────── */
  h1 { font-size: 20pt; color: #002235; border-bottom: 3px solid #39A900; padding-bottom: 8px; margin: 28px 0 16px; }
  h2 {
    font-size: 13pt;
    font-weight: 700;
    color: white;
    background: #002235;
    padding: 8px 14px;
    border-left: 5px solid #39A900;
    border-radius: 0 6px 6px 0;
    margin: 28px 0 10px;
    page-break-after: avoid;
  }
  h3 { font-size: 11pt; color: #002235; margin: 16px 0 6px; }

  /* ── LISTAS ──────────────────────────── */
  ul { list-style: none; margin: 0 0 12px 0; padding: 0; }
  li {
    padding: 5px 10px 5px 22px;
    position: relative;
    border-bottom: 1px solid #f1f5f9;
    font-size: 10pt;
  }
  li:last-child { border-bottom: none; }
  li::before {
    content: "▸";
    position: absolute;
    left: 6px;
    color: #39A900;
    font-size: 9pt;
  }

  /* ── CÓDIGO INLINE ───────────────────── */
  code {
    background: #f1f5f9;
    color: #002235;
    font-family: 'Courier New', monospace;
    font-size: 9.5pt;
    padding: 1px 5px;
    border-radius: 3px;
    font-weight: 600;
  }

  /* ── SEPARADORES ─────────────────────── */
  hr { border: none; border-top: 1px solid #e2e8f0; margin: 20px 0; }

  /* ── PÁRRAFOS ────────────────────────── */
  p { margin: 0 0 10px; font-size: 10pt; color: #475569; }

  /* ── TARJETA DE TABLA ────────────────── */
  h2 + ul, h2 + p + ul {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0 0 8px 8px;
    padding: 6px 0;
    margin-bottom: 6px;
  }

  /* ── PIE DE PÁGINA ───────────────────── */
  .footer {
    margin-top: 48px;
    padding-top: 16px;
    border-top: 2px solid #e2e8f0;
    text-align: center;
    font-size: 9pt;
    color: #94a3b8;
  }
  .footer strong { color: #002235; }

  /* ── PAGINACIÓN ──────────────────────── */
  @page { margin: 20mm 15mm; }
</style>
</head>
<body>

<div class="header">
  <h1>📊 Esquema de Base de Datos</h1>
  <p>Documentación oficial de todas las tablas del sistema AirSense CEFA</p>
  <span class="badge">AirSense CEFA · SENA La Angostura</span>
</div>

{$body}

<div class="footer">
  <strong>AirSense CEFA</strong> · Centro de Formación Agroindustrial La Angostura · SENA Regional Huila<br>
  Generado automáticamente desde <code>docs/database_schema.md</code> · <?= date('d/m/Y H:i') ?>
</div>

</body>
</html>
HTML;

// ── Guardar HTML intermedio ──────────────────────────────────────────────────
$htmlPath = __DIR__ . '/docs/database_schema.html';
file_put_contents($htmlPath, $html);
echo "✅ HTML generado: docs/database_schema.html\n";

// ── Intentar generar PDF con wkhtmltopdf ────────────────────────────────────
$wkhtmltopdf = null;
$candidates  = [
    'C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf.exe',
    'C:/Program Files (x86)/wkhtmltopdf/bin/wkhtmltopdf.exe',
    'wkhtmltopdf',
];
foreach ($candidates as $c) {
    if (@file_exists($c) || (shell_exec("where $c 2>nul") !== null)) {
        $wkhtmltopdf = $c;
        break;
    }
}

if ($wkhtmltopdf) {
    $cmd = "\"$wkhtmltopdf\" --enable-local-file-access --page-size A4 --margin-top 10 --margin-bottom 10 --margin-left 10 --margin-right 10 \"$htmlPath\" \"$pdfPath\" 2>&1";
    $output = shell_exec($cmd);
    if (file_exists($pdfPath)) {
        echo "✅ PDF generado: docs/database_schema.pdf\n";
    } else {
        echo "⚠️  wkhtmltopdf falló. Usa el HTML como alternativa.\n";
        echo $output . "\n";
    }
} else {
    echo "ℹ️  wkhtmltopdf no está instalado.\n";
    echo "   Abre docs/database_schema.html en Chrome y usa Ctrl+P → Guardar como PDF.\n";
    echo "   El HTML ya está listo con estilos completos.\n";
}
