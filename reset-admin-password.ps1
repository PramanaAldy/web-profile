param(
    [Parameter(Mandatory = $true)]
    [string]$Username,
    [string]$Email
)

$ErrorActionPreference = 'Stop'
$php = Get-Command php -CommandType Application
$setupArguments = @((Join-Path $PSScriptRoot 'setup.php'), 'password', $Username)
if ($Email) {
    $setupArguments += $Email
}

$secret = Read-Host 'Password admin baru (minimal 15 karakter)' -AsSecureString
$previousOutputEncoding = $OutputEncoding
try {
    $OutputEncoding = [System.Text.UTF8Encoding]::new($false)
    [System.Net.NetworkCredential]::new('', $secret).Password | & $php.Source @setupArguments
    $exitCode = $LASTEXITCODE
} finally {
    $OutputEncoding = $previousOutputEncoding
    $secret.Dispose()
}
exit $exitCode
