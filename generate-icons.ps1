# generate-icons.ps1
# Generates CBTwise PWA icons using .NET System.Drawing (no external deps)
# Run: powershell -ExecutionPolicy Bypass -File generate-icons.ps1

Add-Type -AssemblyName System.Drawing

$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$outputDir   = Join-Path $projectRoot "public\icons"

if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir -Force | Out-Null
    Write-Host "Created: $outputDir"
}

$sizes = @(72, 96, 128, 144, 152, 192, 384, 512)

# Brand colours
$emerald = [System.Drawing.Color]::FromArgb(255, 16, 185, 129)   # #10b981
$teal    = [System.Drawing.Color]::FromArgb(255, 13, 148, 136)   # #0d9488
$white   = [System.Drawing.Color]::White

foreach ($size in $sizes) {
    $bmp = New-Object System.Drawing.Bitmap($size, $size, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)
    $g   = [System.Drawing.Graphics]::FromImage($bmp)

    $g.SmoothingMode   = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias
    $g.CompositingMode = [System.Drawing.Drawing2D.CompositingMode]::SourceOver

    # Clear to transparent
    $g.Clear([System.Drawing.Color]::Transparent)

    # ── Gradient rounded-rect background ──────────────────────────────────────
    $radius = [int]($size * 0.22)

    $brush = New-Object System.Drawing.Drawing2D.LinearGradientBrush(
        [System.Drawing.Point]::new(0, 0),
        [System.Drawing.Point]::new($size, 0),
        $emerald,
        $teal
    )

    # Build a rounded rectangle path
    $path = New-Object System.Drawing.Drawing2D.GraphicsPath
    $d    = $radius * 2
    $path.AddArc(0,             0,             $d, $d, 180, 90)  # top-left
    $path.AddArc($size - $d,    0,             $d, $d, 270, 90)  # top-right
    $path.AddArc($size - $d,    $size - $d,    $d, $d, 0,   90)  # bottom-right
    $path.AddArc(0,             $size - $d,    $d, $d, 90,  90)  # bottom-left
    $path.CloseFigure()

    $g.FillPath($brush, $path)

    # ── White "C" arc ─────────────────────────────────────────────────────────
    $cx      = $size / 2.0
    $cy      = $size / 2.0
    $outerR  = $size * 0.29
    $innerR  = $size * 0.18
    $penW    = [float]($size * 0.085)

    $whitePen = New-Object System.Drawing.Pen($white, $penW)
    $whitePen.StartCap = [System.Drawing.Drawing2D.LineCap]::Round
    $whitePen.EndCap   = [System.Drawing.Drawing2D.LineCap]::Round

    # Draw arc for "C" — from 45° to 315° (opening on the right)
    $arcRect = [System.Drawing.RectangleF]::new(
        [float]($cx - $outerR),
        [float]($cy - $outerR),
        [float]($outerR * 2),
        [float]($outerR * 2)
    )
    $g.DrawArc($whitePen, $arcRect, 45, 270)

    # ── White checkmark in the opening area ──────────────────────────────────
    $chkPenW = [float]($size * 0.07)
    $chkPen  = New-Object System.Drawing.Pen($white, $chkPenW)
    $chkPen.StartCap = [System.Drawing.Drawing2D.LineCap]::Round
    $chkPen.EndCap   = [System.Drawing.Drawing2D.LineCap]::Round
    $chkPen.LineJoin = [System.Drawing.Drawing2D.LineJoin]::Round

    # Position checkmark in the right opening of the "C"
    $chkCx = [float]($cx + $outerR * 0.52)
    $chkCy = [float]($cy + $outerR * 0.10)
    $s     = [float]($size * 0.09)

    $pts = @(
        [System.Drawing.PointF]::new($chkCx - $s,        $chkCy),
        [System.Drawing.PointF]::new($chkCx - $s * 0.2, $chkCy + $s * 0.9),
        [System.Drawing.PointF]::new($chkCx + $s * 1.1, $chkCy - $s * 0.9)
    )
    $g.DrawLines($chkPen, $pts)

    # ── Save ──────────────────────────────────────────────────────────────────
    $outPath = Join-Path $outputDir "icon-${size}x${size}.png"
    $bmp.Save($outPath, [System.Drawing.Imaging.ImageFormat]::Png)

    $g.Dispose()
    $bmp.Dispose()
    $brush.Dispose()
    $whitePen.Dispose()
    $chkPen.Dispose()
    $path.Dispose()

    Write-Host "OK  icon-${size}x${size}.png"
}

# ── Generate 512x512 logo.png ─────────────────────────────────────────────────
$src512 = Join-Path $outputDir "icon-512x512.png"
$logoDst = Join-Path $projectRoot "public\logo.png"
Copy-Item $src512 $logoDst -Force
Write-Host "OK  public/logo.png"

# ── Generate 32x32 favicon.png ────────────────────────────────────────────────
$srcBmp  = [System.Drawing.Bitmap]::FromFile($src512)
$favBmp  = New-Object System.Drawing.Bitmap(32, 32, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)
$favG    = [System.Drawing.Graphics]::FromImage($favBmp)
$favG.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias
$favG.DrawImage($srcBmp, 0, 0, 32, 32)
$favG.Dispose()
$srcBmp.Dispose()

$favDst = Join-Path $projectRoot "public\favicon.png"
$favBmp.Save($favDst, [System.Drawing.Imaging.ImageFormat]::Png)
$favBmp.Dispose()
Write-Host "OK  public/favicon.png"

Write-Host "`nAll PWA assets generated successfully!"
