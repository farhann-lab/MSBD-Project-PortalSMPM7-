#requires -version 5.1
<#[
  Reorganize existing SIAKAD migration files into database/migrations.
  PREVIEW: .\RapiMigrasiSIAKAD.ps1
  APPLY:   .\RapiMigrasiSIAKAD.ps1 -Apply
  Does not touch the MySQL database or migration history.
  Existing legacy RKM and optional object migrations are renamed to .php.stub
  to avoid executing them accidentally on `php artisan migrate`.
]#>
param([switch]$Apply)
$ErrorActionPreference = 'Stop'
$repoRoot = (Get-Location).Path
if (-not (Test-Path -LiteralPath (Join-Path $repoRoot 'artisan'))) {
    throw 'Jalankan dari folder root Laravel (yang berisi file artisan).'
}
$mainDir = Join-Path $repoRoot 'database\migrations'
if (-not (Test-Path -LiteralPath $mainDir)) { throw 'Folder database/migrations tidak ditemukan.' }
$all = @(Get-ChildItem -LiteralPath $mainDir -Recurse -File -Filter '*_baseline_*.php')
if ($all.Count -eq 0) {
    Write-Host 'Tidak ada file migration *_baseline_*.php untuk dipindahkan. Bisa jadi sudah dirapikan.'
    exit 0
}
$pattern = '^(?<stamp>\d{4}_\d{2}_\d{2}_\d{6})_baseline_(?<name>.+)\.php$'
$moves = New-Object System.Collections.Generic.List[object]
foreach ($f in $all) {
    $m = [regex]::Match($f.Name, $pattern)
    if (-not $m.Success) { throw "Format migration tidak dikenali: $($f.FullName)" }
    $category = if ($f.DirectoryName -eq $mainDir) { 'siakad' }
                elseif ($f.DirectoryName -eq (Join-Path $mainDir 'legacy_rkm_bootstrap')) { 'rkm' }
                elseif ($f.DirectoryName -eq (Join-Path $mainDir 'optional_db_objects')) { 'objects' }
                else { throw "Subfolder tidak dikenal: $($f.DirectoryName)" }
    $stamp = $m.Groups['stamp'].Value
    $name = $m.Groups['name'].Value
    $newName = if ($category -eq 'objects') { "${stamp}_create_${name}.php.stub" }
               elseif ($category -eq 'rkm') { "${stamp}_create_${name}_table.php.stub" }
               else { "${stamp}_create_${name}_table.php" }
    $dest = Join-Path $mainDir $newName
    if (Test-Path -LiteralPath $dest) { throw "Target sudah ada, perubahan dihentikan: $dest" }
    $sourceRel = $f.FullName.Substring($repoRoot.Length + 1).Replace('\','/')
    $targetRel = $dest.Substring($repoRoot.Length + 1).Replace('\','/')
    $moves.Add([pscustomobject]@{
        Source = $f.FullName; Destination = $dest; OldPath = $sourceRel;
        NewPath = $targetRel; Category = $category;
        OldMigration = $f.BaseName; NewMigration = [IO.Path]::GetFileNameWithoutExtension($newName.Replace('.php.stub','.php'))
    })
}
$summary = $moves | Group-Object Category | Sort-Object Name
$summary | Select-Object Name,Count | Format-Table -AutoSize
Write-Host "Total: $($moves.Count) file."
Write-Host 'Contoh perubahan nama:'
$moves | Select-Object -First 6 OldPath,NewPath | Format-Table -AutoSize
if (-not $Apply) {
    Write-Host 'MODE PREVIEW: tidak ada perubahan. Jalankan lagi dengan -Apply untuk eksekusi.'
    exit 0
}
# Preflight all paths first; Git will handle tracked files and Move-Item handles untracked files.
foreach ($m in $moves) {
    if (-not (Test-Path -LiteralPath $m.Source)) { throw "Sumber hilang: $($m.Source)" }
    if (Test-Path -LiteralPath $m.Destination) { throw "Target sudah ada: $($m.Destination)" }
}
foreach ($m in $moves) {
    $tracked = @(& git ls-files --cached -- $m.OldPath)
    if ($LASTEXITCODE -ne 0) { throw 'git ls-files gagal. Pastikan sedang di repository Git.' }
    if ($tracked.Count -gt 0) {
        & git mv -- $m.OldPath $m.NewPath
        if ($LASTEXITCODE -ne 0) { throw "git mv gagal: $($m.OldPath)" }
    } else {
        Move-Item -LiteralPath $m.Source -Destination $m.Destination
    }
}
foreach ($extra in @('legacy_rkm_bootstrap','optional_db_objects')) {
    $d = Join-Path $mainDir $extra
    if (Test-Path -LiteralPath $d) {
        if (@(Get-ChildItem -LiteralPath $d -Force).Count -eq 0) {
            Remove-Item -LiteralPath $d
        } else {
            Write-Warning "Subfolder $extra tidak dihapus karena masih memiliki file lain."
        }
    }
}
Write-Host "Selesai: $($moves.Count) migration dipindahkan/dirapikan."
Write-Warning 'DATABASE TIDAK DIUBAH. Jika migration lama sudah Ran, nama di tabel migrations perlu direkonsiliasi di DEV sebelum php artisan migrate.'
Write-Host 'Cek: git status; php artisan migrate:status --database=mysql'
