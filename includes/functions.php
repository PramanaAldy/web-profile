<?php
require_once __DIR__ . '/../db.php';

// ── Profile ─────────────────────────────────
function getProfile(): array {
    $db = getDB();
    return $db->query("SELECT * FROM profile LIMIT 1")->fetch() ?: [];
}

// ── Skills ──────────────────────────────────
function getSkillsGrouped(): array {
    $db = getDB();
    $rows = $db->query("SELECT * FROM skills WHERE is_active=1 ORDER BY category, sort_order")->fetchAll();
    $grouped = [];
    foreach ($rows as $row) {
        $grouped[$row['category']][] = $row;
    }
    return $grouped;
}

// ── Experience ──────────────────────────────
function getExperiences(): array {
    $db = getDB();
    return $db->query("SELECT * FROM experience ORDER BY is_current DESC, start_date DESC")->fetchAll();
}

// ── Projects ────────────────────────────────
function getProjects(bool $featuredOnly = false): array {
    $db = getDB();
    $sql = "SELECT * FROM projects" . ($featuredOnly ? " WHERE is_featured=1" : "") . " ORDER BY sort_order, created_at DESC";
    return $db->query($sql)->fetchAll();
}

// ── Services ────────────────────────────────
function getServices(): array {
    $db = getDB();
    return $db->query("SELECT * FROM services WHERE is_active=1 ORDER BY sort_order")->fetchAll();
}

// ── Helpers ─────────────────────────────────
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function formatDate(string $date, bool $monthYear = true): string {
    if (!$date) return 'Present';
    $ts = strtotime($date);
    return $monthYear ? date('M Y', $ts) : date('Y', $ts);
}

function techBadges(string $stack): string {
    $techs = array_map('trim', explode(',', $stack));
    $html = '';
    foreach ($techs as $tech) {
        $html .= '<span class="badge">' . e($tech) . '</span>';
    }
    return $html;
}

function saveMessage(array $data): bool {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$data['name'], $data['email'], $data['subject'] ?? '', $data['message']]);
}
