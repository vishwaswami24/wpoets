 <?php
require_once __DIR__ . '/config.php';

$items = [];
try {
  $stmt = $pdo->query('SELECT id, title, badge, description, learn_more_url, web_image_path, mobile_bg_image_path, sort_order FROM sliders ORDER BY sort_order ASC, id ASC');
  $items = $stmt->fetchAll();
} catch (Throwable $e) {}
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DelphianLogic in Action</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="assets/app.css" />
  <link rel="stylesheet" href="assets/slider-both.css" />
</head>
<body>

<section class="slider-section">
  <div class="container">

    <!-- Header -->
    <div class="section-header">
      <h2>DelphianLogic in Action</h2>
      <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo</p>
    </div>

    <!-- ── DESKTOP ── -->
    <div class="slider-grid desktop-only">

      <!-- Col 1: Tab cards -->
      <div class="slider-tabs">
        <?php foreach ($items as $idx => $item):
          $icon = htmlspecialchars($item['mobile_bg_image_path'] ?: '');
        ?>
          <button type="button" class="tab-card<?= $idx === 0 ? ' active' : '' ?>" data-tab="<?= $idx ?>">
            <?php if ($icon): ?>
              <img src="<?= $icon ?>" alt="" class="tab-icon" />
            <?php endif; ?>
            <span><?= htmlspecialchars($item['title']) ?></span>
          </button>
        <?php endforeach; ?>
      </div>

      <!-- Col 2: Slide panel -->
      <div class="slide-col">
        <?php foreach ($items as $idx => $item): ?>
          <div class="slide-pane<?= $idx === 0 ? '' : ' d-none' ?>" data-pane="<?= $idx ?>">
            <div class="slide-bg" style="background-image:url('<?= htmlspecialchars($item['web_image_path']) ?>')"></div>
            <div class="slide-content">
              <?php if ($item['badge']): ?>
                <div class="slide-badge"><?= htmlspecialchars($item['badge']) ?></div>
              <?php endif; ?>
              <div class="slide-title"><?= htmlspecialchars($item['description'] ?? $item['title']) ?></div>
              <a href="<?= htmlspecialchars($item['learn_more_url'] ?? '#') ?>" class="slide-learn">
                Learn More &nbsp;&#8594;
              </a>
              <!-- Dot indicators -->
              <div class="carousel-indicators mt-3" style="position:relative;margin:20px 0 0;">
                <?php foreach ($items as $di => $d): ?>
                  <button type="button" data-dot="<?= $di ?>"
                    class="<?= $di === $idx ? 'active' : '' ?>"
                    style="width:10px;height:10px;border-radius:50%;border:none;background:<?= $di === $idx ? '#fff' : 'rgba(255,255,255,0.45)' ?>;margin-right:6px;padding:0;cursor:pointer;"></button>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Col 3: Photo -->
      <div class="photo-col">
        <img id="synced-image" src="<?= !empty($items) ? htmlspecialchars($items[0]['web_image_path']) : '' ?>" alt="<?= !empty($items) ? htmlspecialchars($items[0]['title']) : '' ?>" />
      </div>

    </div><!-- /slider-grid -->

    <!-- ── MOBILE ── -->
    <div class="mobile-only">
      <?php foreach ($items as $idx => $item):
        $mobileUrl = htmlspecialchars($item['mobile_bg_image_path'] ?: $item['web_image_path']);
        $icon = htmlspecialchars($item['mobile_bg_image_path'] ?: '');
      ?>
        <div class="mobile-tab-card">
          <div class="mobile-tab-header" data-mobile-tab="<?= $idx ?>">
            <div class="tab-left">
              <?php if ($icon): ?>
                <img src="<?= $icon ?>" alt="" class="tab-icon" />
              <?php endif; ?>
              <span><?= htmlspecialchars($item['title']) ?></span>
            </div>
            <span class="mobile-tab-toggle"><?= $idx === 0 ? '&#8722;' : '&#43;' ?></span>
          </div>
          <div class="mobile-slide-body<?= $idx === 0 ? '' : ' d-none' ?>">
            <div class="slide-bg" style="background-image:url('<?= htmlspecialchars($item['web_image_path']) ?>')"></div>
            <div style="position:relative;z-index:2;">
              <?php if ($item['badge']): ?>
                <div class="slide-badge"><?= htmlspecialchars($item['badge']) ?></div>
              <?php endif; ?>
              <div class="slide-title"><?= htmlspecialchars($item['description'] ?? $item['title']) ?></div>
              <a href="<?= htmlspecialchars($item['learn_more_url'] ?? '#') ?>" class="slide-learn">Learn More &nbsp;&#8594;</a>
              <div style="margin-top:16px;">
                <?php foreach ($items as $di => $d): ?>
                  <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:<?= $di === $idx ? '#fff' : 'rgba(255,255,255,0.45)' ?>;margin-right:6px;"></span>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<script>
(function () {
  var items = <?= json_encode(array_values($items)) ?>;

  // Desktop tab switching
  document.querySelectorAll('.tab-card').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var idx = Number(this.getAttribute('data-tab'));
      switchTo(idx);
    });
  });

  // Desktop dot switching
  document.querySelectorAll('[data-dot]').forEach(function (dot) {
    dot.addEventListener('click', function () {
      switchTo(Number(this.getAttribute('data-dot')));
    });
  });

  function switchTo(idx) {
    // Tabs
    document.querySelectorAll('.tab-card').forEach(function (b) {
      b.classList.toggle('active', Number(b.getAttribute('data-tab')) === idx);
    });
    // Panes
    document.querySelectorAll('.slide-pane').forEach(function (p) {
      p.classList.toggle('d-none', Number(p.getAttribute('data-pane')) !== idx);
    });
    // Col 3 image
    var img = document.getElementById('synced-image');
    if (img && items[idx]) {
      img.src = items[idx].web_image_path;
      img.alt = items[idx].title;
    }
  }

  // Mobile accordion
  document.querySelectorAll('[data-mobile-tab]').forEach(function (header) {
    header.addEventListener('click', function () {
      var idx = Number(this.getAttribute('data-mobile-tab'));
      var card = this.closest('.mobile-tab-card');
      var body = card.querySelector('.mobile-slide-body');
      var toggle = card.querySelector('.mobile-tab-toggle');
      var isOpen = !body.classList.contains('d-none');

      // Close all
      document.querySelectorAll('.mobile-slide-body').forEach(function (b) { b.classList.add('d-none'); });
      document.querySelectorAll('.mobile-tab-toggle').forEach(function (t) { t.innerHTML = '&#43;'; });

      if (!isOpen) {
        body.classList.remove('d-none');
        toggle.innerHTML = '&#8722;';
      }
    });
  });
})();
</script>
</body>
</html>
